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
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
abstract class Primitive extends ODataValue
{
    /**
     * Data type
     *
     * @var string
     */
    private $type;

    /**
     * Create Primitive.
     *
     * @param mixed  $value Value.
     * @param string $type  Type (see class constants).
     */
    public function __construct($value, $type)
    {
        parent::__construct($value);
        $this->type = $type;
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
        if (!is_scalar($this->value)) {
            return '<Can not convert ' . gettype($this->value) . ' to string>';
        }

        return (string) $this->value;
    }

    /**
     * Return value type
     *
     * @return string
     *
     * @since 1.0
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return value
     *
     * @return mixed
     *
     * @since 1.0
     */
    public function getValue()
    {
        return $this->value;
    }
}
