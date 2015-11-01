<?php 
/* SVN FILE: $Id$ */
/* MemberFriend Test cases generated on: 2008-08-28 14:08:42 : 1219946742*/
App::import('Model', 'MemberFriend');

class TestMemberFriend extends MemberFriend {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class MemberFriendTestCase extends CakeTestCase {
	var $MemberFriend = null;
	var $fixtures = array('app.member_friend', 'app.member', 'app.member', 'app.member');

	function start() {
		parent::start();
		$this->MemberFriend = new TestMemberFriend();
	}

	function testMemberFriendInstance() {
		$this->assertTrue(is_a($this->MemberFriend, 'MemberFriend'));
	}

	function testMemberFriendFind() {
		$results = $this->MemberFriend->recursive = -1;
		$results = $this->MemberFriend->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('MemberFriend' => array(
			'friend_id'  => 1,
			'owner_member_id'  => 1,
			'friend_member_id'  => 1,
			'authorized'  => 1
			));
		$this->assertEqual($results, $expected);
	}
}
?>