<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Float primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class FloatType extends Primitive
{
    const DECIMAL = 'Edm.Decimal';
    const DOUBLE = 'Edm.Double';
    const SINGLE = 'Edm.Single';

    /**
     * Create value.
     *
     * @param float  $value Float number.
     * @param string $type  Float subtype (see class constants)
     */
    public function __construct($value, $type = self::DECIMAL)
    {
        parent::__construct((float) $value, $type);
    }
}
