<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * OData URI building helper.
 *
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/
 */
class Uri extends UriComponent
{
    /**
     * Query String Options.
     *
     * @var Options
     */
    private $options;

    /**
     * Create new URI.
     *
     * @since 0.3
     */
    public function __construct()
    {
        $this->options = new Options();
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
        return parent::__toString() . $this->options;
    }

    /**
     * Set collection name.
     *
     * @param string $name
     *
     * @return Collection
     *
     * @since 0.3
     */
    public function collection($name)
    {
        $component = new Collection($name);
        $this->addComponent($component);

        return $component;
    }

    /**
     * Query String Options.
     *
     * @return Options
     *
     * @since 0.3
     */
    public function options()
    {
        return $this->options;
    }
}
