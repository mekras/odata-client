<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\Atom\Document\EntryDocument;
use Mekras\AtomPub\DocumentFactory as BaseDocumentFactory;
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Exception\LogicException;

/**
 * OData document factory.
 *
 * @since 1.0
 */
class DocumentFactory extends BaseDocumentFactory
{
    /**
     * Create new factory.
     *
     * @since 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->getExtensions()->register(new ODataExtension());
    }

    /**
     * Create new entity document object.
     *
     * @param string $type Entity type.
     *
     * @return EntryDocument
     *
     * @throws \Mekras\OData\Client\Exception\LogicException
     *
     * @since 1.0
     */
    public function createEntityDocument($type)
    {
        try {
            $document = $this->createDocument('atom:entry');
        } catch (\InvalidArgumentException $e) {
            throw new LogicException('Can not create entry document', 0, $e);
        }

        if (!$document instanceof EntryDocument) {
            throw new LogicException('Unexpected document type: ' . get_class($document));
        }

        $entry = $document->getEntry();
        if (!$entry instanceof Entry) {
            throw new LogicException('Unexpected entry type: ' . get_class($entry));
        }

        $entry->setEntityType($type);
        $entry->addAuthor('');
        $entry->addContent('', 'text');
        $entry->getProperties(); // Create properties node.

        return $document;
    }
}
