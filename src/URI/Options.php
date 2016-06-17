<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\URI;

/**
 * Query String Options.
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#QueryStringOptions
 */
class Options
{
    private $options = [];

    /**
     * Represent URI component as a string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        $query = '';
        if (count($this->options)) {
            $query = [];
            foreach ($this->options as $key => $value) {
                $query[] = '$' . $key . '=' . $value;
            }
            $query = '?' . implode('&', $query);
        }
        return $query;
    }

    /**
     * Select only $count first entries.
     *
     * @param int $count
     *
     * @return $this
     *
     * @since 1.0
     */
    public function top($count)
    {
        $this->options['top'] = (int) $count;

        return $this;
    }
}
