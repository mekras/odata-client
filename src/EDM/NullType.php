<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Null primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class NullType extends Primitive
{
    /**
     * Create value.
     */
    public function __construct()
    {
        parent::__construct(null, 'Null');
    }

    /**
     * Represent object as a string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        return '';
    }
}
