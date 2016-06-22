<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Parser;

use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\Parser\JsonParser;
use Mekras\OData\Client\Response\Response;

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
        $response = $parser->parse('{"d":{"foo":"bar"}}');
        static::assertInstanceOf(Response::class, $response);
        $object = $response->getData();
        static::assertInstanceOf(ComplexType::class, $object);
        /** @var ComplexType $object */
        static::assertEquals('bar', $object['foo']);
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
