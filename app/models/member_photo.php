<?php
class MemberPhoto extends AppModel {

	var $name = 'MemberPhoto';
	var $primaryKey = 'photo_id';

	var $paginationLimit = null;

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	function getDirPath($size)
	{
		# Returns filesystem path to dir where picture stored....
	}

	function set_pagination_limit($limit = null)
	{
		$this->paginationLimit = $limit;
	}

	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$extra['limit'] = $this->paginationLimit;
		$limit = $this->paginationLimit;
		#$extra['limit'] = 1;
		$count = $this->findCount($conditions, $recursive, $extra);
		#error_log("LIMIT=$limit, COUNT=$count");
		return ($limit > 0 && $count > $limit) ? $limit : $count;
	}

}
?>
