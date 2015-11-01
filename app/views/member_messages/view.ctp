<div id="member_messages_view">
<h2>View Message</h2>
<?


?>
	<div>
		<label>From: </label> <?=
			$html->link($message['Sender']['firstname'] . " " . $message['Sender']['lastname'],
				"/members/view/".$message['Sender']['member_id']);
			?>
	</div>
	<div>
		<label>Subject: </label> <?= $message['MemberMessage']['subject'] ?>
	</div>
	<div>
		<label>Sent: </label> <?= $time->nice($message['MemberMessage']['sent_time']) ?>
	</div>
	<hr/>
	<div>
		<label>Message: </label> 
		<? 
			$content = preg_replace("/\n/", "<br/>\n", $message['MemberMessage']['content']); 
			echo $content;
		?>
	</div>
	<div class="replylink">
		<a href="/member_messages/send/<?= $message['Sender']['member_id'] ?>/<?= $message['MemberMessage']['msg_id'] ?>">Reply</a>
	</div>

	<div class="inboxlink">
		<a href="/member_messages/index">Back to Inbox</a>
	</div>

</div>
