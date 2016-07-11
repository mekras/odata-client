<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Element;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Mekras\AtomPub\Document\ServiceDocument;
use Mekras\OData\Client\Service;
use Mekras\OData\Client\Tests\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests for Mekras\OData\Client\Service
 *
 * @covers Mekras\OData\Client\Service
 */
class EntryTest extends TestCase
{
    /**
     * Service::sendRequest() should return ServiceDocument.
     */
    public function testGetServiceDocument()
    {
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response->expects(static::any())->method('getStatusCode')->willReturn(200);
        $response->expects(static::any())->method('getBody')
            ->willReturn($this->loadFixture('ServiceDocument.xml'));
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
        $document = $service->sendRequest('GET', '/foo');
        static::assertInstanceOf(ServiceDocument::class, $document);
    }
}
