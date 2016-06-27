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
use Mekras\OData\Client\EDM\Error;
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

/**
 * JSON response parser.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/json-format/
 */
class JsonParser implements ResponseParser
{
    /**
     * Parse JSON response.
     *
     * @param string $contents The response body.
     *
     * @return ODataValue
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

        return $this->parseObject($data['d']);
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
     * NOTE. This method can not detect Edm.Binary.
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
            $value = (int) $value;
            if ($value >= -32768 && $value <= 32767) {
                return new IntegerType($value, IntegerType::INT16);
            }

            return new IntegerType($value, IntegerType::INT32);
        }

        if (is_numeric($value)) {
            if (filter_var($value, FILTER_VALIDATE_INT)) {
                $value = (int) $value;
                if ($value > -128 && $value < 0) {
                    return new IntegerType($value, IntegerType::SBYTE);
                }
                if ($value < 256) {
                    return new IntegerType($value, IntegerType::BYTE);
                }

                return new IntegerType($value, IntegerType::INT64);
            } elseif (filter_var($value, FILTER_VALIDATE_FLOAT)) {
                return new FloatType($value);
            }
        }

        if (preg_match(DateTimeType::PATTERN, $value)) {
            return DateTimeType::createFromString($value);
        }

        if (preg_match(GuidType::PATTERN, $value)) {
            return new GuidType($value);
        }

        if (preg_match('/^(\d+\.\d+)m$/i', $value, $matches)) {
            return new FloatType($matches[1], FloatType::DECIMAL);
        }

        if (preg_match('/^(\d+(\.\d+|E[+-]\d+))d$/', $value, $matches)) {
            return new FloatType($matches[1], FloatType::DOUBLE);
        }

        if (preg_match('/^(\d+\.\d+)f$/', $value, $matches)) {
            return new FloatType($matches[1], FloatType::SINGLE);
        }

        if (preg_match('/^(-?\d+)L$/', $value, $matches)) {
            return new IntegerType($matches[1], IntegerType::INT64);
        }

        return new StringType($value);
    }
}
