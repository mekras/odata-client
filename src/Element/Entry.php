<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Element;

use Mekras\Atom\Element\Entry as AtomEntry;
use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\Util\Converter;

/**
 * OData Entry.
 *
 * @since 1.0
 */
class Entry extends AtomEntry
{
    /**
     * Return entity.
     *
     * @return EntityType|null
     *
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function getContent()
    {
        return $this->getCachedProperty(
            'content',
            function () {
                $properties = Converter::toComplex($this->query('atom:content/m:properties/*'));

                return new EntityType($properties);
            }
        );
    }
}
