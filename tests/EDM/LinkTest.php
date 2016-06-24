<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\Link;

/**
 * Tests for Mekras\OData\Client\EDM\Link
 *
 * @covers Mekras\OData\Client\EDM\Link
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new Link('Foo/Bar(123)');
        static::assertEquals('Foo/Bar(123)', $value);
    }
}
