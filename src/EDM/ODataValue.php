<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Abstract ODataValue.
 *
 * This is a root class for all OData data types.
 *
 * @since 1.0
 */
abstract class ODataValue
{
    /**
     * Value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Create ODataValue.
     *
     * @param mixed $value Value.
     *
     * @since 1.0
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }
}
