<?
class MemberMessage extends AppModel
{
	var $name = 'MemberMessage';
	var $primaryKey = 'msg_id';

	var $belongsTo = array(
			'Sender' => array('className' => 'Member',
								'foreignKey' => 'member_id_from',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Recipient' => array('className' => 'Member',
								'foreignKey' => 'member_id_to',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
}
?>
