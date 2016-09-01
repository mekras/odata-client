<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

/**
 * OData constants.
 *
 * @since 0.3
 */
class OData
{
    /**
     * OData method: get resource.
     *
     * @since 0.3
     */
    const GET = 'GET';

    /**
     * OData method: create resource.
     *
     * @since 0.3
     */
    const CREATE = 'POST';

    /**
     * OData method: update resource.
     *
     * @since 0.3
     */
    const UPDATE = 'PUT';

    /**
     * OData method: delete resource.
     *
     * @since 0.3
     */
    const DELETE = 'DELETE';

    /* XML namespaces */
    const DATA = 'http://schemas.microsoft.com/ado/2007/08/dataservices';
    const META = 'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata';
    const SCHEME = 'http://schemas.microsoft.com/ado/2007/08/dataservices/scheme';
    const RELATED = 'http://schemas.microsoft.com/ado/2007/08/dataservices/related';
}
