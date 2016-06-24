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
     * Create object from string
     *
     * @param string $value
     *
     * @return DateTimeType
     *
     * @since 1.0
     */
    public static function createFromString($value)
    {
        if (preg_match(self::PATTERN, $value, $matches)) {
            $ticks = (int) $matches[1];
            $seconds = floor($ticks / 1000);
            $ms = $ticks - ($seconds * 1000);
            if (count($matches) === 3) {
                $seconds += ($matches[2] * 60);
            }
            $time = new \DateTime('@' . $seconds);
            if ($ms > 0) {
                $time = new \DateTime($time->format('Y-m-dTH:i:s.') . $ms);
            }
        } else {
            $time = new \DateTime($value, new \DateTimeZone('+00:00'));
        }
        return new static($time);
    }

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
