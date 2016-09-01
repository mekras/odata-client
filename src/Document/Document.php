<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

use Mekras\Atom\Atom;
use Mekras\Atom\Extensions;
use Mekras\AtomPub\Document\Document as AtomPubDocument;
use Mekras\OData\Client\OData;

/**
 * OData document.
 *
 * @since 0.3
 */
abstract class Document extends AtomPubDocument
{
    /**
     * Create document.
     *
     * @param Extensions        $extensions Extension registry.
     * @param \DOMDocument|null $document   Source document.
     *
     * @throws \InvalidArgumentException If $document root node has invalid name.
     *
     * @since 0.3
     */
    public function __construct(Extensions $extensions, \DOMDocument $document = null)
    {
        parent::__construct($extensions, $document);
        if (null === $document) {
            $this->getDomDocument()->documentElement
                ->setAttributeNS(Atom::XMLNS, 'xmlns:m', OData::META);
            $this->getDomDocument()->documentElement
                ->setAttributeNS(Atom::XMLNS, 'xmlns:d', OData::DATA);
        }
    }

    /**
     * Get the XPath query object
     *
     * @return \DOMXPath
     *
     * @since 0.3
     */
    protected function getXPath()
    {
        $xpath = parent::getXPath();
        $xpath->registerNamespace('d', OData::DATA);
        $xpath->registerNamespace('m', OData::META);

        return $xpath;
    }
}
