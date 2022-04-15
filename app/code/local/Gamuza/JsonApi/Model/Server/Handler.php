<?php
/*
 * Gamuza JSON API - Fast API for magento platform.
 * Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * Author: Eneias Ramos de Melo <eneias@gamuza.com.br>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Library General Public License for more details.
 *
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA.
 */

/*
 * See the AUTHORS file for a list of people on the Gamuza Team.
 * See the ChangeLog files for a list of changes.
 * These files are distributed with gamuza_jsonapi-magento at http://github.com/gamuzabrasil/.
 */

class Gamuza_JsonApi_Model_Server_Handler
extends Mage_Api_Model_Server_Handler
// extends Mage_Api_Model_Server_Handler_Abstract
{

protected function _getConfig()
{
    return Mage::getSingleton('jsonapi/config');
}

protected function _getServer()
{
    return Mage::getSingleton('jsonapi/server');
}

public function call($sessionId, $apiPath, $args = array())
{
    $this->_startSession($sessionId);

    if (!$this->_getSession()->isLoggedIn($sessionId))
    {
        return $this->_fault('session_expired');
    }

    $cached = Mage::getStoreConfig('api/json/cache_enabled');

    if ($cached)
    {
        $result = $this->_getConfig()->loadCacheResult ($this->_getCacheId($sessionId, $apiPath, $args));

        if (!empty ($result)) return unserialize ($result);
    }

    $result = parent::call ($sessionId, $apiPath, $args);

    if ($cached)
    {
        $this->_getConfig()->saveCacheResult (serialize ($result), $this->_getCacheId($sessionId, $apiPath, $args));
    }

    return $result;
}

public function multiCall($sessionId, array $calls = array(), $options = array())
{
    /*
    $this->_startSession($sessionId);

    if (!$this->_getSession()->isLoggedIn($sessionId))
    {
        return $this->_fault('session_expired');
    }
    */

    $result = array();

    foreach ($calls as $_call)
    {
        $apiPath = $_call [0];
        $args    =  !empty ($_call [1]) ? $_call [1] : array ();

        $result [] = $this->call ($sessionId, $apiPath, $args);
    }

    return $result;
}

protected function _getCacheId($sessionId, $apiPath, $args)
{
    $id = json_encode (array ($sessionId, $apiPath, $args));

    return $this->_getServer()->getApiName() . '.' . hash ('sha512', $id);
}

}

