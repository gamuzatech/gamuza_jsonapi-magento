<?php
/**
 * @package     Gamuza_JsonApi
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_JsonApi_lib_Zend_Json_Server_Error extends Zend_Json_Server_Error
{
    /**
     * Set error code
     *
     * @param  int $code
     * @return Zend_Json_Server_Error
     */
    public function setCode ($code)
    {
        if (!is_scalar ($code))
        {
            return $this;
        }

        $this->_code = intval ($code);

        return $this;
    }
}

