<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Element;

use Mekras\Atom\Element\Entry as BaseEntry;
use Mekras\Atom\Element\Link;
use Mekras\Atom\Node;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\Exception\LogicException;
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;

/*$elements = $this->query('atom:link[starts-with(@type, "application/atom+xml;")]');
foreach ($elements as $element) {
    $link = new Link($this->getExtensions(), $element);
    $result[$link->getTitle()] = $link;
}*/

/**
 * OData Entry.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class Entry extends BaseEntry implements \ArrayAccess
{
    /**
     * Relations cache.
     *
     * @var Link[]
     */
    private $relations = [];

    /**
     * Create entry.
     *
     * @param  Node            $parent  Parent node.
     * @param \DOMElement|null $element DOM element.
     *
     * @since 1.0
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(Node $parent, $element = null)
    {
        parent::__construct($parent, $element);
        /** @var \DOMNodeList $nodes */
        $nodes = $this->query('atom:link[starts-with(@rel, "' . OData::RELATED . '")]');
        foreach ($nodes as $node) {
            /** @var Link $link */
            $link = $this->getExtensions()->parseElement($this, $node);
            $this->relations[$link->getTitle()] = $link;
        }
    }

    /**
     * Return entity type.
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function getEntityType()
    {
        return $this->getCachedProperty(
            'entityType',
            function () {
                $categories = $this->getCategories();
                foreach ($categories as $category) {
                    if ($category->getScheme() === OData::SCHEME) {
                        return $category->getTerm();
                    }
                }

                return null;
            }
        );
    }

    /**
     * Set entity type.
     *
     * @param string $type
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function setEntityType($type)
    {
        $this->setCachedProperty('entityType', $type);
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            if ($category->getScheme() === OData::SCHEME) {
                $category->setTerm($type);

                return;
            }
        }

        $this->addCategory($type)->setScheme(OData::SCHEME);
    }

    /**
     * Return entity properties.
     *
     * @return Properties
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function getProperties()
    {
        return $this->getCachedProperty(
            'properties',
            function () {
                $node = $this->query('atom:content/m:properties', self::SINGLE);
                if (null === $node) {
                    return $this->getExtensions()
                        ->createElement($this->getContent(), 'm:properties');
                }

                return $this->getExtensions()->parseElement($this->getContent(), $node);
            }
        );
    }

    /**
     * Return entity relations.
     *
     * @return Link[]
     *
     * @since 1.0
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Add relation to some resource.
     *
     * @param Entry|string $resource Entry or resource IRI.
     * @param null         $type     Resource type if $resource is not an Entry.
     *
     * @return Link
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function addRelation($resource, $type = null)
    {
        /** @var Link $link */
        $link = $this->getExtensions()->createElement($this, 'atom:link');
        $link->setType('application/atom+xml;type=entry');
        if ($resource instanceof Entry) {
            $link->setRelation(OData::RELATED . '/' . $resource->getEntityType());
            $link->setTitle($resource->getEntityType());
            $link->setUri($resource->getLink('self'));
        } else {
            if (null === $type) {
                throw new \InvalidArgumentException(
                    '$type should be specified if $resource not an Entry'
                );
            }
            $link->setRelation(OData::RELATED . '/' . $type);
            $link->setTitle($type);
            $link->setUri((string) $resource);
        }

        return $link;
    }

    /**
     * Whether a offset exists
     *
     * @param string $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     */
    public function offsetExists($offset)
    {
        return $this->getProperties()->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param string $offset The offset to retrieve.
     *
     * @return Primitive
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     */
    public function offsetGet($offset)
    {
        return $this->getProperties()->get($offset);
    }

    /**
     * Offset to set
     *
     * @param string $offset The offset to assign the value to.
     * @param mixed  $value  The value to set.
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     * @throws \Mekras\OData\Client\Exception\LogicException
     */
    public function offsetSet($offset, $value)
    {
        if ($value instanceof \DateTimeInterface) {
            $type = Primitive::DATETIME;
        } elseif (is_float($value)) {
            $type = Primitive::DECIMAL;
        } elseif (is_int($value)) {
            $type = Primitive::INT32;
        } else {
            $type = Primitive::STRING;
        }

        $this->getProperties()->add($offset, $value, $type);
    }

    /**
     * Offset to unset
     *
     * @param string $offset The offset to unset.
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     * @throws \Mekras\OData\Client\Exception\LogicException
     *
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('Removing properties not supported');
    }
}
