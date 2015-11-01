<?php 
/* SVN FILE: $Id$ */
/* BannedEmail Test cases generated on: 2008-09-24 15:09:24 : 1222285584*/
App::import('Model', 'BannedEmail');

class TestBannedEmail extends BannedEmail {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class BannedEmailTestCase extends CakeTestCase {
	var $BannedEmail = null;
	var $fixtures = array('app.banned_email');

	function start() {
		parent::start();
		$this->BannedEmail = new TestBannedEmail();
	}

	function testBannedEmailInstance() {
		$this->assertTrue(is_a($this->BannedEmail, 'BannedEmail'));
	}

	function testBannedEmailFind() {
		$this->BannedEmail->recursive = -1;
		$results = $this->BannedEmail->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('BannedEmail' => array(
			'banned_email_id'  => 1,
			'email'  => 'Lorem ipsum dolor sit amet'
			));
		$this->assertEqual($results, $expected);
	}
}
?>