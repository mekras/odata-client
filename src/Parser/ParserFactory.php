<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\Exception\NotImplementedException;

/**
 * Parser factory.
 *
 * @since 1.0
 */
class ParserFactory
{
    /**
     * Response parsers
     *
     * @var ResponseParser[]
     */
    private $parsers = [];

    /**
     * Returns response parser for the given content type
     *
     * @param string $contentType
     *
     * @return ResponseParser
     *
     * @throws \Mekras\OData\Client\Exception\NotImplementedException
     *
     * @since 1.0
     */
    public function getByContentType($contentType)
    {
        if (!array_key_exists($contentType, $this->parsers)) {
            switch ($contentType) {
                case 'application/xml':
                case 'application/atom+xml':
                case 'application/atom xml': // TODO Убрать после https://github.com/php-http/message/pull/42
                case 'application/atomsvc+xml':
                    $this->parsers[$contentType] = new AtomParser();
                    break;

                case 'application/json':
                    $this->parsers[$contentType] = new JsonParser();
                    break;

                default:
                    throw new NotImplementedException(
                        sprintf('Invalid response Content-type: "%s"', $contentType)
                    );
            }
        }

        return $this->parsers[$contentType];
    }
}
