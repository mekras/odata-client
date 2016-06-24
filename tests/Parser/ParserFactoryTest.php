<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Parser;

use Mekras\OData\Client\Parser\AtomParser;
use Mekras\OData\Client\Parser\JsonParser;
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
        static::assertInstanceOf(JsonParser::class, $parser);
        static::assertSame($parser, $factory->getByContentType('application/json'));
    }

    /**
     * Get parser for "application/atom+xml"
     */
    public function testAtom()
    {
        $factory = new ParserFactory();
        $parser = $factory->getByContentType('application/atom+xml');
        static::assertInstanceOf(AtomParser::class, $parser);
        static::assertSame($parser, $factory->getByContentType('application/atom+xml'));
    }

    /**
     * ErrorException should be thrown for invalid content type
     *
     * @expectedException \Mekras\OData\Client\Exception\NotImplementedException
     */
    public function testInvalidContentType()
    {
        $factory = new ParserFactory();
        $factory->getByContentType('foo/bar');
    }
}
