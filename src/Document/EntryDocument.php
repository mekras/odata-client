<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

use Mekras\Atom\Document\EntryDocument as BaseEntryDocument;
use Mekras\Atom\Extensions;

/**
 * OData Service error response.
 *
 * @since 1.0
 */
class EntryDocument extends BaseEntryDocument
{
    /**
     * Create document.
     *
     * @param Extensions        $extensions Extension registry.
     * @param \DOMDocument|null $document   Source document.
     *
     * @throws \InvalidArgumentException If $document root node has invalid name.
     *
     * @since 1.0
     */
    public function __construct(Extensions $extensions, $document = null)
    {
        parent::__construct($extensions, $document);
        if (null === $document) {
            /* For new (empty) entry we should create all necessary nodes. */
            $entry = $this->getEntry();
            $entry->addAuthor('');
            $entry->addContent('', 'text');
            $entry->getProperties(); // Create properties node.
        }
    }

    /**
     * Return entry.
     *
     * @return \Mekras\OData\Client\Element\Entry
     *
     * @since 1.0
     */
    public function getEntry()
    {
        // This method is a syntactic sugar.
        return parent::getEntry();
    }
}
