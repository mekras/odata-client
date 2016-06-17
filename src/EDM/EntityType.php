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
 * OData Entry
 *
 * @since 1.0
 *
 * @link http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class EntityType extends ODataValue
{
    /**
     * ServiceDocument constructor.
     *
     * @param array $raw Raw data.
     *
     * @throws InvalidDataException If $raw is not an array.
     *
     * @since 1.0
     */
    public function __construct($raw)
    {
        if (!is_array($raw)) {
            throw new InvalidDataException(__METHOD__ . ' expects $raw to be an array');
        }
        parent::__construct($raw);
    }
}
