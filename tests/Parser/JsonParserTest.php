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
use Mekras\OData\Client\EDM\FloatType;
use Mekras\OData\Client\EDM\GuidType;
use Mekras\OData\Client\EDM\IntegerType;
use Mekras\OData\Client\EDM\NullType;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\EDM\ServiceDocument;
use Mekras\OData\Client\EDM\StringType;
use Mekras\OData\Client\Parser\JsonParser;
use Mekras\OData\Client\Response\Error;
use Mekras\OData\Client\Response\Response;

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
        /** @var Error $response */
        $response = $parser->parse(file_get_contents(__DIR__ . '/fixtures/ServiceDocument.json'));
        static::assertInstanceOf(Response::class, $response);
        /** @var ServiceDocument $data */
        $data = $response->getData();
        static::assertInstanceOf(ServiceDocument::class, $data);
        static::assertEquals(['Products', 'Categories', 'Suppliers'], $data->getEntitySets());
    }

    /**
     * Entity set test.
     */
    public function testEntitySet()
    {
        $parser = new JsonParser();
        /** @var Error $response */
        $response = $parser->parse(file_get_contents(__DIR__ . '/fixtures/EntitySet.json'));
        static::assertInstanceOf(Response::class, $response);
        /** @var EntitySet $data */
        $data = $response->getData();
        static::assertInstanceOf(EntitySet::class, $data);
        static::assertCount(3, $data);
        /** @var EntityType|Primitive[] $item */
        $item = $data[0];
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
        /** @var Error $response */
        $response = $parser->parse(file_get_contents(__DIR__ . '/fixtures/Entity.json'));
        static::assertInstanceOf(Response::class, $response);
        /** @var EntityType|Primitive[] $data */
        $data = $response->getData();
        static::assertInstanceOf(EntityType::class, $data);
        static::assertArrayHasKey('ID', $data);
        static::assertEquals(0, $data['ID']->getValue());
    }

    /**
     * Properties test.
     */
    public function testProperties()
    {
        $parser = new JsonParser();
        /** @var Error $response */
        $response = $parser->parse(file_get_contents(__DIR__ . '/fixtures/Properties.json'));
        static::assertInstanceOf(Response::class, $response);
        /** @var ComplexType|Primitive[] $data */
        $data = $response->getData();
        static::assertInstanceOf(ComplexType::class, $data);

        static::assertArrayHasKey('Null', $data);
        static::assertInstanceOf(NullType::class, $data['Null']);
        static::assertNull($data['Null']->getValue());

        static::assertArrayHasKey('Binary', $data);
        // JsonParser can not detect Edm.Binary
        static::assertInstanceOf(StringType::class, $data['Binary']);
        static::assertEquals('NTc2ZDRhOThhMDAxZA==', $data['Binary']->getValue());

        static::assertArrayHasKey('Boolean', $data);
        static::assertInstanceOf(BooleanType::class, $data['Boolean']);
        static::assertTrue($data['Boolean']->getValue());

        static::assertArrayHasKey('Byte', $data);
        static::assertInstanceOf(IntegerType::class, $data['Byte']);
        static::assertEquals(255, $data['Byte']->getValue());
        static::assertEquals(IntegerType::BYTE, $data['Byte']->getType());

        static::assertArrayHasKey('DateTime', $data);
        static::assertInstanceOf(DateTimeType::class, $data['DateTime']);
        static::assertInstanceOf(\DateTimeInterface::class, $data['DateTime']->getValue());
        static::assertEquals(
            '2015-12-04 11:23:22.367000',
            $data['DateTime']->getValue()->format('Y-m-d H:i:s.u')
        );

        static::assertArrayHasKey('Decimal', $data);
        static::assertInstanceOf(FloatType::class, $data['Decimal']);
        static::assertEquals(2.345, $data['Decimal']->getValue());
        static::assertEquals(FloatType::DECIMAL, $data['Decimal']->getType());

        static::assertArrayHasKey('Double', $data);
        static::assertInstanceOf(FloatType::class, $data['Double']);
        static::assertEquals(2.345, $data['Decimal']->getValue());
        static::assertEquals(FloatType::DOUBLE, $data['Double']->getType());

        static::assertArrayHasKey('Guid', $data);
        static::assertInstanceOf(GuidType::class, $data['Guid']);
        static::assertEquals('12345678-aaaa-bbbb-cccc-ddddeeeeffff', $data['Guid']->getValue());

        static::assertArrayHasKey('Int16', $data);
        static::assertInstanceOf(IntegerType::class, $data['Int16']);
        static::assertEquals(-16, $data['Int16']->getValue());
        static::assertEquals(IntegerType::INT16, $data['Int16']->getType());

        static::assertArrayHasKey('Int32', $data);
        static::assertInstanceOf(IntegerType::class, $data['Int32']);
        static::assertEquals(-123456, $data['Int32']->getValue());
        static::assertEquals(IntegerType::INT32, $data['Int32']->getType());

        static::assertArrayHasKey('Int64', $data);
        static::assertInstanceOf(IntegerType::class, $data['Int64']);
        static::assertEquals(-64, $data['Int64']->getValue());
        static::assertEquals(IntegerType::INT64, $data['Int64']->getType());

        static::assertArrayHasKey('SByte', $data);
        static::assertInstanceOf(IntegerType::class, $data['SByte']);
        static::assertEquals(-8, $data['SByte']->getValue());
        static::assertEquals(IntegerType::SBYTE, $data['SByte']->getType());

        static::assertArrayHasKey('Single', $data);
        static::assertInstanceOf(FloatType::class, $data['Single']);
        static::assertEquals(2, $data['Single']->getValue());
        static::assertEquals(FloatType::SINGLE, $data['Single']->getType());
    }
}
