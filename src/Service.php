<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Http\Client\Exception as HttpClientException;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Mekras\Atom\Document\Document;
use Mekras\Atom\Document\EntryDocument;
use Mekras\OData\Client\Document\ErrorDocument;
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Exception\ClientErrorException;
use Mekras\OData\Client\Exception\RuntimeException;
use Mekras\OData\Client\Exception\ServerErrorException;
use Psr\Http\Message\ResponseInterface;

/**
 * OData Service.
 *
 * A low-level interface to OData Service. Encapsulates all HTTP operations.
 *
 * @api
 */
class Service
{
    /**
     * Service root URI.
     *
     * @var string
     */
    private $serviceRootUri;

    /**
     * Клиент HTTP.
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * The HTTP request factory.
     *
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * OData Document factory.
     *
     * @var DocumentFactory
     */
    private $documentFactory;

    /**
     * Creates new OData service proxy.
     *
     * @param string         $serviceRootUri OData service root URI.
     * @param HttpClient     $httpClient     HTTP client to use.
     * @param RequestFactory $requestFactory The HTTP request factory.
     *
     * @since 1.0
     *
     * @link  http://www.odata.org/documentation/odata-version-2-0/uri-conventions#ServiceRootUri
     */
    public function __construct(
        $serviceRootUri,
        HttpClient $httpClient,
        RequestFactory $requestFactory
    ) {
        $this->serviceRootUri = rtrim($serviceRootUri, '/');
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->documentFactory = new DocumentFactory();
    }

    /**
     * Perform actual HTTP request to service
     *
     * @param string   $method   HTTP method.
     * @param string   $uri      URI.
     * @param Document $document Document to send to the server.
     *
     * @return Document
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\ClientErrorException
     * @throws \Mekras\OData\Client\Exception\RuntimeException
     * @throws \Mekras\OData\Client\Exception\ServerErrorException
     */
    public function sendRequest($method, $uri, Document $document = null)
    {
        $headers = [
            'DataServiceVersion' => '1.0',
            'MaxDataServiceVersion' => '1.0',
            'Accept' => 'application/atom+xml,application/atomsvc+xml,application/xml'
        ];

        if ($document) {
            $headers['Content-type'] = 'application/atom+xml';
        }

        $uri = str_replace($this->getServiceRootUri(), '', $uri);
        $uri = '/' . ltrim($uri, '/');

        $request = $this->requestFactory->createRequest(
            $method,
            $this->getServiceRootUri() . $uri,
            $headers,
            $document ? $document->getDomDocument()->saveXML() : null
        );

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $version = $response->getHeaderLine('DataServiceVersion');
        if ('' === $version) {
            throw new ServerErrorException('DataServiceVersion header missed');
        }

        $doc = $this->documentFactory->parseXML((string) $response->getBody());
        $this->checkResponseForErrors($response, $doc);

        return $doc;
    }

    /**
     * Create new entity object.
     *
     * Created document should be POSTed via {@link sendRequest()}.
     *
     * @param string $type Entity type.
     *
     * @return EntryDocument
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\Atom\Exception\MalformedNodeException
     *
     * @since 1.0
     */
    public function createEntityDocument($type)
    {
        /** @var EntryDocument $document */
        $document = $this->documentFactory->createDocument('atom:entry');

        /** @var Entry $entry */
        $entry = $document->getEntry();
        $entry->setEntityType($type);
        $entry->addAuthor('');
        $entry->addContent('');
        $entry->getProperties(); // Create properties node.

        return $document;
    }

    /**
     * Return Service root URI.
     *
     * @return string
     *
     * @link http://www.odata.org/documentation/odata-version-2-0/uri-conventions#ServiceRootUri
     */
    public function getServiceRootUri()
    {
        return $this->serviceRootUri;
    }

    /**
     * Throw exception if server reports error
     *
     * @param ResponseInterface $response
     * @param Document          $document
     *
     * @throws ServerErrorException
     * @throws ClientErrorException
     */
    private function checkResponseForErrors(ResponseInterface $response, Document $document)
    {
        if ($document instanceof ErrorDocument) {
            $message = $document->getMessage();
            $code = $document->getCode();
        } else {
            $message = $response->getReasonPhrase();
            $code = $response->getStatusCode();
        }
        switch (floor($response->getStatusCode() / 100)) {
            case 4:
                throw new ClientErrorException($message, $code);
            case 5:
                throw new ServerErrorException($message, $code);
        }

        if ($document instanceof ErrorDocument) {
            throw new ServerErrorException($message, $code);
        }
    }
}
