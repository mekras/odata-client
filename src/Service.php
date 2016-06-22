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
use Mekras\OData\Client\Exception\ClientErrorException;
use Mekras\OData\Client\Exception\ErrorException;
use Mekras\OData\Client\Exception\LogicException;
use Mekras\OData\Client\Exception\RuntimeException;
use Mekras\OData\Client\Exception\ServerErrorException;
use Mekras\OData\Client\Parser\ParserFactory;
use Mekras\OData\Client\Response\Error;
use Mekras\OData\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * OData Service.
 *
 * A low-level interface to OData Service. Encapsulates all HTTP operations.
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
     * The response parser factory.
     *
     * @var ParserFactory
     */
    private $parserFactory;

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
        $this->parserFactory = new ParserFactory();
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
     * Perform actual HTTP request to service
     *
     * @param string $method  HTTP method.
     * @param string $uri     URI.
     * @param string $content Request body contents.
     *
     * @return Response
     *
     * @throws \Mekras\OData\Client\Exception\ErrorException
     * @throws \Mekras\OData\Client\Exception\InvalidDataException
     * @throws \Mekras\OData\Client\Exception\InvalidFormatException
     * @throws \Mekras\OData\Client\Exception\LogicException
     * @throws \Mekras\OData\Client\Exception\NotImplementedException
     * @throws \Mekras\OData\Client\Exception\RuntimeException
     * @throws \Mekras\OData\Client\Exception\ServerErrorException
     */
    public function sendRequest($method, $uri, $content = null)
    {
        $headers = [
            'DataServiceVersion' => '1.0',
            'MaxDataServiceVersion' => '1.0',
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $uri = str_replace($this->getServiceRootUri(), '', $uri);
        $uri = '/' . ltrim($uri, '/');

        $request = $this->requestFactory
            ->createRequest($method, $this->getServiceRootUri() . $uri, $headers, $content);

        try {
            $httpResponse = $this->httpClient->sendRequest($request);
        } catch (HttpClientException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            throw new LogicException($e->getMessage(), 0, $e);
        }

        $version = $httpResponse->getHeaderLine('DataServiceVersion');
        if ('' === $version) {
            throw new ServerErrorException('DataServiceVersion header not missed');
        }

        $contentType = $httpResponse->getHeaderLine('Content-type');
        $parts = explode(';', $contentType);
        $contentType = reset($parts);

        $parser = $this->parserFactory->getByContentType($contentType);

        $response = $parser->parse((string) $httpResponse->getBody());

        $this->checkForErrorResponse($httpResponse, $response);

        return $response;
    }

    /**
     * Throw exception if server reports error
     *
     * @param ResponseInterface $httpResponse
     * @param Response          $response
     *
     * @throws ErrorException
     */
    private function checkForErrorResponse(ResponseInterface $httpResponse, Response $response)
    {
        if ($response instanceof Error) {
            $message = $response->getMessage();
            $code = $response->getCode();
        } else {
            $message = $httpResponse->getReasonPhrase();
            $code = $httpResponse->getStatusCode();
        }
        switch (floor($httpResponse->getStatusCode() / 100)) {
            case 4:
                throw new ClientErrorException($message, $code);
            case 5:
                throw new ServerErrorException($message, $code);
        }

        if ($response instanceof Error) {
            throw new ErrorException($message, $code);
        }
    }
}
