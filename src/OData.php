<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client;

use Mekras\AtomPub\AtomPub;

/**
 * XML to OData Document converter.
 */
class OData extends AtomPub
{
    const DATA = 'http://schemas.microsoft.com/ado/2007/08/dataservices';
    const META = 'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata';

    /**
     * OData constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerDocumentType(new ODataDocuments());
    }
}
