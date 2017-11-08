<?php
/**
 * @package     Gamuza_JsonApi
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('lib', 'Gamuza_JsonApi') . DS . 'lib' . DS . 'Zend' . DS . 'Json' . DS . 'Server' . DS . 'Error.php');

class Gamuza_JsonApi_lib_Zend_Json_Server extends Zend_Json_Server
{
    /**
     * Flag: whether or not to auto-emit the response
     * @var bool
     */
    protected $_autoEmitResponse = false;

    /**
     * Indicate fault response
     *
     * @param  string $fault
     * @param  int $code
     * @return false
     */
    public function fault ($fault = null, $code = 404, $data = null)
    {
        $error = new Gamuza_JsonApi_lib_Zend_Json_Server_Error ($fault, $code, $data);

        $this->getResponse ()->setError ($error);

        return $error;
    }
}

