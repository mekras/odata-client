<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Exception;

/**
 * Server issued error
 *
 * @since 1.0
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
     * @since 1.0
     */
    public static function createFromArray(array $errorData, $code = 0)
    {
        $message = '(unknown)';
        if (array_key_exists('message', $errorData)) {
            $message = $errorData['message'];
            if (is_array($message) && array_key_exists('value', $message)) {
                $message = $message['value'];
            } else {
                $message = (string) $message;
            }
        }
        if (array_key_exists('code', $errorData)) {
            $code = (int) $errorData['code'];
        }
        $message = rtrim($message, '.!') . '.';

        return new static($message, $code);
    }
}
