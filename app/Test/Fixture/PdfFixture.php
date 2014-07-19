<?php
/**
 * PdfFixture
 *
 */
class PdfFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'pdf';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'pdf_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'form_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'pdf_url' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created_at' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'pdf_id', 'unique' => 1),
			'form_id' => array('column' => 'form_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'pdf_id' => 1,
			'form_id' => 1,
			'pdf_url' => 'Lorem ipsum dolor sit amet',
			'created_at' => '2014-05-03 07:43:38'
		),
	);

}
