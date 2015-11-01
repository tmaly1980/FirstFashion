<p>Dear <?= $user['first'] ?>,

<p>This email is just to confirm that you received the following book today:

<?  echo $this->element("email_book_info", array('listings'=>$listings)); ?>

<p>
<a href="mailto:sales@betterthanthebookstore.com">If you have a moment, we would love to have your feedback so we can continue to make BTTB better</a>


<h5>This virtual receipt is emailed to you in place of a paper receipt in order to save paper.</h5>


