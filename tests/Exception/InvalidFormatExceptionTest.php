<?php
/**
 * Tests for Mekras\OData\Client\Exception\InvalidFormatException
 */
namespace Mekras\OData\Client\Tests\Exception;

use Mekras\OData\Client\Exception\InvalidFormatException;

/**
 * Tests for Mekras\OData\Client\Exception\InvalidFormatException
 */
class InvalidFormatExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Mekras\OData\Client\Exception\InvalidFormatException::create
     */
    public function testCreate()
    {
        $e = InvalidFormatException::create('JSON');
        $this->assertEquals('Invalid JSON.', $e->getMessage());

        $data = str_repeat('0', 100);

        $e = InvalidFormatException::create('JSON', $data);
        $this->assertEquals(
            'Invalid JSON. Unexpected data in ' .
            '"0000000000000000000000000000000000000000000000000000000000000000…"',
            $e->getMessage()
        );
    }

    /**
     * @covers Mekras\OData\Client\Exception\InvalidFormatException::create
     */
    public function testCreateWithMessage()
    {
        $e = InvalidFormatException::create('JSON', null, 'Syntax error');
        $this->assertEquals(
            'Invalid JSON. Syntax error',
            $e->getMessage()
        );

        $e = InvalidFormatException::create('JSON', 'foo', 'Syntax error');
        $this->assertEquals(
            'Invalid JSON. Syntax error in "foo"',
            $e->getMessage()
        );
    }
}
