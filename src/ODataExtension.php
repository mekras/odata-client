<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\Atom\Atom;
use Mekras\Atom\Document\Document;
use Mekras\Atom\Element\Element;
use Mekras\Atom\Extension\DocumentExtension;
use Mekras\Atom\Extension\ElementExtension;
use Mekras\Atom\Extension\NamespaceExtension;
use Mekras\Atom\Extensions;
use Mekras\Atom\Node;
use Mekras\OData\Client\Document\ErrorDocument;
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Element\Properties;

/**
 * OData extensions.
 */
class ODataExtension implements DocumentExtension, ElementExtension, NamespaceExtension
{
    /**
     * Create Atom document from XML DOM document.
     *
     * @param Extensions   $extensions Extension registry.
     * @param \DOMDocument $document   Source document.
     *
     * @return Document|null
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function parseDocument(Extensions $extensions, \DOMDocument $document)
    {
        if (OData::META === $document->documentElement->namespaceURI) {
            switch ($document->documentElement->localName) {
                case 'error':
                    return new ErrorDocument($extensions, $document);
            }
        }

        return null;
    }

    /**
     * Create new Atom document.
     *
     * @param Extensions $extensions Extension registry.
     * @param string     $name       Element name.
     *
     * @return Document|null
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function createDocument(Extensions $extensions, $name)
    {
        return null;
    }

    /**
     * Create Atom node from XML DOM element.
     *
     * @param Node        $parent  Parent node.
     * @param \DOMElement $element DOM element.
     *
     * @return Element|null
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function parseElement(Node $parent, \DOMElement $element)
    {
        if (Atom::NS === $element->namespaceURI) {
            switch ($element->localName) {
                case 'entry':
                    return new Entry($parent, $element);
            }
        } elseif (OData::META === $element->namespaceURI) {
            switch ($element->localName) {
                case 'properties':
                    return new Properties($parent, $element);
            }
        }

        return null;
    }

    /**
     * Create new Atom node.
     *
     * @param Node   $parent Parent node.
     * @param string $name   Element name.
     *
     * @return Element|null
     * @throws \InvalidArgumentException
     *
     * @since 1.0
     */
    public function createElement(Node $parent, $name)
    {
        switch ($name) {
            case 'atom:entry':
                return new Entry($parent);
            case 'm:properties':
                return new Properties($parent);
        }

        return null;
    }

    /**
     * Return additional XML namespaces.
     *
     * @return string[] prefix => namespace.
     *
     * @since 1.0
     */
    public function getNamespaces()
    {
        return [
            'm' => OData::META,
            'd' => OData::DATA
        ];
    }
}
