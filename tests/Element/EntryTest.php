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
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Service;
use Mekras\OData\Client\Tests\TestCase;
use Mekras\OData\Client\URI\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests for Mekras\OData\Client\Element\Entry
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

    /**
     * Should be created valid "atom:link" element.
     */
    public function testAddRelationViaObject()
    {
        $resource = new Entry($this->createFakeNode());
        $resource->setEntityType('Foo');
        $resource->addLink('FooSet(123)', 'self');

        $entry = new Entry($this->createFakeNode());
        $entry->addRelation($resource);

        static::assertEquals(
            '<entry>' .
            '<link type="application/atom+xml;type=entry" ' .
            'href="FooSet(123)" ' .
            'rel="http://schemas.microsoft.com/ado/2007/08/dataservices/related/Foo" ' .
            'title="Foo"/>' .
            '</entry>',
            $this->getXML($entry)
        );
    }

    /**
     * Should be created valid "atom:link" element.
     */
    public function testAddRelationViaURI()
    {
        $uri = new Uri();
        $uri->collection('FooSet')->item(123);

        $entry = new Entry($this->createFakeNode());
        $entry->addRelation($uri, 'Foo');

        static::assertEquals(
            '<entry>' .
            '<link type="application/atom+xml;type=entry" ' .
            'href="FooSet(123)" ' .
            'rel="http://schemas.microsoft.com/ado/2007/08/dataservices/related/Foo" ' .
            'title="Foo"/>' .
            '</entry>',
            $this->getXML($entry)
        );
    }
}
