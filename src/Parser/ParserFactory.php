<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\Exception\UnsupportedException;

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
     * @throws \Mekras\OData\Client\Exception\UnsupportedException If $contentType not supported
     *
     * @since 1.0
     */
    public function getByContentType($contentType)
    {
        if (!array_key_exists($contentType, $this->parsers)) {
            switch ($contentType) {
                case 'application/json':
                    $this->parsers[$contentType] = new JsonParser();
                    break;

                default:
                    throw new UnsupportedException(
                        'Unsupported response Content-type: ' . $contentType
                    );
            }
        }

        return $this->parsers[$contentType];
    }
}
