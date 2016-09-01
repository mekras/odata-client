<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

use Mekras\OData\Client\OData;

/**
 * OData Service error response.
 *
 * @since 0.3
 */
class ErrorDocument extends Document
{
    /**
     * Return error code.
     *
     * @return int
     *
     * @since 0.3
     */
    public function getCode()
    {
        $nodes = $this->getXPath()->query('m:code');

        return $nodes->length > 0 ? (int) $nodes->item(0)->textContent : 0;
    }

    /**
     * Return error message.
     *
     * @return string
     *
     * @since 0.3
     */
    public function getMessage()
    {
        $nodes = $this->getXPath()->query('m:message');

        return $nodes->length > 0 ? trim($nodes->item(0)->textContent) : '';
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
     * Return root node name here.
     *
     * @return string
     *
     * @since 0.3
     */
    protected function getRootNodeName()
    {
        return 'error';
    }
}
