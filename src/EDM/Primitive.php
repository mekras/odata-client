<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Primitive
 *
 * @since 1.0
 *
 * @link http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class Primitive extends ODataValue
{
    /**
     * Represent object as a string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        if (!is_scalar($this->raw)) {
            return '<Can not convert ' . gettype($this->raw) . ' to string>';
        }
        return (string) $this->raw;
    }
}
