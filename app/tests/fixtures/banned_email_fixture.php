<?php 
/* SVN FILE: $Id$ */
/* BannedEmail Fixture generated on: 2008-09-24 15:09:24 : 1222285584*/

class BannedEmailFixture extends CakeTestFixture {
	var $name = 'BannedEmail';
	var $table = 'banned_emails';
	var $fields = array(
			'banned_email_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'email' => array('type'=>'string', 'null' => false, 'key' => 'index'),
			'indexes' => array('PRIMARY' => array('column' => 'banned_email_id', 'unique' => 1), 'email' => array('column' => 'email', 'unique' => 0))
			);
	var $records = array(array(
			'banned_email_id'  => 1,
			'email'  => 'Lorem ipsum dolor sit amet'
			));
}
?>