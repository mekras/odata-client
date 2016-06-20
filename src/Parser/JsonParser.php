<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\Exception\InvalidFormatException;
use Mekras\OData\Client\Response\Error;
use Mekras\OData\Client\Response\Response;

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
     * @param string $contents The response body.
     *
     * @return Response
     *
     * @throws InvalidFormatException
     *
     * @since 1.0
     */
    public function parse($contents)
    {
        $data = json_decode($contents, true);
        if (!is_array($data)) {
            $error = json_last_error_msg();
            throw InvalidFormatException::create('JSON', $contents, $error);
        }

        if (array_key_exists('error', $data)) {
            $message = '(unknown)';
            if (array_key_exists('message', $data['error'])) {
                $message = $data['error']['message'];
                if (is_array($message) && array_key_exists('value', $message)) {
                    $message = $message['value'];
                } else {
                    $message = (string) $message;
                }
            }
            $code = null;
            if (array_key_exists('code', $data['error'])) {
                $code = (int) $data['error']['code'];
            }
            $message = rtrim($message, '.!') . '.';

            return new Error($message, $code);
        }

        if (!array_key_exists('d', $data)) {
            throw new InvalidFormatException('Missing "d" key in response: ' . $contents);
        }

        return new Response($data['d']);
    }
}
