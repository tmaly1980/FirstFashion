<?php 
/* SVN FILE: $Id$ */
/* MemberPhotosController Test cases generated on: 2008-08-07 12:08:51 : 1218128031*/
App::import('Controller', 'MemberPhotos');

class TestMemberPhotos extends MemberPhotosController {
	var $autoRender = false;
}

class MemberPhotosControllerTest extends CakeTestCase {
	var $MemberPhotos = null;

	function setUp() {
		$this->MemberPhotos = new TestMemberPhotos();
	}

	function testMemberPhotosControllerInstance() {
		$this->assertTrue(is_a($this->MemberPhotos, 'MemberPhotosController'));
	}

	function tearDown() {
		unset($this->MemberPhotos);
	}
}
?>