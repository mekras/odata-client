<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\Interfaces\Http\Client\HttpClientInterface;
use Mekras\Interfaces\Http\Message\ResponseInterface;
use Mekras\OData\Client\Exception\ClientErrorException;
use Mekras\OData\Client\Exception\ErrorException;
use Mekras\OData\Client\Exception\ServerErrorException;
use Mekras\OData\Client\Mapper\DefaultMapper;
use Mekras\OData\Client\Mapper\ObjectMapperInterface;
use Mekras\OData\Client\Object\ODataObject;
use Mekras\OData\Client\Parser\ParserFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * OData Service
 */
class Service
{
    /**
     * The latest supported OData version
     *
     * @since x.xx
     */
    const MAX_VERSION = '2.0';

    /**
     * Service root URI
     *
     * @var string
     */
    private $serviceRootUri;

    /**
     * Клиент HTTP
     *
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * Response parser factory
     *
     * @var ParserFactory
     */
    private $parsers;

    /**
     * Object mapper
     *
     * @var ObjectMapperInterface
     */
    private $mapper;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Creates new OData service proxy
     *
     * @param string $serviceRootUri          OData service root URI
     * @param HttpClientInterface $httpClient HTTP client to use
     * @param LoggerInterface $logger         Logger (optional)
     *
     * @since x.xx
     *
     * @link  http://www.odata.org/documentation/odata-version-2-0/uri-conventions#ServiceRootUri
     */
    public function __construct(
        $serviceRootUri,
        HttpClientInterface $httpClient,
        LoggerInterface $logger = null
    ) {
        $this->serviceRootUri = rtrim($serviceRootUri, '/');
        $this->httpClient = $httpClient;
        $this->parsers = new ParserFactory();
        //$this->mapper = new DefaultMapper();
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Retrieve collection or entry
     *
     * @param string $uri
     *
     * @return mixed
     *
     * @since x.xx
     */
    public function retrieve($uri)
    {
        $result = $this->request('GET', $this->serviceRootUri . $uri);
        return $result;
    }

    /*public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }*/

    /**
     * Perform actual HTTP request to service
     *
     * @param string $method HTTP method
     * @param string $url    URL
     *
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws ErrorException
     *
     * @return array TODO ODataObject
     */
    private function request($method, $url)
    {
        $headers = [
            'DataServiceVersion' => '1.0',
            'MaxDataServiceVersion' => self::MAX_VERSION,
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ];
        switch (strtoupper($method)) {
            case 'GET':
                $this->logger->debug(sprintf('Requesting "%s"', $url));
                $response = $this->httpClient->get($url, $headers);
                break;
            default:
                throw new \LogicException(sprintf('Unsupported method "%s"', $method));
        }
        $this->logger->debug(
            sprintf(
                'Server response: [%d] %s',
                $response->getStatusCode(),
                $response->getBody()->getContents(1024)
            )
        );

        $contentType = $response->getHeader('Content-type');
        $parser = $this->parsers->getParserFor($contentType);
        if (is_null($parser)) {
            throw new \RuntimeException(sprintf('Unsupported content type "%s"', $contentType));
        }

        $rawData = $parser->parse($response->getBody()->getContents());

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
            throw $this->createErrorException($response, $rawData);
        }

        return $rawData;//$this->mapper->map($array);
    }

    /**
     * Creates error exception from response and parsed raw data
     *
     * @param ResponseInterface $response
     * @param array             $rawData
     *
     * @return ErrorException
     */
    private function createErrorException(ResponseInterface $response, array $rawData)
    {
        if ($response->getStatusCode() < 500) {
            $exception = ClientErrorException::createFromArray(
                $rawData,
                $response->getStatusCode()
            );
        } else {
            $exception = ServerErrorException::createFromArray(
                $rawData,
                $response->getStatusCode()
            );
        }
        return $exception;
    }
}
