<p>Dear <?=$member["firstname"] ?>,

<p>Welcome to FirstFashionSite.com!

<p>Your account has been created. To log in, use the following account information:

<p><b>Email:</b> <?= $member['email'] ?>
<p><b>Password:</b> <?= $member['password2'] ?>

<p>Log into our site at: <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/">http://<?= $_SERVER['HTTP_HOST'] ?></a>


<p>Thank you,

<p>FirstFashionSite.com
