<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * Filter System Query Option.
 *
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#FilterSystemQueryOption
 */
class Filter
{
    /**
     * Equal.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function eq($arg1, $arg2)
    {
        return $arg1 . ' eq ' . $arg2;
    }

    /**
     * Not equal.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function neq($arg1, $arg2)
    {
        return $arg1 . ' ne ' . $arg2;
    }

    /**
     * Greater than.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function gt($arg1, $arg2)
    {
        return $arg1 . ' gt ' . $arg2;
    }

    /**
     * Greater than or equal.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function gte($arg1, $arg2)
    {
        return $arg1 . ' ge ' . $arg2;
    }

    /**
     * Less than.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function lt($arg1, $arg2)
    {
        return $arg1 . ' lt ' . $arg2;
    }

    /**
     * Less than or equal.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function lte($arg1, $arg2)
    {
        return $arg1 . ' le ' . $arg2;
    }

    /**
     * Logical and.
     *
     * @param array|string ...$args
     *
     * @return string
     */
    public static function lAnd(...$args)
    {
        return implode(' and ', $args);
    }

    /**
     * Logical or.
     *
     * @param array|string ...$args
     *
     * @return string
     */
    public static function lOr(...$args)
    {
        return implode(' or ', $args);
    }

    /**
     * Logical not.
     *
     * @param string $arg
     *
     * @return string
     */
    public static function not($arg)
    {
        return ' not ' . $arg;
    }

    /**
     * Addition.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function add($arg1, $arg2)
    {
        return $arg1 . ' add ' . $arg2;
    }

    /**
     * Subtraction.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function sub($arg1, $arg2)
    {
        return $arg1 . ' sub ' . $arg2;
    }

    /**
     * Multiplication.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function mul($arg1, $arg2)
    {
        return $arg1 . ' mul ' . $arg2;
    }

    /**
     * Division.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function div($arg1, $arg2)
    {
        return $arg1 . ' div ' . $arg2;
    }

    /**
     * Modulo.
     *
     * @param string $arg1
     * @param string $arg2
     *
     * @return string
     */
    public static function mod($arg1, $arg2)
    {
        return $arg1 . ' mod ' . $arg2;
    }

    /**
     * Precedence grouping "(…)".
     *
     * @param string $arg
     *
     * @return string
     */
    public static function group($arg)
    {
        return '(' . $arg . ')';
    }

    /**
     * Represent value as a string.
     *
     * @param string $arg
     *
     * @return string
     */
    public static function str($arg)
    {
        return "'" . $arg . "'";
    }
}
