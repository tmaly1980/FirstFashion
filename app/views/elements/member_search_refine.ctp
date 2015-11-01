<table cellpadding="0" cellspacing="0">
<?
	echo $html->tableCells(array(
		array(
			$form->input('Member.member_type', array('options'=>$member_types,'label'=>'Member Type:','onChange'=>'refineMemberSearch(this)','default'=>$default_member_type)),
			$form->input('Member.firstname', array('label'=>'First Name:')),
			$form->input('Member.lastname', array('label'=>'Last Name:')),
		),
		array(
			$form->input('Member.username', array('label'=>'Username:')),
			$form->input('Member.city', array('label'=>'City:')),
			$form->input('Member.state', array('options'=>$states, 'label'=>'State:')),
		),

	));
?>
</table>
