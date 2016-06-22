<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Link to some resource
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/terminology/
 */
class Link extends ODataValue
{
    /**
     * Represent link as a URI string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
