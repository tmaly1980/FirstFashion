<p>Dear <?=$member["firstname"] ?>,

<p>We have been notified that your video has been deemed inappropriate.

<? if (isset($video) && isset($video['MemberVideo']['title'])) { ?>
<p>The video in question is entitled '<?= $video['MemberVideo']['title'] ?>'.
<? } ?>

<p>Enough viewers have flagged the video, at which time it has been automatically deleted.

<p>Please review the <a href="<?=HTTPHOST?>/pages/terms">Terms of Service</a> to better understand our policy. Repeated violations of our terms of service due to inappropriate content will result in account suspension.

<p>Thank you,

<p>FirstFashionSite.com
