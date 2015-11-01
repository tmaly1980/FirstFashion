<?php 
/* SVN FILE: $Id$ */
/* BannedEmailsController Test cases generated on: 2008-09-24 15:09:07 : 1222285627*/
App::import('Controller', 'BannedEmails');

class TestBannedEmails extends BannedEmailsController {
	var $autoRender = false;
}

class BannedEmailsControllerTest extends CakeTestCase {
	var $BannedEmails = null;

	function setUp() {
		$this->BannedEmails = new TestBannedEmails();
		$this->BannedEmails->constructClasses();
	}

	function testBannedEmailsControllerInstance() {
		$this->assertTrue(is_a($this->BannedEmails, 'BannedEmailsController'));
	}

	function tearDown() {
		unset($this->BannedEmails);
	}
}
?>