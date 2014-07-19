<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Secretary extends AppModel{
    public $useTable = 'secretaries';
    public $primaryKey = 'id';
   //public $primaryKey = array('document_id','director_id','created_at');
    public $belongsTo = array(
		'StakeHolder' => array(
			'className' => 'StakeHolder',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
        
    );
}