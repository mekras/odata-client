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
     * Raw data
     *
     * @var mixed
     */
    protected $raw;

    /**
     * Create ODataValue.
     *
     * @param mixed $raw Raw value.
     *
     * @since 1.0
     */
    public function __construct($raw)
    {
        $this->raw = $raw;
    }
}
