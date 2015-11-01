<p>Dear <?= $user['first'] ?>,

<p>This email is just to confirm that we received the following book from you today:

<?  echo $this->element("email_book_info", array('listings'=>$listings)); ?>

<p>
Keep an eye out for an email from PayPal with your payment within the next 3 days.

<a href="http://<?= $_SERVER['HTTP_HOST'] ?>/pages/payment">Payment FAQ</a>



<h5>This virtual receipt is emailed to you in place of a paper receipt in order to save paper.</h5>


