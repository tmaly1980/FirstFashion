<div id="member_messages_list">
<h2>Message Center - Inbox</h2>

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
	<th><?php echo $paginator->sort('From', 'Sender.firstname, Sender.lastname');?></th>
	<th><?php echo $paginator->sort('Subject', 'MemberMessage.subject');?></th>
	<th><?php echo $paginator->sort('Sent','MemberMessage.sent_time');?></th>
</tr>
<?php

$i = 0;
$message_rows = array();

foreach ($messages as $message):
	$isnew = $message['MemberMessage']['is_read'] ? "read" : "unread";
	$message_rows[] = 
		array(
			array(
				#$form->select('message_id',array('multiple'=>'checkbox', 'value'=>$message['MemberSentMessage']['msg_id'], 'class'=>"member_sent_message_msg_id")),
				$form->checkbox("MemberMessage.msg_id.".$message['MemberMessage']['msg_id'],array('class'=>"member_message_msg_id")),
				array('class'=>"message_checkbox $isnew"),
			),
			array(
				$html->link( $message['Sender']['firstname'] . " " . $message['Sender']['lastname'],
					"/member_messages/view/".$message['MemberMessage']['msg_id']),
				array('class'=>"sender $isnew"),
			),
			array(
				$html->link( $message['MemberMessage']['subject'],
					"/member_messages/view/".$message['MemberMessage']['msg_id']),
				array('class'=>"subject $isnew"),
			),
			array(
				$time->niceShort($message['MemberMessage']['sent_time'], $time->serverOffset()/60/60),
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
