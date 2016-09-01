<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Element;

use Mekras\AtomPub\Element\Element as BaseElement;
use Mekras\OData\Client\OData;

/**
 * Abstract OData element.
 *
 * @since 0.3
 */
abstract class Element extends BaseElement
{
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
        $xpath->registerNamespace('m', OData::META);
        $xpath->registerNamespace('d', OData::DATA);

        return $xpath;
    }
}
