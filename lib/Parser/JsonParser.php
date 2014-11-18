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
 * OData service JSON response parser
 *
 * @since x.xx
 */
class JsonParser implements ResponseParserInterface
{
    /**
     * Parses response and returns object
     *
     * @param string $string
     *
     * @throws InvalidFormatException
     *
     * @return array
     *
     * @since x.xx
     */
    public function parse($string)
    {
        $array = json_decode($string, true);
        if (!is_array($array)) {
            $error = function_exists('json_last_error_msg') ? json_last_error_msg() : null;
            throw InvalidFormatException::create('JSON', $string, $error);
        }
        return $array;
    }
}
