<?php
App::uses('AppModel', 'Model');
/**
 * Director Model
 *
 * @property Company $Company
 */
class StakeHolder extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */     public $useTable = "stakeholders";
	public $primaryKey = 'id';
	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
