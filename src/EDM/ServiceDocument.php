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
 * Abstract ODataValue.
 *
 * This is a root class for all OData data types.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview#ODataBasics
 */
class ServiceDocument extends ODataValue
{
    /**
     * ServiceDocument constructor.
     *
     * @param array $value Raw data.
     *
     * @since 1.0
     */
    public function __construct(array $value)
    {
        parent::__construct($value);
    }

    /**
     * Return entity sets list.
     *
     * @return string[]
     *
     * @throws InvalidDataException
     *
     * @since 1.0
     */
    public function getEntitySets()
    {
        if (!array_key_exists('EntitySets', $this->value)) {
            throw new InvalidDataException('"EntitySets" key is missing');
        }

        return $this->value['EntitySets'];
    }
}
