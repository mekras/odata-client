<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\AtomPub\DocumentFactory as BaseDocumentFactory;

/**
 * XML to OData Document converter.
 */
class DocumentFactory extends BaseDocumentFactory
{
    /**
     * Create new factory.
     */
    public function __construct()
    {
        parent::__construct();
        $this->getExtensions()->register(new ODataExtension());
    }
}
