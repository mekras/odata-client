<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

use Mekras\OData\Client\Exception\InvalidDataException;

/**
 * Entry Set
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class EntitySet extends ODataValue implements \Countable, \ArrayAccess
{
    /**
     * ServiceDocument constructor.
     *
     * @param ODataValue[] $items Collection items.
     *
     * @throws InvalidDataException If $items is not an array of ODataValue
     *
     * @since 1.0
     */
    public function __construct($items)
    {
        if (!is_array($items)) {
            throw new InvalidDataException(__METHOD__ . ' expects $raw to be an array');
        }
        array_map(
            function ($item) {
                if (!$item instanceof ODataValue) {
                    throw new InvalidDataException(
                        '$items should contain only ODataValue instances'
                    );
                }
            },
            $items
        );
        parent::__construct($items);
    }

    /**
     * Return entry count.
     *
     * @return int
     *
     * @since 1.0
     */
    public function count()
    {
        return count($this->raw);
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
        return array_key_exists($offset, $this->raw);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->raw[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->raw[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->raw[$offset]);
    }
}
