<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Exception;

use RuntimeException;

/**
 * Server issued error
 *
 * @since x.xx
 */
class ErrorException extends RuntimeException
{
    /**
     * Creates exception from server error data
     *
     * @param array $errorData raw error data
     * @param int   $code      HTTP code
     *
     * @return ErrorException
     *
     * @since x.xx
     */
    public static function createFromArray(array $errorData, $code = 0)
    {
        $message = '(unknown)';
        if (array_key_exists('message', $errorData)) {
            $message = $errorData['message'];
            if (is_array($message) && array_key_exists('value', $message)) {
                $message = $message['value'];
            } else {
                $message = strval($message);
            }
        }
        if (array_key_exists('code', $errorData)) {
            $code = intval($errorData['code']);
        }
        $message = rtrim($message, '.!') . '.';
        return new static($message, $code);
    }
}
