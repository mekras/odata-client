<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Parser;

use Mekras\OData\Client\Parser\ParserFactory;

/**
 * Tests for Mekras\OData\Client\Parser\ParserFactory
 *
 * @covers Mekras\OData\Client\Parser\ParserFactory
 */
class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get parser for "application/json"
     */
    public function testJson()
    {
        $factory = new ParserFactory();
        $parser = $factory->getByContentType('application/json');
        static::assertInstanceOf('Mekras\OData\Client\Parser\JsonParser', $parser);
        static::assertSame($parser, $factory->getByContentType('application/json'));
    }

    /**
     * UnsupportedException should be thrown for unsupported content type
     *
     * @expectedException \Mekras\OData\Client\Exception\UnsupportedException
     */
    public function testUnsupportedContentType()
    {
        $factory = new ParserFactory();
        $factory->getByContentType('foo/bar');
    }
}
