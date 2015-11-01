<p>Dear <?=$member["firstname"] ?>,

<p>We have been notified that a photo in your gallery has been deemed inappropriate.

<? if (isset($photo) && isset($photo['MemberPhoto']['title'])) { ?>
<p>The photo in question is entitled '<?= $photo['MemberPhoto']['title'] ?>'.
<? } ?>

<p>Enough viewers have flagged the photo, at which time it has been automatically deleted.

<p>Please review the <a href="<?=HTTPHOST?>/pages/terms">Terms of Service</a> to better understand our policy. Repeated violations of our terms of service due to inappropriate content will result in account suspension.

<p>Thank you,

<p>FirstFashionSite.com
