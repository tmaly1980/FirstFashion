<?php 
/* SVN FILE: $Id$ */
/* MemberFriendsController Test cases generated on: 2008-08-28 14:08:29 : 1219947269*/
App::import('Controller', 'MemberFriends');

class TestMemberFriends extends MemberFriendsController {
	var $autoRender = false;
}

class MemberFriendsControllerTest extends CakeTestCase {
	var $MemberFriends = null;

	function setUp() {
		$this->MemberFriends = new TestMemberFriends();
	}

	function testMemberFriendsControllerInstance() {
		$this->assertTrue(is_a($this->MemberFriends, 'MemberFriendsController'));
	}

	function tearDown() {
		unset($this->MemberFriends);
	}
}
?>