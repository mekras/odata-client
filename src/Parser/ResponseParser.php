<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\EDM\ODataValue;
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
     * @param string $contents The response body.
     *
     * @return ODataValue
     *
     * @throws InvalidFormatException
     *
     * @since 1.0
     */
    public function parse($contents);
}
