<?php
/*
 * Gamuza JSON API - Fast API for magento platform.
 * Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
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

class Gamuza_JsonApi_Model_Adminhtml_System_Config_Source_Resource_List
{
    public function toArray ()
    {
        $result = array ();

        foreach (Mage::getSingleton('api/config')->getResources() as $resourceId => $resource)
        {
            $resourceModule = $resource->getAttribute('module');

            foreach ($resource->methods as $methods)
            {
                foreach ($methods as $methodId => $method)
                {
                    $methodModule = $method->getAttribute('module');

                    if (!empty ($methodModule))
                    {
                        $resourceModule = $methodModule;
                    }

                    $methodName = sprintf ('%s.%s', $resourceId, $methodId);
                    $methodTitle = Mage::helper ($resourceModule)->__(strval ($method->title));

                    $result [$methodName] = $methodTitle;
                }
            }
        }

        return $result;
    }
    
    public function toOptionArray ()
    {
        $result = array ();
        
        foreach ($this->toArray () as $id => $value)
        {
            $result [] = array ('value' => $id, 'label' => sprintf ('%s ( %s )', $id, $value));
        }
        
        return $result;
    }
}
