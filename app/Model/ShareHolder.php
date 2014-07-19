<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ShareHolder extends AppModel{
    public $primaryKey = 'id';
    public $useTable = 'shareholders';
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
