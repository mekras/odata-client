<?php
/**
 * OData service response parser interface
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

/**
 * OData service response parser interface
 *
 * @since x.xx
 */
interface ResponseParserInterface
{
    /**
     * Parses response and returns object
     *
     * @param $string
     *
     * @return array
     *
     * @since x.xx
     */
    public function parse($string);
}
