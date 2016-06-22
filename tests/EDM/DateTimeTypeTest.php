<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\DateTimeType;

/**
 * Tests for Mekras\OData\Client\EDM\DateTimeType
 *
 * @covers Mekras\OData\Client\EDM\DateTimeType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class DateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new DateTimeType(new \DateTime('2016-06-01 01:02:03'));
        static::assertEquals('Edm.DateTime', $value->getType());
        static::assertEquals('01.06.16 01:02:03', $value->getValue()->format('d.m.y H:i:s'));
    }
}
