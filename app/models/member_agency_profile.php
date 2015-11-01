<?php
class MemberAgencyProfile extends AppModel {

	var $name = 'MemberAgencyProfile';
	var $primaryKey = 'member_id';
	var $validate = array(
		'member_id' => array('numeric'),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>
