<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Element;

use Mekras\Atom\Element\Content;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\Exception\LogicException;
use Mekras\OData\Client\OData;

/**
 * OData Entry properties.
 *
 * @since 0.3
 */
class Properties extends Element implements \Iterator
{
    /**
     * Property cache.
     *
     * @var Primitive[]
     */
    private $properties = [];

    /**
     * Create node.
     *
     * @param  Content  $parent  Parent node.
     * @param \DOMElement|null $element DOM element.
     *
     * @since 0.3
     *
     * @throws \InvalidArgumentException If $element has invalid namespace.
     */
    public function __construct(Content $parent, $element = null)
    {
        parent::__construct($parent, $element);

        if (null === $element) {
            // No prefix — no exception
            $parent->setAttribute('type', 'application/xml');
        }

        /** @var \DOMNodeList $nodes */
        // No REQUIRED — no exception
        $nodes = $this->query('d:*');
        foreach ($nodes as $node) {
            $primitive = new Primitive($this, $node);
            $this->properties[$primitive->getName()] = $primitive;
        }
    }

    /**
     * Add property.
     *
     * @param string $name  Property name.
     * @param mixed  $value Property value.
     * @param string $type  Primitive type (default is Primitive::STRING).
     *
     * @return Primitive
     *
     * @throws \InvalidArgumentException
     * @throws \Mekras\OData\Client\Exception\LogicException If property already exist.
     *
     * @since 0.3
     */
    public function add($name, $value, $type = Primitive::STRING)
    {
        if ($this->has($name)) {
            throw new LogicException(sprintf('Property "%s" already exist', $name));
        }

        $primitive = new Primitive($this, $name, $type);
        $primitive->setValue($value);
        $this->properties[$name] = $primitive;
    }

    /**
     * Return property.
     *
     * @param string $name
     *
     * @return Primitive
     *
     * @since 0.3
     */
    public function get($name)
    {
        return $this->properties[$name];
    }

    /**
     * Return true if property exists.
     *
     * @param string $name
     *
     * @return bool
     *
     * @since 0.3
     */
    public function has($name)
    {
        return array_key_exists($name, $this->properties);
    }

    /**
     * Return node main namespace.
     *
     * @return string
     *
     * @since 0.3
     */
    public function ns()
    {
        return OData::META;
    }

    /**
     * Return the current element
     *
     * @return Primitive|false
     */
    public function current()
    {
        return current($this->properties);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        next($this->properties);
    }

    /**
     * Return the key of the current element
     *
     * @return string string on success, or null on failure.
     */
    public function key()
    {
        $property = $this->current();

        return $property ? $property->getName() : null;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return (bool) $this->current();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->properties);
    }

    /**
     * Return node name here.
     *
     * @return string
     *
     * @since 0.3
     */
    protected function getNodeName()
    {
        return 'properties';
    }
}
