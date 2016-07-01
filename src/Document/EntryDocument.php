<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

use Mekras\Atom\Document\EntryDocument as AtomEntryDocument;
use Mekras\OData\Client\Element\Entry;

/**
 * OData Entry document.
 *
 * @since 1.0
 */
class EntryDocument extends AtomEntryDocument
{
    /**
     * Return entry.
     *
     * @return Entry
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function getEntry()
    {
        return $this->getCachedProperty(
            'entry',
            function () {
                return new Entry($this->getDomDocument()->documentElement);
            }
        );
    }
}
