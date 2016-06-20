<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Exception;

use Mekras\OData\Client\Exception\ErrorException;

/**
 * Tests for Mekras\OData\Client\Exception\ErrorException
 *
 * @covers Mekras\OData\Client\Exception\ErrorException
 */
class ErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testCreate()
    {
        $e = new ErrorException([]);
        static::assertEquals('(unknown).', $e->getMessage());
        static::assertEquals(0, $e->getCode());

        $e = new ErrorException(['message' => 123, 'code' => 456]);
        static::assertEquals('123.', $e->getMessage());
        static::assertEquals(456, $e->getCode());

        $e = new ErrorException(['message' => ['value' => 'Foo bar']]);
        static::assertEquals('Foo bar.', $e->getMessage());

        $e = new ErrorException(['message' => ['value' => 'Foo bar', 'code' => '']]);
        static::assertEquals(0, $e->getCode());
        static::assertEquals('Foo bar.', $e->getMessage());
    }
}
