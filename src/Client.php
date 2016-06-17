<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

/**
 * OData Client.
 */
class Client
{
    /**
     * OData Service.
     *
     * @var Service
     */
    private $service;

    /**
     * Creates new OData client.
     *
     * @param Service $service OData Service.
     *
     * @since 1.0
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieve collection or entry
     *
     * @param string $uri
     *
     * @return array Generalized data.
     *
     * @throws \Mekras\OData\Client\Exception\ErrorException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     * @throws \Mekras\OData\Client\Exception\InvalidFormatException
     * @throws \Mekras\OData\Client\Exception\LogicException
     * @throws \Mekras\OData\Client\Exception\RuntimeException
     * @throws \Mekras\OData\Client\Exception\ServerErrorException
     * @throws \Mekras\OData\Client\Exception\UnsupportedException
     *
     * @since 1.0
     */
    public function retrieve($uri)
    {
        return $this->service->sendRequest('GET', $uri);
    }
}
