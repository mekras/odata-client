<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\URI;

use Mekras\OData\Client\URI\UriComponent;

/**
 * Tests for Mekras\OData\Client\URI\UriComponent
 *
 * @covers Mekras\OData\Client\URI\UriComponent
 */
class UriComponentTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $addComponent = new \ReflectionMethod(UriComponent::class, 'addComponent');
        $addComponent->setAccessible(true);

        $component = $this->getMockForAbstractClass(UriComponent::class);
        $addComponent->invoke($component, '/foo');
        $addComponent->invoke($component, '/bar');

        static::assertEquals('/foo/bar', (string) $component);
    }
}
