<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\StringType;

/**
 * Tests for Mekras\OData\Client\EDM\StringType
 *
 * @covers Mekras\OData\Client\EDM\StringType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class StringTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new StringType('Foo');
        static::assertEquals('Edm.String', $value->getType());
        static::assertEquals('Foo', $value->getValue());
    }
}
