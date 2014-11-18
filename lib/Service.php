<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\Interfaces\Http\Client\HttpClientInterface;
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
     * Service base URL
     *
     * @var string
     */
    private $baseUrl;

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
     * @param string              $baseUrl    OData service base URL
     * @param HttpClientInterface $httpClient HTTP client to use
     * @param LoggerInterface     $logger     Logger (optional)
     *
     * @since x.xx
     */
    public function __construct(
        $baseUrl,
        HttpClientInterface $httpClient,
        LoggerInterface $logger = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->parsers = new ParserFactory();
        $this->mapper = new DefaultMapper();
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
        $result = $this->request('GET', $this->baseUrl . $uri);
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

        $array = $parser->parse($response->getBody()->getContents());

        return $array;//$this->mapper->map($array);
    }
}
