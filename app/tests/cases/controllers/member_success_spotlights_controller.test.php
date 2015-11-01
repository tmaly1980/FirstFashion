<?php 
/* SVN FILE: $Id$ */
/* MemberSuccessSpotlightsController Test cases generated on: 2008-09-24 20:09:44 : 1222302524*/
App::import('Controller', 'MemberSuccessSpotlights');

class TestMemberSuccessSpotlights extends MemberSuccessSpotlightsController {
	var $autoRender = false;
}

class MemberSuccessSpotlightsControllerTest extends CakeTestCase {
	var $MemberSuccessSpotlights = null;

	function setUp() {
		$this->MemberSuccessSpotlights = new TestMemberSuccessSpotlights();
		$this->MemberSuccessSpotlights->constructClasses();
	}

	function testMemberSuccessSpotlightsControllerInstance() {
		$this->assertTrue(is_a($this->MemberSuccessSpotlights, 'MemberSuccessSpotlightsController'));
	}

	function tearDown() {
		unset($this->MemberSuccessSpotlights);
	}
}
?>