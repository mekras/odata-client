<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\EDM;

use Mekras\Atom\Node;
use Mekras\OData\Client\Element\Element;
use Mekras\OData\Client\OData;

/**
 * Primitive
 *
 * @since 1.0
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel
 */
class Primitive extends Element
{
    const BINARY = 'Edm.Binary';
    const BOOLEAN = 'Edm.Boolean';
    const BYTE = 'Edm.Byte';
    const DATETIME = 'Edm.DateTime';
    const DECIMAL = 'Edm.Decimal';
    const DOUBLE = 'Edm.Double';
    const GUID = 'Edm.Guid';
    const INT16 = 'Edm.Int16';
    const INT32 = 'Edm.Int32';
    const INT64 = 'Edm.Int64';
    const SBYTE = 'Edm.SByte';
    const SINGLE = 'Edm.Single';
    const STRING = 'Edm.String';

    /**
     * Node name.
     *
     * @var string
     */
    private $nodeName;

    /**
     * Create Primitive.
     *
     * @param Node               $parent  Parent node.
     * @param \DOMElement|string $element DOM element or node name.
     * @param string|null        $type    Primitive type if $element is a string.
     *
     * @throws \InvalidArgumentException If $element has invalid namespace.
     *
     * @since 1.0
     */
    public function __construct(Node $parent, $element, $type = null)
    {
        if ($element instanceof \DOMElement) {
            $this->nodeName = $element->localName;
            parent::__construct($parent, $element);
        } else {
            $this->nodeName = (string) $element;
            parent::__construct($parent);
            if ($type) {
                // Prefix "m" registered — no exception
                $this->setAttribute('m:type', $type);
            }
        }
    }

    /**
     * Represent object as a string
     *
     * @return string
     *
     * @since 1.0
     */
    public function __toString()
    {
        try {
            $value = $this->getValue();
        } catch (\InvalidArgumentException $e) {
            return '(' . $e->getMessage() . ')';
        }
        if (!is_scalar($value)) {
            return '(Can not convert ' . gettype($value) . ' to string)';
        }

        return (string) $value;
    }

    /**
     * Return value name
     *
     * @return string
     *
     * @since 1.0
     */
    public function getName()
    {
        return $this->getDomElement()->localName;
    }

    /**
     * Return value type
     *
     * @return string
     *
     * @since 1.0
     */
    public function getType()
    {
        // Prefix "m" registered — no exception
        return $this->getAttribute('m:type');
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @since 1.0
     */
    public function setType($type)
    {
        // Prefix "m" registered — no exception
        $this->setAttribute('m:type', $type);
    }

    /**
     * Return value
     *
     * @return mixed
     *
     * @since 1.0
     */
    public function getValue()
    {
        return $this->getCachedProperty(
            'value',
            function () {
                // Prefix "m" registered — no exception
                if ($this->getAttribute('m:null') === 'true') {
                    return null;
                }
                $value = trim($this->getDomElement()->textContent);
                switch ($this->getType()) {
                    case self::BINARY:
                        $value = base64_decode($value);
                        break;
                    case self::BOOLEAN:
                        $value = 'true' === $value;
                        break;
                    case self::BYTE:
                    case self::INT16:
                    case self::INT32:
                    case self::INT64:
                    case self::SBYTE:
                        $value = (int) $value;
                        break;
                    case self::DATETIME:
                        if (preg_match('#^/Date\((\d+)([+-]\d+)?\)/$#', $value, $matches)) {
                            $ticks = (int) $matches[1];
                            $seconds = floor($ticks / 1000);
                            $ms = $ticks - ($seconds * 1000);
                            if (count($matches) === 3) {
                                $seconds += ($matches[2] * 60);
                            }
                            $value = new \DateTime('@' . $seconds);
                            if ($ms > 0) {
                                $value = new \DateTime($value->format('Y-m-dTH:i:s.') . $ms);
                            }
                        } else {
                            $value = new \DateTime($value, new \DateTimeZone('+00:00'));
                        }
                        break;
                    case self::DECIMAL:
                    case self::DOUBLE:
                    case self::SINGLE:
                        $value = (float) $value;
                        break;
                }

                return $value;
            }
        );
    }

    /**
     * Set new value.
     *
     * @param mixed $value
     *
     * @throws \InvalidArgumentException If the $value type does not match type set via setType()
     *
     * @since 1.0
     */
    public function setValue($value)
    {
        $element = $this->getDomElement();
        if (null === $value) {
            // Prefix "m" registered — no exception
            $this->setAttribute('m:null', 'true');
            $element->nodeValue = '';
        }
        switch ($this->getType()) {
            case self::BINARY:
                $element->nodeValue = base64_encode($value);
                break;
            case self::BOOLEAN:
                $element->nodeValue = $value ? 'true' : 'false';
                break;
            case self::DATETIME:
                if (!$value instanceof \DateTimeInterface) {
                    throw new \InvalidArgumentException('Value should implement DateTimeInterface');
                }
                $element->nodeValue = $value->format(\DateTime::RFC3339);
                break;
            default:
                $element->nodeValue = $value;
        }

        $this->setCachedProperty('value', $value);
    }

    /**
     * Return node main namespace.
     *
     * @return string
     *
     * @since 1.0
     */
    public function ns()
    {
        return OData::DATA;
    }

    /**
     * Return node name.
     *
     * @return string
     *
     * @since 1.0
     */
    protected function getNodeName()
    {
        return $this->nodeName;
    }
}
