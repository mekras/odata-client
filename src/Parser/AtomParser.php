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
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/atom-format/
 */
class AtomParser implements ResponseParser
{
    const NS_DATA = 'http://schemas.microsoft.com/ado/2007/08/dataservices';
    const NS_META = 'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata';
    const NS_ATOM = 'http://www.w3.org/2005/Atom';
    const NS_APP = 'http://www.w3.org/2007/app';

    /**
     * Parse Atom response.
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
        $doc = $this->createDocument($contents);

        switch ($doc->documentElement->tagName) {
            case 'error':
                $message = '(unknown)';
                $nodes = $doc->getElementsByTagName('message');
                if ($nodes->length > 0) {
                    $message = trim($nodes->item(0)->textContent);
                }
                $code = 0;
                $nodes = $doc->getElementsByTagName('code');
                if ($nodes->length > 0) {
                    $code = (int) $nodes->item(0)->textContent;
                }
                $message = rtrim($message, '.!') . '.';

                return new Error($message, $code);

            case 'service':
                $object = $this->parseServiceDocument($doc->documentElement);
                break;

            case 'feed':
                $object = $this->parseEntitySet($doc->documentElement);
                break;

            case 'entry':
                $object = $this->parseEntry($doc->documentElement);
                break;

            default:
                $object = $this->parseProperties($doc->childNodes);
        }

        return new Response($object);
    }

    /**
     * Create DOMDocument from XML
     *
     * @param string $xml
     *
     * @return \DOMDocument
     *
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function createDocument($xml)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');

        $libxmlInternalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();
        $doc->loadXML($xml);
        /** @var \libXMLError[] $errors */
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($libxmlInternalErrors);

        if (count($errors) > 0) {
            $message = [];
            foreach ($errors as $error) {
                $message[] = sprintf(
                    '[%s] %s at %d:%d',
                    $error->code,
                    $error->message,
                    $error->line,
                    $error->column
                );
            }
            throw new InvalidDataException(implode('; ', $message));
        }

        return $doc;
    }

    /**
     * Parse ServiceDocument
     *
     * @param \DOMElement $element
     *
     * @return ServiceDocument
     */
    private function parseServiceDocument(\DOMElement $element)
    {
        $doc = [
            'EntitySets' => []
        ];

        $collections = $element->getElementsByTagName('collection');
        foreach ($collections as $collection) {
            /** @var \DOMElement $collection */
            $doc['EntitySets'][] = trim(
                $collection->getElementsByTagNameNS(self::NS_ATOM, 'title')->item(0)->textContent
            );
        }

        return new ServiceDocument($doc);
    }

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
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     */
    private function parseProperties(\DOMNodeList $elements)
    {
        $properties = new ComplexType();
        foreach ($elements as $element) {
            if ($element instanceof \DOMElement) {
                $name = $element->localName;
                $properties[$name] = $this->parsePrimitive($element);
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
    private function parsePrimitive(\DOMElement $element)
    {
        if ($element->hasAttributeNS(self::NS_META, 'null')) {
            return new NullType();
        }

        $value = trim($element->textContent);

        switch ($element->getAttributeNS(self::NS_META, 'type')) {
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
