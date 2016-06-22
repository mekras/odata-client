<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\FloatType;

/**
 * Tests for Mekras\OData\Client\EDM\FloatType
 *
 * @covers Mekras\OData\Client\EDM\FloatType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class FloatTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new FloatType(123.456);
        static::assertEquals('Edm.Decimal', $value->getType());
        static::assertEquals(123.456, $value->getValue());
        static::assertEquals('123.456', (string) $value);
    }
}
