<p>Dear <?=$member["firstname"] ?> <?=$member["lastname"] ?>,

<p>As per your request, your password has been reset.

<p>Email: <?=$member["email"] ?><br/>
New Password: <?=$new_password ?>

<p>Please click on the following url to login:

<p><a href="<?=HTTPHOST?>/members/login"><?=HTTPHOST?>/members/login</a>

<p>Thanks,<br/>
The FirstFashionSite.com Team!
