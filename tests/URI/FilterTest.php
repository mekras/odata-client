<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\URI;

use Mekras\OData\Client\URI\Filter as F;

/**
 * Tests for Mekras\OData\Client\URI\Options
 *
 * @covers Mekras\OData\Client\URI\Options
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testEq()
    {
        $filter = F::eq('Foo', F::str('Bar'));

        static::assertEquals("Foo eq 'Bar'", $filter);
    }

    /**
     *
     */
    public function testNeq()
    {
        $filter = F::neq('Foo', F::str('Bar'));

        static::assertEquals("Foo ne 'Bar'", $filter);
    }
}
