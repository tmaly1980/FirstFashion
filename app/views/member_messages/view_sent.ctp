<div id="member_messages_view">
<h2>View Message</h2>
<?


?>
	<div>
		<label>To: </label> <?=
			$html->link($message['Recipient']['firstname'] . " " . $message['Recipient']['lastname'],
				"/members/view/".$message['Recipient']['member_id']);
			?>
	</div>
	<div>
		<label>Subject: </label> <?= $message['MemberSentMessage']['subject'] ?>
	</div>
	<div>
		<label>Sent: </label> <?= $time->nice($message['MemberSentMessage']['sent_time']) ?>
	</div>
	<hr/>
	<div>
		<label>Message: </label> 
		<? 
			$content = preg_replace("/\n/", "<br/>\n", $message['MemberSentMessage']['content']); 
			echo $content;
		?>
	</div>

	<div class="inboxlink">
		<a href="/member_messages/sentbox">Back to Sent Items</a>
	</div>

</div>
