<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\EntitySet;
use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\EDM\IntegerType;
use Mekras\OData\Client\EDM\NullType;

/**
 * Tests for Mekras\OData\Client\EDM\EntitySet
 *
 * @covers Mekras\OData\Client\EDM\EntitySet
 */
class EntitySetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test \ArrayAccess implementation.
     */
    public function testArrayAccess()
    {
        $value = new EntitySet(
            [
                new EntityType(['Id' => new IntegerType(1)]),
                new EntityType(['Id' => new IntegerType(2)])
            ]
        );

        static::assertArrayHasKey(0, $value);
        static::assertArrayHasKey(1, $value);
        static::assertArrayNotHasKey(2, $value);
        unset($value[1]);
        static::assertArrayNotHasKey(1, $value);
        static::assertEquals('1', (string) $value[0]['Id']);
        $value[1] = new EntityType(['Id' => new IntegerType(2)]);
        static::assertArrayHasKey(1, $value);
        static::assertEquals('2', (string) $value[1]['Id']);
    }

    /**
     * Only instances of EntityType can be added to set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testAddNotEntityType()
    {
        $value = new EntitySet();
        $value[0] = new NullType();
    }

    /**
     * Test \Iterator implementation.
     */
    public function testIterator()
    {
        $value = new EntitySet(
            [
                new EntityType(['Id' => new IntegerType(1)]),
                new EntityType(['Id' => new IntegerType(2)])
            ]
        );

        foreach ($value as $k => $v) {
            static::assertEquals($k + 1, (string) $v['Id']);
        }
    }

    /**
     * Test \Countable implementation.
     */
    public function testCountable()
    {
        $value = new EntitySet(
            [
                new EntityType(['Id' => new IntegerType(1)]),
                new EntityType(['Id' => new IntegerType(2)])
            ]
        );

        static::assertCount(2, $value);
    }
}
