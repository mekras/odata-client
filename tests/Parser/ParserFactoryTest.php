<?php
/**
 * OData client library
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
     *
     */
    public function testBasics()
    {
        $factory = new ParserFactory();
        $p = $factory->getParserFor('application/json');
        $this->assertInstanceOf('Mekras\OData\Client\Parser\JsonParser', $p);
        $this->assertSame($p, $factory->getParserFor('application/json'));
        $this->assertNull($factory->getParserFor('foo/bar'));
    }
}
