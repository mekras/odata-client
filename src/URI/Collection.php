<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * Collection URI component.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/uri-conventions/
 */
class Collection extends UriComponent
{
    /**
     * Collection name.
     *
     * @var string
     */
    private $name;

    /**
     * Create Collection URI component.
     *
     * @param string $name Collection name.
     *                     
     * @since 1.0
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Represent URI component as a string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        return $this->name . parent::__toString();
    }

    /**
     * Set KeyPredicate component.
     *
     * @return KeyPredicate
     *
     * @since 1.0
     */
    public function keyPredicate()
    {
        $component = new KeyPredicate();
        $this->addComponent($component);

        return $component;
    }

    /**
     * Request should return the number of collection entries.
     *
     * Available since OData 2.0.
     *
     * @return void
     *
     * @since 1.0
     */
    public function count()
    {
        $this->addComponent('/$count');
    }
}
