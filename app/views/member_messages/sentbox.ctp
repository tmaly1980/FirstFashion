<div id="member_messages_list">
<h2>Message Center - Sent Items</h2>

<div class="folderlinks">
	<a href="/member_messages/inbox">Inbox</a> |
	<a href="/member_messages/sentbox">Sent Items</a>
</div>

<? if (count($messages) > 0) { ?>

<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing messages %start% - %end%', true)
));

?></p>
<br/>
<form method="POST">
<table cellpadding="0" cellspacing="0" id="messages_list">
<tr class="header">
	<th>
		<input type="checkbox" onClick="toggleMasterCheckbox(this, 'member_sent_message_msg_id');">
	</th>
	<th><?php echo $paginator->sort('To', 'Receiver.firstname, Receiver.lastname');?></th>
	<th><?php echo $paginator->sort('Subject', 'MemberSentMessage.subject');?></th>
	<th><?php echo $paginator->sort('Sent','MemberSentMessage.sent_time');?></th>
</tr>
<?php

$i = 0;
$message_rows = array();

foreach ($messages as $message):
	$isnew = $message['MemberSentMessage']['is_read'] ? "read" : "unread";
	$message_rows[] = 
		array(
			array(
				$form->checkbox("MemberSentMessage.msg_id.".$message['MemberSentMessage']['msg_id'],array('class'=>"member_sent_message_msg_id")),
				array('class'=>"message_checkbox $isnew"),
			),
			array(
				$html->link( $message['Recipient']['firstname'] . " " . $message['Recipient']['lastname'],
					"/member_messages/view_sent/".$message['MemberSentMessage']['msg_id']),
				array('class'=>"sender $isnew"),
			),
			array(
				$html->link( $message['MemberSentMessage']['subject'],
					"/member_messages/view_sent/".$message['MemberSentMessage']['msg_id']),
				array('class'=>"subject $isnew"),
			),
			array(
				$time->niceShort($message['MemberSentMessage']['sent_time'], $time->serverOffset()/60/60),
				array('class'=>"sent_time $isnew"),
			),
		);
	$i++;
	?>

<?php endforeach; 
	echo $html->tableCells($message_rows, null, array('class'=>'altrow'));
	echo "<tr><td colspan='4'>";
	#echo $form->input('delete',array('type'=>'submit','value'=>"Delete Selected Messages",'onClick'=>"Are you sure you want to delete the selected messages? They cannot be recovered."));
	echo $form->submit('Delete Selected Messages',array('onClick'=>"return confirm('Are you sure you want to delete the selected messages? They cannot be recovered.')",'name'=>'data[action]'));
	echo "</td></tr>";
?>
</table>
</form>

<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>

<? 
} else { # NO messages....
	echo "<p><b>No messages</b></p>";
}

?>

</div>
