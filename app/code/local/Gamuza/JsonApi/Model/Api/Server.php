<?php
/**
 * @package     Gamuza_JsonApi
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Webservice api abstract
 */
class Gamuza_JsonApi_Model_Api_Server extends Mage_Api_Model_Server
{
    public function __construct ()
    {
        // parent::__construct ();

        if (Mage::getStoreConfigFlag (Gamuza_JsonApi_Helper_Data::XML_PATH_API_BASIC_AUTH_ACTIVE))
        {
            if (!isset ($_SERVER ['PHP_AUTH_USER']) || !isset ($_SERVER ['PHP_AUTH_PW']))
            {
                header ('WWW-Authenticate: Basic realm="Authentication Required"');
                header ('HTTP/1.0 401 Unauthorized');

                die (__('Unauthorized'));
            }

            $authUser = $_SERVER ['PHP_AUTH_USER'];
            $authPass = $_SERVER ['PHP_AUTH_PW'];

            /*
            $username = Mage::getStoreConfig (Gamuza_JsonApi_Helper_Data::XML_PATH_API_BASIC_AUTH_USERNAME);
            $password = Mage::getStoreConfig (Gamuza_JsonApi_Helper_Data::XML_PATH_API_BASIC_AUTH_PASSWORD);
            */

            $resource = Mage::getSingleton ('core/resource');
            $read  = $resource->getConnection ('core_read');
            $table = $resource->getTableName ('core/config_data');

            $username = $read->fetchOne(
                " SELECT value FROM {$table} WHERE scope = 'default' AND scope_id = 0 AND path = ? ",
                Gamuza_JsonApi_Helper_Data::XML_PATH_API_BASIC_AUTH_USERNAME
            );

            $password = $read->fetchOne(
                " SELECT value FROM {$table} WHERE scope = 'default' AND scope_id = 0 AND path = ? ",
                Gamuza_JsonApi_Helper_Data::XML_PATH_API_BASIC_AUTH_PASSWORD
            );

            if (strcmp ($authUser, $username) != 0 || strcmp ($authPass, $password) != 0)
            {
                header ('HTTP/1.0 403 Forbidden');

                die (__('Forbidden'));
            }
        }
    }
}

