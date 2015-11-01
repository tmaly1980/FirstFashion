<?php
class MemberFeaturedModel extends AppModel {

	var $name = 'MemberFeaturedModel';
	var $primaryKey = 'featured_model_id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
	);

}
?>
