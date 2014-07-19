<?php
App::uses('Pdf', 'Model');

/**
 * Pdf Test Case
 *
 */
class PdfTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pdf',
		'app.form'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Pdf = ClassRegistry::init('Pdf');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Pdf);

		parent::tearDown();
	}

}
