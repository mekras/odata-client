<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * DateTime primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class DateTimeType extends Primitive
{
    /**
     * PCRE pattern for Edm.DateTime
     */
    const PATTERN = '#^/Date\((\d+)([+-]\d+)?\)/$#';

    /**
     * Create value.
     *
     * @param \DateTimeInterface $value DateTime value.
     */
    public function __construct(\DateTimeInterface $value)
    {
        parent::__construct($value, 'Edm.DateTime');
    }
}
