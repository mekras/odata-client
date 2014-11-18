<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Exception;

use Exception;
use RuntimeException;

/**
 * Invalid format exception
 *
 * @since x.xx
 */
class InvalidFormatException extends RuntimeException
{
    /**
     * Exception factory
     *
     * @param string    $format   expected format (e. g. "JSON")
     * @param string    $data     invalid data (or only part of data)
     * @param string    $error    error description
     * @param Exception $previous previous exception
     *
     * @return InvalidFormatException
     */
    public static function create($format, $data = null, $error = null, Exception $previous = null)
    {
        if ($data) {
            $data = strval($data);
            if (mb_strlen($data, 'utf-8') > 64) {
                $data = mb_substr($data, 0, 64, 'utf-8') . '…';
            }
            $data = ' in "' . $data . '"';
        }

        $error = $error
            ? ucfirst(strval($error))
            : ($data ? 'Unexpected data' : '');
        $message = trim("Invalid {$format}. {$error}{$data}");
        return new self($message, 0, $previous);
    }
}
