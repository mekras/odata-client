<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Integer primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class IntegerType extends Primitive
{
    const BYTE = 'Edm.Byte';
    const INT16 = 'Edm.Int16';
    const INT32 = 'Edm.Int32';
    const INT64 = 'Edm.Int64';
    const SBYTE = 'Edm.SByte';

    /**
     * Create value.
     *
     * @param int    $value Integer number.
     * @param string $type  Integer subtype (see class constants)
     */
    public function __construct($value, $type = self::INT64)
    {
        parent::__construct((int) $value, $type);
    }
}
