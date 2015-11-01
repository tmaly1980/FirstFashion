<?
if(isset($re_msg_id) && $re_msg_id != '')
{
	?>
	<a href="/member_messages/view/<?=$re_msg_id?>">Go back to message</a>
	<?
} else if (isset($member_id) && $member_id != '') {
	?>
	<a href="/members/view/<?=$member_id?>">Go back to member's profile</a>
	<?
} else {
	?>
	<a href="/members/view">Go back to your member profile</a>
	<?
}
?>
