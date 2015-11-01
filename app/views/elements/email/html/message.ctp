<p>Dear <?=$member["firstname"] ?>,

<p>You have been sent a private message from <?= $sending_member['Member']['username'] ?>.

<p><a href="<?=HTTPHOST?>/member_messages/view/<?= $msg_id ?>">Click here</a> to view your message.

<p>To send this member a message, click on 'Send a Message' from their <a href="<?=HTTPHOST?>/members/view/<?= $sending_member['Member']['member_id'] ?>">Profile Page</a>.

<p>Thank you,

<p>FirstFashionSite.com
