<?php
class MemberVideo extends AppModel {

	var $name = 'MemberVideo';
	var $primaryKey = 'member_video_id';

	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
}
