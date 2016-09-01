<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\AtomPub\DocumentFactory as BaseDocumentFactory;
use Mekras\OData\Client\Document\EntryDocument;
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Exception\LogicException;

/**
 * OData document factory.
 *
 * @since 0.3
 */
class DocumentFactory extends BaseDocumentFactory
{
    /**
     * Create new factory.
     *
     * @since 0.3
     */
    public function __construct()
    {
        parent::__construct();
        $this->getExtensions()->register(new ODataExtension());
    }

    /**
     * Create new entity document object.
     *
     * @param string $type Optional entity type. Do not forget to call
     *                     {@see \Mekras\OData\Client\Element\Entry::setEntityType()} if omitting
     *                     this argument.
     *
     * @return EntryDocument
     *
     * @throws \Mekras\OData\Client\Exception\LogicException
     *
     * @since 0.3.4 $type made optional.
     * @since 0.3.2
     */
    public function createEntityDocument($type = null)
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

        if ($type) {
            $entry->setEntityType($type);
        }

        return $document;
    }
}
