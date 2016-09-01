<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Document;

/**
 * OData Metadata document.
 *
 * @since 0.3
 *
 * @see   http://www.odata.org/documentation/odata-version-2-0/overview/#ServiceMetadataDocument
 */
class MetadataDocument extends Document
{
    /**
     * Return node main namespace.
     *
     * @return string
     *
     * @since 0.3.2
     */
    public function ns()
    {
        return 'http://schemas.microsoft.com/ado/2007/06/edmx';
    }

    /**
     * Return root node name here.
     *
     * @return string
     *
     * @since 0.3.2
     */
    protected function getRootNodeName()
    {
        return 'Edmx';
    }
}
