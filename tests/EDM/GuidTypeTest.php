<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\GuidType;

/**
 * Tests for Mekras\OData\Client\EDM\GuidType
 *
 * @covers Mekras\OData\Client\EDM\GuidType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class GuidTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic tests
     */
    public function testBasics()
    {
        $value = new GuidType('1f13d502-079a-4d65-9ca4-1c5798504475');
        static::assertEquals('Edm.Guid', $value->getType());
        static::assertEquals('1f13d502-079a-4d65-9ca4-1c5798504475', $value->getValue());
        static::assertEquals('1f13d502-079a-4d65-9ca4-1c5798504475', (string) $value);
    }

    /**
     * Test not a GUID value
     *
     * @expectedException \InvalidArgumentException
     */
    public function testNotAGuid()
    {
        new GuidType('1f13d502-079a-4d65-9ca4-1c579850447');
    }
}
