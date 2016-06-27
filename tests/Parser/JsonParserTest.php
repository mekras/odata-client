<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Parser;

use Mekras\OData\Client\EDM\BooleanType;
use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\EDM\DateTimeType;
use Mekras\OData\Client\EDM\EntitySet;
use Mekras\OData\Client\EDM\EntityType;
use Mekras\OData\Client\EDM\Error;
use Mekras\OData\Client\EDM\FloatType;
use Mekras\OData\Client\EDM\GuidType;
use Mekras\OData\Client\EDM\IntegerType;
use Mekras\OData\Client\EDM\NullType;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\EDM\ServiceDocument;
use Mekras\OData\Client\EDM\StringType;
use Mekras\OData\Client\Parser\JsonParser;

/**
 * Tests for Mekras\OData\Client\Parser\JsonParser
 *
 * @covers Mekras\OData\Client\Parser\JsonParser
 */
class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Error instance should be returned for error response.
     */
    public function testError()
    {
        $parser = new JsonParser();
        /** @var Error $response */
        $response = $parser->parse('{"error":{"message":{"value":"Foo"},"code":"123"}}');
        static::assertInstanceOf(Error::class, $response);
        static::assertEquals('Foo.', $response->getMessage());
        static::assertEquals(123, $response->getCode());
    }

    /**
     * @expectedException \Mekras\OData\Client\Exception\InvalidFormatException
     */
    public function testMalformedJson()
    {
        $parser = new JsonParser();
        $parser->parse('{"foo":bar"}');
    }

    /**
     * @expectedException \Mekras\OData\Client\Exception\InvalidFormatException
     */
    public function testNoData()
    {
        $parser = new JsonParser();
        $parser->parse('{"foo":"bar"}');
    }

    /**
     * ServiceDocument test.
     */
    public function testServiceDocument()
    {
        $parser = new JsonParser();
        /** @var ServiceDocument $result */
        $result = $parser->parse(file_get_contents(__DIR__ . '/fixtures/ServiceDocument.json'));
        static::assertInstanceOf(ServiceDocument::class, $result);
        static::assertEquals(['Products', 'Categories', 'Suppliers'], $result->getEntitySets());
    }

    /**
     * Entity set test.
     */
    public function testEntitySet()
    {
        $parser = new JsonParser();
        /** @var EntitySet $result */
        $result = $parser->parse(file_get_contents(__DIR__ . '/fixtures/EntitySet.json'));
        static::assertInstanceOf(EntitySet::class, $result);
        static::assertCount(3, $result);
        /** @var EntityType|Primitive[] $item */
        $item = $result[0];
        static::assertInstanceOf(EntityType::class, $item);
        static::assertArrayHasKey('ID', $item);
        static::assertEquals(1, $item['ID']->getValue());
    }

    /**
     * Entity test.
     */
    public function testEntity()
    {
        $parser = new JsonParser();
        /** @var EntityType|Primitive[] $result */
        $result = $parser->parse(file_get_contents(__DIR__ . '/fixtures/Entity.json'));
        static::assertInstanceOf(EntityType::class, $result);
        static::assertArrayHasKey('ID', $result);
        static::assertEquals(0, $result['ID']->getValue());
    }

    /**
     * Properties test.
     */
    public function testProperties()
    {
        $parser = new JsonParser();
        /** @var ComplexType|Primitive[] $result */
        $result = $parser->parse(file_get_contents(__DIR__ . '/fixtures/Properties.json'));
        static::assertInstanceOf(ComplexType::class, $result);

        static::assertArrayHasKey('Null', $result);
        static::assertInstanceOf(NullType::class, $result['Null']);
        static::assertNull($result['Null']->getValue());

        static::assertArrayHasKey('Binary', $result);
        // JsonParser can not detect Edm.Binary
        static::assertInstanceOf(StringType::class, $result['Binary']);
        static::assertEquals('NTc2ZDRhOThhMDAxZA==', $result['Binary']->getValue());

        static::assertArrayHasKey('Boolean', $result);
        static::assertInstanceOf(BooleanType::class, $result['Boolean']);
        static::assertTrue($result['Boolean']->getValue());

        static::assertArrayHasKey('Byte', $result);
        static::assertInstanceOf(IntegerType::class, $result['Byte']);
        static::assertEquals(255, $result['Byte']->getValue());
        static::assertEquals(IntegerType::BYTE, $result['Byte']->getType());

        static::assertArrayHasKey('DateTime', $result);
        static::assertInstanceOf(DateTimeType::class, $result['DateTime']);
        static::assertInstanceOf(\DateTimeInterface::class, $result['DateTime']->getValue());
        static::assertEquals(
            '2015-12-04 11:23:22.367000',
            $result['DateTime']->getValue()->format('Y-m-d H:i:s.u')
        );

        static::assertArrayHasKey('Decimal', $result);
        static::assertInstanceOf(FloatType::class, $result['Decimal']);
        static::assertEquals(2.345, $result['Decimal']->getValue());
        static::assertEquals(FloatType::DECIMAL, $result['Decimal']->getType());

        static::assertArrayHasKey('Double', $result);
        static::assertInstanceOf(FloatType::class, $result['Double']);
        static::assertEquals(2.345, $result['Decimal']->getValue());
        static::assertEquals(FloatType::DOUBLE, $result['Double']->getType());

        static::assertArrayHasKey('Guid', $result);
        static::assertInstanceOf(GuidType::class, $result['Guid']);
        static::assertEquals('12345678-aaaa-bbbb-cccc-ddddeeeeffff', $result['Guid']->getValue());

        static::assertArrayHasKey('Int16', $result);
        static::assertInstanceOf(IntegerType::class, $result['Int16']);
        static::assertEquals(-16, $result['Int16']->getValue());
        static::assertEquals(IntegerType::INT16, $result['Int16']->getType());

        static::assertArrayHasKey('Int32', $result);
        static::assertInstanceOf(IntegerType::class, $result['Int32']);
        static::assertEquals(-123456, $result['Int32']->getValue());
        static::assertEquals(IntegerType::INT32, $result['Int32']->getType());

        static::assertArrayHasKey('Int64', $result);
        static::assertInstanceOf(IntegerType::class, $result['Int64']);
        static::assertEquals(-64, $result['Int64']->getValue());
        static::assertEquals(IntegerType::INT64, $result['Int64']->getType());

        static::assertArrayHasKey('SByte', $result);
        static::assertInstanceOf(IntegerType::class, $result['SByte']);
        static::assertEquals(-8, $result['SByte']->getValue());
        static::assertEquals(IntegerType::SBYTE, $result['SByte']->getType());

        static::assertArrayHasKey('Single', $result);
        static::assertInstanceOf(FloatType::class, $result['Single']);
        static::assertEquals(2, $result['Single']->getValue());
        static::assertEquals(FloatType::SINGLE, $result['Single']->getType());
    }
}
