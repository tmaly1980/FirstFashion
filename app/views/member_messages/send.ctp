<div id="member_messages_send">
<h2>Message Center - Send Message</h2>
<?


?>
<div class="folderlinks">
	<a href="/member_messages/inbox">Inbox</a> |
	<a href="/member_messages/sentbox">Sent Items</a>
</div>
	<form method="POST" action="/member_messages/send/<?= $member['Member']['member_id'] ?>/<?= $re_msg_id ?>">
		<label>Recipient:</label> <?= $member['Member']['firstname'] . ' ' . $member['Member']['lastname'] ?> (<?= $member['Member']['username'] ?>)<br/>
		<?= $form->input('MemberMessage.subject', array('label'=>'Subject:','value'=>$re_subject)); ?>
		<?= $form->input('MemberMessage.content', array('id'=>'message_content','rows'=>"15", 'cols'=>'70','label'=>'Message:')); ?>
		<input type=submit name="submit" value="Send">
	</form>
</div>
