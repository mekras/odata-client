<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

use Mekras\AtomPub\Document\Document as AtomPubDocument;
use Mekras\OData\Client\OData;

/**
 * OData document.
 *
 * @since 1.0
 */
abstract class Document extends AtomPubDocument
{
    /**
     * Get the XPath query object
     *
     * @return \DOMXPath
     *
     * @since 1.0
     */
    protected function getXPath()
    {
        $xpath = parent::getXPath();
        $xpath->registerNamespace('d', OData::DATA);
        $xpath->registerNamespace('m', OData::META);

        return $xpath;
    }
}
