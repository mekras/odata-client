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
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\Exception\LogicException;
use Mekras\OData\Client\OData;

/**
 * OData Entry.
 *
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class Entry extends BaseEntry implements \ArrayAccess
{
    /**
     * Return entity type.
     *
     * @return string|null
     *
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 0.3
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
     * @throws \Mekras\OData\Client\Exception\LogicException
     *
     * @since 0.3
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

        try {
            $this->addCategory($type)->setScheme(OData::SCHEME);
        } catch (\InvalidArgumentException $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return entity properties.
     *
     * @return Properties
     *
     * @since 0.3
     */
    public function getProperties()
    {
        return $this->getCachedProperty(
            'properties',
            function () {
                // No REQUIRED — no exception
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
     * @since 0.3
     */
    public function getRelations()
    {
        return $this->getCachedProperty(
            'relations',
            function () {
                $relations = [];
                /** @var \DOMNodeList $nodes */
                $nodes = $this->query('atom:link[starts-with(@rel, "' . OData::RELATED . '")]');
                foreach ($nodes as $node) {
                    /** @var Link $link */
                    $link = $this->getExtensions()->parseElement($this, $node);
                    $relations[$link->getTitle()] = $link;
                }

                return $relations;
            }
        );
    }

    /**
     * Add relation to some resource.
     *
     * @param Entry|string $resource Entry or resource IRI.
     * @param string|null  $type     Can be omitted if $resource is an instance of Entry.
     *
     * @return Link
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 0.3
     */
    public function addRelation($resource, $type = null)
    {
        /** @var Link $link */
        $link = $this->addChild('atom:link', 'relations');
        $link->setType('application/atom+xml;type=entry');
        if ($resource instanceof Entry) {
            if (null === $type) {
                $type = $resource->getEntityType();
            }
            $link->setUri($resource->getLink('self'));
        } else {
            if (null === $type) {
                throw new \InvalidArgumentException(
                    '$type should be specified if $resource not an Entry'
                );
            }
            $link->setUri((string) $resource);
        }
        $link->setRelation(OData::RELATED . '/' . $type);
        $link->setTitle($type);

        return $link;
    }

    /**
     * Whether a offset exists
     *
     * @param string $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
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
     * @throws \Mekras\OData\Client\Exception\LogicException
     *
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('Removing properties not supported');
    }
}
