<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\URI;

use Mekras\OData\Client\URI\Options;

/**
 * Tests for Mekras\OData\Client\URI\Options
 *
 * @covers Mekras\OData\Client\URI\Options
 */
class OptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testOrderBy()
    {
        $options = new Options();
        $options
            ->orderBy('Foo')
            ->orderBy('Bar', Options::DESC);

        static::assertEquals('?$orderby=Foo asc,Bar desc', (string) $options);
    }

    /**
     *
     */
    public function testTop()
    {
        $options = new Options();
        $options->top(5);

        static::assertEquals('?$top=5', (string) $options);
    }

    /**
     *
     */
    public function testSkip()
    {
        $options = new Options();
        $options->skip(5);

        static::assertEquals('?$skip=5', (string) $options);
    }

    /**
     *
     */
    public function testFilter()
    {
        $options = new Options();
        $options->filter("Name eq 'Foo'");

        static::assertEquals('?$filter=Name eq \'Foo\'', (string) $options);
    }

    /**
     *
     */
    public function testComplex()
    {
        $options = new Options();
        $options
            ->filter("Name eq 'Foo'")
            ->top(5)
            ->skip(10)
            ->orderBy('Foo', Options::DESC);

        static::assertEquals(
            '?$filter=Name eq \'Foo\'&$top=5&$skip=10&$orderby=Foo desc',
            (string) $options
        );
    }
}
