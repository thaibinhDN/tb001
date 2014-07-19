<?php
App::uses('FormField', 'Model');

/**
 * FormField Test Case
 *
 */
class FormFieldTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.form_field',
		'app.form'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FormField = ClassRegistry::init('FormField');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FormField);

		parent::tearDown();
	}

}
