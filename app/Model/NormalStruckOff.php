<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NormalStruckOff extends AppModel{
    public $primaryKey = 'document_id';
    public $useTable = "normal_struck_off";
	public $belongsTo = array(
		'Document' => array(
			'className' => 'Document',
			'foreignKey' => 'document_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
//                'Event' =>array(
//                    'className' => 'Event',
//			'foreignKey' => 'event_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
//                ),
	);
}