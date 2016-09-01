<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * Property URI component.
 *
 * @since 0.3
 *
 * @see  http://www.odata.org/documentation/odata-version-2-0/uri-conventions/
 */
class Property extends UriComponent
{
    /**
     * Property name
     *
     * @var string
     */
    private $name;

    /**
     * Create Property URI component.
     *
     * @param string $name Property name.
     *
     * @since 0.3
     */
    public function __construct($name)
    {
        $this->name = (string) $name;
    }

    /**
     * Represent URI component as a string
     *
     * @return string
     *
     * @since 0.3
     */
    public function __toString()
    {
        return '/' . $this->name . parent::__toString();
    }

    /**
     * Request should return plain value.
     *
     * Available since OData 2.0.
     *
     * @return void
     *
     * @since 0.3
     */
    public function value()
    {
        $this->addComponent('/$value');
    }
}
