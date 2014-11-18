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
 * Тесты класса Mekras\OData\Client\Exception\ErrorException
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
        $e = ErrorException::createFromArray([]);
        $this->assertEquals('(unknown).', $e->getMessage());
        $this->assertEquals(0, $e->getCode());

        $e = ErrorException::createFromArray(['message' => 123, 'code' => 456]);
        $this->assertEquals('123.', $e->getMessage());
        $this->assertEquals(456, $e->getCode());

        $e = ErrorException::createFromArray(['message' => ['value' => 'Foo bar']]);
        $this->assertEquals('Foo bar.', $e->getMessage());

        $e = ErrorException::createFromArray(['message' => ['value' => 'Foo bar', 'code' => '']]);
        $this->assertEquals(0, $e->getCode());
        $this->assertEquals('Foo bar.', $e->getMessage());
    }
}
