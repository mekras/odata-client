<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests;

use Mekras\OData\Client\Service;

/**
 * Tests for Mekras\OData\Client\Service
 *
 * @covers Mekras\OData\Client\Service
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testRetrieve()
    {
        $body = $this
            ->getMockForAbstractClass('Mekras\Interfaces\Http\Message\StreamableInterface');
        $body->expects($this->any())->method('getContents')->willReturn('{"foo":"bar"}');

        $response = $this
            ->getMockForAbstractClass('Mekras\Interfaces\Http\Message\ResponseInterface');
        $response->expects($this->any())->method('getStatusCode')->willReturn(200);
        $response->expects($this->any())->method('getBody')->willReturn($body);
        $response->expects($this->any())->method('getHeader')->willReturn('application/json');

        $http = $this->getMockForAbstractClass('Mekras\Interfaces\Http\Client\HttpClientInterface');
        $http->expects($this->once())->method('get')->with('http://example.com/foo')
            ->willReturn($response);
        /** @var \Mekras\Interfaces\Http\Client\HttpClientInterface $http */

        $service = new Service('http://example.com', $http);
        $result = $service->retrieve('/foo');
        $this->assertEquals(['foo' => 'bar'], $result);
    }
}
