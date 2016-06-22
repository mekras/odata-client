<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

use Mekras\OData\Client\Exception\NotImplementedException;
use Mekras\OData\Client\Exception\ServerErrorException;

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
     * @throws \Mekras\OData\Client\Exception\ServerErrorException If invalid content type given
     *
     * @since 1.0
     */
    public function getByContentType($contentType)
    {
        if (!array_key_exists($contentType, $this->parsers)) {
            switch ($contentType) {
                case 'application/atom+xml':
                    throw new NotImplementedException('Atom Format not supported');

                case 'application/json':
                    $this->parsers[$contentType] = new JsonParser();
                    break;

                default:
                    throw new ServerErrorException(
                        sprintf('Invalid response Content-type: "%s"', $contentType)
                    );
            }
        }

        return $this->parsers[$contentType];
    }
}
