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
 * JSON response parser.
 *
 * @since 1.0
 */
class JsonParser implements ResponseParser
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
    public function parse($response)
    {
        $data = json_decode($response, true);
        if (!is_array($data)) {
            $error = function_exists('json_last_error_msg') ? json_last_error_msg() : null;
            throw InvalidFormatException::create('JSON', $response, $error);
        }

        if (array_key_exists('d', $data)) {
            $data['results'] = $data['d'];
            unset($data['d']);
        }

        return $data;
    }
}
