<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Document extends AppModel {
    public $useTable = 'documents';
    public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
                'FunctionCorp' => array(
			'className' => 'FunctionCorp',
			'foreignKey' => 'function_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}     

