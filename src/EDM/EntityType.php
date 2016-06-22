<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * OData Entry
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class EntityType extends ComplexType
{
    /**
     * Meta data
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Create value of a ComplexType.
     *
     * @param \Traversable|array $properties Property set.
     * @param array              $metadata   Meta data
     *
     * @since 1.0
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($properties, array $metadata = [])
    {
        parent::__construct([]);
        foreach ($properties as $key => $value) {
            $this[$key] = $value;
        }
        $this->metadata = $metadata;
    }

    /**
     * Return meta data.
     *
     * @return array
     *
     * @since 1.0
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set meta data.
     *
     * @param array $metadata
     *
     * @since 1.0
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }
}
