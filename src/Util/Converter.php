<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Util;

use Mekras\OData\Client\EDM\BooleanType;
use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\EDM\DateTimeType;
use Mekras\OData\Client\EDM\FloatType;
use Mekras\OData\Client\EDM\GuidType;
use Mekras\OData\Client\EDM\IntegerType;
use Mekras\OData\Client\EDM\NullType;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\EDM\StringType;
use Mekras\OData\Client\OData;

/**
 * XML to ODataValue converter
 *
 * @since 1.0
 */
class Converter
{
    /**
     * Parse entity set
     *
     * @param \DOMElement $element
     *
     * @return EntitySet
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseEntitySet(\DOMElement $element)
    {
        $collection = new EntitySet();
        $entries = $element->getElementsByTagName('entry');
        foreach ($entries as $entry) {
            $object = $this->parseEntry($entry);
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
     * @param \DOMElement $element
     *
     * @return EntityType
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseEntry(\DOMElement $element)
    {
        /** @var \DOMElement $content */
        $content = $element->getElementsByTagName('content')->item(0);

        $properties = $content->getElementsByTagNameNS(self::NS_META, 'properties')->item(0)
            ->childNodes;

        $entity = new EntityType($this->parseProperties($properties));

        $links = $element->getElementsByTagName('link');
        foreach ($links as $link) {
            /** @var \DOMElement $link */
            if (strpos($link->getAttribute('type'), 'application/atom+xml') === 0) {
                $entity[$link->getAttribute('title')] = new Link($link->getAttribute('href'));
            }
        }

        return $entity;
    }

    /**
     * Parse properties of an entry
     *
     * @param \DOMNodeList $elements
     *
     * @return ComplexType
     *
     * @throws \InvalidArgumentException
     */
    public static function toComplex(\DOMNodeList $elements)
    {
        $properties = new ComplexType();
        foreach ($elements as $element) {
            if ($element instanceof \DOMElement) {
                $name = $element->localName;
                $properties[$name] = self::toPrimitive($element);
            }
        }

        return $properties;
    }

    /**
     * Parse primitive value.
     *
     * @param \DOMElement $element
     *
     * @return Primitive
     *
     * @throws \InvalidArgumentException If $value is not a scalar.
     */
    public static function toPrimitive(\DOMElement $element)
    {
        if ($element->hasAttributeNS(OData::META, 'null')) {
            return new NullType();
        }

        $value = trim($element->textContent);

        switch ($element->getAttributeNS(OData::META, 'type')) {
            case 'Edm.Boolean':
                return new BooleanType('true' === $value);
            case 'Edm.Byte':
                return new IntegerType((int) $value, IntegerType::BYTE);
            case 'Edm.DateTime':
                return DateTimeType::createFromString($value);
            case 'Edm.Decimal':
                return new FloatType((float) $value, FloatType::DECIMAL);
            case 'Edm.Double':
                return new FloatType((float) $value, FloatType::DOUBLE);
            case 'Edm.Guid':
                return new GuidType($value);
            case 'Edm.Int16':
                return new IntegerType((int) $value, IntegerType::INT16);
            case 'Edm.Int32':
                return new IntegerType((int) $value, IntegerType::INT32);
            case 'Edm.Int64':
                return new IntegerType((int) $value, IntegerType::INT64);
            case 'Edm.SByte':
                return new IntegerType((int) $value, IntegerType::SBYTE);
            case 'Edm.Single':
                return new FloatType((float) $value, FloatType::SINGLE);
        }

        return new StringType($value);
    }
}
