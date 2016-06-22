<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * OData ComplexType.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class ComplexType extends ODataValue implements \ArrayAccess, \Iterator
{
    /**
     * Create value of a ComplexType.
     *
     * @param ODataValue[] $properties Property set.
     *
     * @since 1.0
     */
    public function __construct(array $properties = [])
    {
        parent::__construct([]);
        foreach ($properties as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->value);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return ODataValue
     */
    public function offsetGet($offset)
    {
        return $this->value[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed      $offset The offset to assign the value to.
     * @param ODataValue $value  The value to set.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof ODataValue) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Value for "%s" should be an instance of ODataValue, %s given',
                    $offset,
                    is_object($value) ? get_class($value) : gettype($value)
                )
            );
        }
        $this->value[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->value[$offset]);
    }

    /**
     * Return the current element
     *
     * @return ODataValue
     */
    public function current()
    {
        return current($this->value);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        next($this->value);
    }

    /**
     * Return the key of the current element
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->value);
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== $this->key();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->value);
    }
}
