<?php
class MemberDesignerProfile extends AppModel {

	var $name = 'MemberDesignerProfile';
	var $primaryKey = 'member_id';
	var $validate = array(
		'member_id' => array('numeric'),
		'about_me' => array('rule' => array('maxLength',255)),
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
