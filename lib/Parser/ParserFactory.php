<?php
/**
 * Parser factory
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Parser;

/**
 * Parser factory
 *
 * @since x.xx
 */
class ParserFactory
{
    /**
     * Response parsers
     *
     * @var ResponseParserInterface[]
     * @since x.xx
     */
    private $parsers = [];

    /**
     * Returns response parser
     *
     * @param string $contentType
     *
     * @return ResponseParserInterface|null
     *
     * @since x.xx
     */
    public function getParserFor($contentType)
    {
        if (!array_key_exists($contentType, $this->parsers))
        {
            switch ($contentType)
            {
                case 'application/json':
                    $this->parsers[$contentType] = new JsonParser();
                    break;
                default:
                    $this->parsers[$contentType] = null;
            }
        }
        return $this->parsers[$contentType];
    }
}
