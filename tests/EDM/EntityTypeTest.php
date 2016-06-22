<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\EDM\IntegerType;

/**
 * Tests for Mekras\OData\Client\EDM\EntityType
 *
 * @covers Mekras\OData\Client\EDM\EntityType
 */
class EntityTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test meta data read/write
     */
    public function testMetadata()
    {
        $entity = new EntityType(['Id' => new IntegerType(1)], ['foo' => 'bar']);

        static::assertEquals(['foo' => 'bar'], $entity->getMetadata());
        $entity->setMetadata(['bar' => 'baz']);
        static::assertEquals(['bar' => 'baz'], $entity->getMetadata());
    }
}
