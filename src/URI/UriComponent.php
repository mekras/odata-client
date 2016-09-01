<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * OData URI component.
 *
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/
 */
abstract class UriComponent
{
    /**
     * Child components
     *
     * @var UriComponent[]
     */
    private $children = [];

    /**
     * Represent URI component as a string
     *
     * @return string
     *
     * @since 0.3
     */
    public function __toString()
    {
        $string = '';
        foreach ($this->children as $child) {
            $string .= (string) $child;
        }

        return $string;
    }

    /**
     * Add new URI component
     *
     * @param UriComponent|string $component
     *
     * @since 0.3
     */
    protected function addComponent($component)
    {
        $this->children[] = $component;
    }
}
