<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Response;

use Mekras\OData\Client\EDM\ODataValue;

/**
 * OData Service response.
 *
 * @since 1.0
 */
class Response
{
    /**
     * Meta data
     *
     * @var mixed
     */
    private $meta;

    /**
     * Response data
     *
     * @var ODataValue
     */
    private $data;

    /**
     * Response constructor.
     *
     * @param ODataValue $data $data Response payload.
     * @param mixed      $meta Meta data.
     */
    public function __construct(ODataValue $data, $meta = null)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Return meta data.
     *
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Return response payload.
     *
     * @return ODataValue
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set response payload.
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
