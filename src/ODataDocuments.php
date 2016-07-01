<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\Atom\Document\Document;
use Mekras\Atom\Extension\DocumentType;
use Mekras\Atom\Node;
use Mekras\OData\Client\Document\EntryDocument;
use Mekras\OData\Client\Document\ErrorDocument;

/**
 * OData document types.
 */
class ODataDocuments implements DocumentType
{
    /**
     * Create OData document from XML document.
     *
     * @param \DOMDocument $document
     *
     * @return Document|null
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function createDocument(\DOMDocument $document)
    {
        if (OData::META === $document->documentElement->namespaceURI) {
            switch ($document->documentElement->localName) {
                case 'error':
                    return new ErrorDocument($document);
            }
        } elseif (Node::ATOM === $document->documentElement->namespaceURI) {
            switch ($document->documentElement->localName) {
                case 'entry':
                    return new EntryDocument($document);
            }
        }

        return null;
    }
}
