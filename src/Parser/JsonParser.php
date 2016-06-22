<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\EDM\BooleanType;
use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\EDM\DateTimeType;
use Mekras\OData\Client\EDM\EntitySet;
use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\EDM\FloatType;
use Mekras\OData\Client\EDM\GuidType;
use Mekras\OData\Client\EDM\IntegerType;
use Mekras\OData\Client\EDM\Link;
use Mekras\OData\Client\EDM\NullType;
use Mekras\OData\Client\EDM\ODataValue;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\EDM\ServiceDocument;
use Mekras\OData\Client\EDM\StringType;
use Mekras\OData\Client\Exception\InvalidDataException;
use Mekras\OData\Client\Exception\InvalidFormatException;
use Mekras\OData\Client\Response\Error;
use Mekras\OData\Client\Response\Response;

/**
 * JSON response parser.
 *
 * @since 1.0
 */
class JsonParser implements ResponseParser
{
    /**
     * Parse response to array.
     *
     * @param string $contents The response body.
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     * @throws InvalidFormatException
     *
     * @since 1.0
     */
    public function parse($contents)
    {
        $data = json_decode($contents, true);
        if (!is_array($data)) {
            $error = json_last_error_msg();
            throw InvalidFormatException::create('JSON', $contents, $error);
        }

        if (array_key_exists('error', $data)) {
            $message = '(unknown)';
            if (array_key_exists('message', $data['error'])) {
                $message = $data['error']['message'];
                if (is_array($message) && array_key_exists('value', $message)) {
                    $message = $message['value'];
                } else {
                    $message = (string) $message;
                }
            }
            $code = null;
            if (array_key_exists('code', $data['error'])) {
                $code = (int) $data['error']['code'];
            }
            $message = rtrim($message, '.!') . '.';

            return new Error($message, $code);
        }

        if (!array_key_exists('d', $data)) {
            throw new InvalidFormatException('Missing "d" key in response: ' . $contents);
        }

        $object = $this->parseObject($data['d']);

        return new Response($object);
    }

    /**
     * Parse value to a ODataValue object
     *
     * @param mixed $data
     *
     * @return ODataValue
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseObject($data)
    {
        if (array_key_exists('EntitySets', $data)) {
            return $this->parseServiceDocument($data);
        }

        if (array_key_exists('__metadata', $data)) {
            return $this->parseEntry($data);
        }

        if (array_key_exists('__deferred', $data)) {
            return $this->parseDeferred($data);
        }

        if (count($data) && is_numeric(key($data))) {
            return $this->parseEntitySet($data);
        }

        return $this->parseProperties($data);
    }

    /**
     * Parse ServiceDocument
     *
     * @param array $data
     *
     * @return ServiceDocument
     */
    private function parseServiceDocument(array $data)
    {
        return new ServiceDocument($data);
    }

    /**
     * Parse entity set
     *
     * @param array $data
     *
     * @return EntitySet
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseEntitySet(array $data)
    {
        $collection = new EntitySet();
        foreach ($data as $item) {
            $object = $this->parseObject($item);
            if (!$object instanceof EntityType) {
                throw new InvalidDataException('Expecting EntityType, got ' . get_class($object));
            }
            $collection->add($object);
        }

        return $collection;
    }

    /**
     * Parse entry
     *
     * @param array $data
     *
     * @return EntityType
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseEntry(array $data)
    {
        return new EntityType(
            $this->parseProperties(array_diff_key($data, ['__metadata' => null])),
            $data['__metadata']
        );
    }

    /**
     * Parse deferred object
     *
     * @param array $data
     *
     * @return Link
     */
    private function parseDeferred(array $data)
    {
        return new Link($data['__deferred']['uri']);
    }

    /**
     * Parse properties of an entry
     *
     * @param array $data
     *
     * @return ComplexType
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseProperties(array $data)
    {
        $property = new ComplexType();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $property[$key] = $this->parseObject($value);
            } else {
                $property[$key] = $this->parsePrimitive($value);
            }
        }

        return $property;
    }

    /**
     * Parse primitive value.
     *
     * @param mixed $value
     *
     * @return Primitive
     *
     * @throws \InvalidArgumentException If $value is not a scalar.
     */
    private function parsePrimitive($value)
    {
        if (null === $value) {
            return new NullType();
        }

        if (!is_scalar($value)) {
            throw new \InvalidArgumentException(gettype($value) . ' is not a primitive value');
        }

        if (is_bool($value)) {
            return new BooleanType($value);
        }

        if (is_int($value)) {
            return new IntegerType($value, IntegerType::INT32);
        }

        if (is_float($value)) {
            return new FloatType($value);
        }

        if (is_numeric($value)) {
            return new IntegerType($value, IntegerType::INT64);
        }

        if (preg_match(DateTimeType::PATTERN, $value, $matches)) {
            $ticks = (int) $matches[1];
            if (count($matches) === 3) {
                $ticks += ($matches[2] * 60);
            }

            return new DateTimeType(new \DateTime('@' . $ticks));
        }

        if (preg_match(GuidType::PATTERN, $value, $matches)) {
            return new GuidType($value);
        }

        return new StringType($value);
    }
}
