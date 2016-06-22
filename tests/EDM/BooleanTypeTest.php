<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\BooleanType;

/**
 * Tests for Mekras\OData\Client\EDM\BooleanType
 *
 * @covers Mekras\OData\Client\EDM\BooleanType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new BooleanType(true);
        static::assertEquals('Edm.Boolean', $value->getType());
        static::assertEquals(true, $value->getValue());
    }
}
