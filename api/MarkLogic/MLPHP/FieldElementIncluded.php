<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
namespace MarkLogic\MLPHP;

/**
 * Represents a database field element to include.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class FieldElementIncluded
{
    public $properties = array(); // @var array

    /**
     * Create a FieldElementIncluded object.
     *
     * @param array $properties Associative array of config properties.
     */
    public function __construct($properties)
    {
        $this->properties = array_merge(array(
            'localname' => '',
            'attribute-localname' => '',
            'attribute-value' => '',
            'namespace-uri' => '',
            'attribute-namespace-uri' => '',
            'weight' => 1.0
        ), $properties);
    }

}
