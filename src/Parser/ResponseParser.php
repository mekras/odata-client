<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\Exception\InvalidFormatException;

/**
 * OData service response parser interface
 *
 * @since 1.00
 */
interface ResponseParser
{
    /**
     * Parse response to array.
     *
     * @param string $response The response body.
     *
     * @return array Generalized data.
     *
     * @throws InvalidFormatException
     *
     * @since 1.0
     */
    public function parse($response);
}
