<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * KeyPredicate URI component
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/uri-conventions/
 */
class KeyPredicate extends UriComponent
{
    /**
     * Key value
     *
     * @var array|string
     */
    private $keyValue = [];

    /**
     * KeyPredicate constructor.
     *
     * @param string|null $keyValue Set single key value.
     */
    public function __construct($keyValue = null)
    {
        $this->keyValue = (string) $keyValue;
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
        if (is_array($this->keyValue)) {
            $string = [];
            foreach ($this->keyValue as $key => $value) {
                $string[] = $key . '=' . $value;
            }
            $string = implode(',', $string);
        } else {
            $string = $this->keyValue;
        }

        return '(' . $string . ')' . parent::__toString();
    }

    /**
     * Add predicate key/value component
     *
     * @param string $property
     * @param string $value
     *
     * @return $this
     *
     * @since 1.0
     */
    public function add($property, $value)
    {
        if (!is_array($this->keyValue)) {
            $this->keyValue = [];
        }
        $this->keyValue[$property] = $value;

        return $this;
    }

    /**
     * Set property name.
     *
     * @param string $name
     *
     * @return Property
     *
     * @since 1.0
     */
    public function property($name)
    {
        $component = new Property($name);
        $this->addComponent($component);

        return $component;
    }
}
