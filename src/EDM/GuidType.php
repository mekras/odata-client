<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

/**
 * Guid primitive
 *
 * @since 1.0
 *
 * @link  http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class GuidType extends Primitive
{
    /**
     * PCRE pattern for Edm.Guid
     */
    const PATTERN = '/^[\dA-F]{8}-[\dA-F]{4}-[\dA-F]{4}-[\dA-F]{4}-[\dA-F]{12}$/i';

    /**
     * Create value.
     *
     * @param string $value GUID
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        $value = (string) $value;
        if (!preg_match(self::PATTERN, $value)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid GUID', $value));
        }
        parent::__construct($value, 'Edm.Guid');
    }
}
