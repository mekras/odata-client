<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Mekras\OData\Client\Service;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests for Mekras\OData\Client\Service
 *
 * @covers Mekras\OData\Client\Service
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Should return array
     */
    public function testSendRequest()
    {
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response->expects(static::any())->method('getStatusCode')->willReturn(200);
        $response->expects(static::any())->method('getBody')->willReturn('{"foo":"bar"}');
        $response->expects(static::any())->method('getHeaderLine')->willReturnCallback(
            function ($header) {
                switch ($header) {
                    case 'Content-type':
                        return 'application/json';
                    case 'DataServiceVersion':
                        return '1.0';
                }
                return null;
            }
        );

        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $requestFactory = $this->getMockForAbstractClass(RequestFactory::class);
        $requestFactory->expects(static::once())->method('createRequest')
            ->with('GET', 'http://example.com/foo')->willReturn($request);
        /** @var RequestFactory $requestFactory */

        $httpClient = $this->getMockForAbstractClass(HttpClient::class);
        $httpClient->expects(static::once())->method('sendRequest')->with($request)
            ->willReturn($response);
        /** @var HttpClient $httpClient */

        $service = new Service('http://example.com', $httpClient, $requestFactory);
        $result = $service->sendRequest('GET', '/foo');
        static::assertEquals(['foo' => 'bar'], $result);
    }
}
