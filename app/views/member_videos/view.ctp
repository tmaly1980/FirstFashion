<div id="member_video_embed">
<?
	if(isset($_REQUEST['member_link']) && $_REQUEST['member_link'])
	{
	?>
		<div class='member_link'>
			<a target="_top" href="/members/view/<?= $member_id ?>">View Member &raquo;</a>
		</div>
		<div class="member_name">
			<?= $member['Member']['firstname'] ?> <?= $member['Member']['lastname'] ?>
		</div>
		<div class="clear"></div>
	<?
	}
	if (isset($flash_video_url)) {
?>
<object width="425" height="350">
	<param name="movie" value="<?= $flash_video_url ?>&autoplay=1"></param>
	<param name="allowFullScreen" value="true"></param>
	<embed src="<?= $flash_video_url ?>&autoplay=1&fs=1&rel=0" type="application/x-shockwave-flash" allowfullscreen="true" width="425" height="350"></embed>
</object>
</div>
<?
	}

?>
