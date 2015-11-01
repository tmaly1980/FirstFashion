<?php
class MemberFriend extends AppModel {

	var $name = 'MemberFriend';
	var $primaryKey = 'friend_id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Owner' => array('className' => 'Member',
								'foreignKey' => 'owner_member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Friend' => array('className' => 'Member',
								'foreignKey' => 'friend_member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>
