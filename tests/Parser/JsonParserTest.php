<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Parser;

use Mekras\OData\Client\Parser\JsonParser;

/**
 * Tests for Mekras\OData\Client\Parser\JsonParser
 *
 * @covers Mekras\OData\Client\Parser\JsonParser
 */
class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testBasics()
    {
        $parser = new JsonParser();
        $array = $parser->parse('{"foo":"bar"}');
        $this->assertEquals(['foo' => 'bar'], $array);
    }

    /**
     * @expectedException \Mekras\OData\Client\Exception\InvalidFormatException
     */
    public function testMalformedJson()
    {
        $parser = new JsonParser();
        $parser->parse('{"foo":bar"}');
    }
}
