<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Exception;

/**
 * Invalid format exception
 *
 * @since 1.0
 */
class InvalidFormatException extends RuntimeException
{
    /**
     * Exception factory.
     *
     * @param string     $format   Expected format (e. g. "JSON").
     * @param string     $data     Invalid data (or only part of data).
     * @param string     $error    Error description.
     * @param \Exception $previous Previous exception.
     *
     * @return InvalidFormatException
     *
     * @since x.xx
     */
    public static function create($format, $data = null, $error = null, \Exception $previous = null)
    {
        if ($data) {
            $data = (string) $data;
            if (mb_strlen($data, 'utf-8') > 64) {
                $data = mb_substr($data, 0, 64, 'utf-8') . '…';
            }
            $data = ' in "' . $data . '"';
        }

        $error = $error
            ? ucfirst((string) $error)
            : ($data ? 'Unexpected data' : '');
        $message = trim("Invalid {$format}. {$error}{$data}");

        return new self($message, 0, $previous);
    }
}
