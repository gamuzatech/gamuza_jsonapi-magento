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

require_once (Mage::getModuleDir('lib', 'Gamuza_JsonApi') . DS . 'lib' . DS . 'Zend' . DS . 'Json' . DS . 'Server.php');

class Gamuza_JsonApi_Model_Server_Adapter_Json
extends Varien_Object
implements Mage_Api_Model_Server_Adapter_Interface
{

protected $_json = null;

public function setHandler($handler)
{
    $this->setData('handler', $handler);

    return $this;
}

public function getHandler()
{
    return $this->getData('handler');
}

public function setController(Mage_Api_Controller_Action $controller)
{
     $this->setData('controller', $controller);

     return $this;
}

public function getController()
{
    $controller = $this->getData('controller');

    if (null === $controller)
    {
        $controller = new Varien_Object(
            array('request' => Mage::app()->getRequest(), 'response' => Mage::app()->getResponse())

        );
        $this->setData('controller', $controller);
    }

    return $controller;
}

public function run()
{
    Mage::app()->getTranslator()->init (Mage_Core_Model_App_Area::AREA_ADMINHTML, true);

    $apiConfigCharset = Mage::getStoreConfig("api/config/charset");

    $this->_json = new Gamuza_JsonApi_lib_Zend_Json_Server();
    $this->_json // ->setEncoding($apiConfigCharset)
        ->setClass($this->getHandler())
        ->setAutoEmitResponse(false)
        ->getRequest()->setId($this->getHelper()->getUniqId());
    $this->getController()->getResponse()
        ->clearHeaders()
        ->setHeader('Content-Type','application/json; charset=' . $apiConfigCharset)
        ->setBody($this->_json->handle());

    return $this;
}

public function map()
{
    $apiConfigCharset = Mage::getStoreConfig("api/config/charset");

    $this->_json = new Gamuza_JsonApi_lib_Zend_Json_Server();
    $this->_json // ->setEncoding($apiConfigCharset)
        ->setClass($this->getHandler());
    $this->getController()->getResponse()
        ->clearHeaders()
        ->setHeader('Content-Type', 'application/json; charset=' . $apiConfigCharset)
        ->setBody($this->_json->getServiceMap());

    return $this;
}

public function fault($code, $message)
{
    throw new Zend_Json_Server_Exception ($message, $code);
}

public function getHelper()
{
    return Mage::helper('jsonapi');
}

}

