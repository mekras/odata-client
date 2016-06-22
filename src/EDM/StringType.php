<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * String primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class StringType extends Primitive
{
    /**
     * Create value.
     *
     * @param bool $value String value.
     */
    public function __construct($value)
    {
        parent::__construct((string) $value, 'Edm.String');
    }
}
