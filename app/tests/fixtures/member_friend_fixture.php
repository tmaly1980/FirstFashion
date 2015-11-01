<?php 
/* SVN FILE: $Id$ */
/* MemberFriend Fixture generated on: 2008-08-28 14:08:42 : 1219946742*/

class MemberFriendFixture extends CakeTestFixture {
	var $name = 'MemberFriend';
	var $table = 'member_friends';
	var $fields = array(
			'friend_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'owner_member_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
			'friend_member_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
			'authorized' => array('type'=>'boolean', 'null' => true, 'default' => '1'),
			'indexes' => array('PRIMARY' => array('column' => 'friend_id', 'unique' => 1), 'owner_member_id' => array('column' => 'owner_member_id', 'unique' => 0), 'friend_member_id' => array('column' => 'friend_member_id', 'unique' => 0))
			);
	var $records = array(array(
			'friend_id'  => 1,
			'owner_member_id'  => 1,
			'friend_member_id'  => 1,
			'authorized'  => 1
			));
}
?>