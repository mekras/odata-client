<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\EDM\EntitySet;
use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\EDM\ODataValue;
use Mekras\OData\Client\EDM\ServiceDocument;

/**
 * OData Object Mapper
 *
 * @since 1.0
 */
class ObjectMapper
{
    /**
     * Maps array to OData object.
     *
     * @param array $raw Response as an array.
     *
     * @return ODataValue
     *
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     *
     * @since 1.0
     */
    public function mapArrayToObject(array $raw)
    {
        if (array_key_exists('EntitySets', $raw)) {
            return new ServiceDocument($raw);
        }

        if (array_key_exists('__metadata', $raw)) {
            return new EntityType($raw);
        }

        if (count($raw) && is_numeric(key($raw))) {
            $items = [];
            foreach ($raw as $item) {
                $items[] = $this->mapArrayToObject($item);
            }

            return new EntitySet($items);
        }

        return new ComplexType($raw);
    }
}
