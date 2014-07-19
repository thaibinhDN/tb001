<?php
App::uses('AppModel', 'Model');
/**
 * Director Model
 *
 * @property Company $Company
 */
class Director extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'id';
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
