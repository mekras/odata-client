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
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#QueryStringOptions
 */
class Options
{
    /**
     * Ascending sorting
     */
    const ASC = 'asc';

    /**
     * Descending sorting
     */
    const DESC = 'desc';

    /**
     * Options
     *
     * @var array
     */
    private $options = [];

    /**
     * Represent URI component as a string
     *
     * @return string
     *
     * @since 0.3
     */
    public function __toString()
    {
        $query = '';
        if (count($this->options)) {
            $query = [];
            foreach ($this->options as $key => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $query[] = '$' . $key . '=' . $value;
            }
            $query = '?' . implode('&', $query);
        }

        return $query;
    }

    /**
     * Select only $count first entries.
     *
     * @param string $field     Field to sort by.
     * @param string $direction Sort direction.
     *
     * @return $this
     *
     * @since 0.3
     *
     * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#OrderBySystemQueryOption
     */
    public function orderBy($field, $direction = self::ASC)
    {
        if (!array_key_exists('orderby', $this->options)) {
            $this->options['orderby'] = [];
        }
        $this->options['orderby'][] = $field . ' ' . $direction;

        return $this;
    }

    /**
     * Select only $count first entries.
     *
     * @param int $count
     *
     * @return $this
     *
     * @since 0.3
     *
     * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#TopSystemQueryOption
     */
    public function top($count)
    {
        $this->options['top'] = (int) $count;

        return $this;
    }

    /**
     * Skip $count entries.
     *
     * @param int $count
     *
     * @return $this
     *
     * @since 0.3
     *
     * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#SkipSystemQueryOption
     */
    public function skip($count)
    {
        $this->options['skip'] = (int) $count;

        return $this;
    }

    /**
     * Filter entries.
     *
     * @param string $filter Use Filter class to construct filter.
     *
     * @return $this
     *
     * @since 0.3
     *
     * @see   http://www.odata.org/documentation/odata-version-2-0/uri-conventions/#FilterSystemQueryOption
     */
    public function filter($filter)
    {
        $this->options['filter'] = (string) $filter;

        return $this;
    }
}
