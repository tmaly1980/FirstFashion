<div id="video">
	<? 
	if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
		echo "<div class='editlink'>[<a href='/member_videos/editlist'>Edit Video</a>]</div>";
	}
	?>
<h2>My YouTube Video</h2>

<? if (isset($video_thumbnail_url) && $video_thumbnail_url != '') { ?>
	<a rel="shadowbox;width=450;height=390;player=iframe" href="/member_videos/view/<?= $member_id ?>">
	<img width="320" height="240" src="<?= $video_thumbnail_url ?>" />
	<img style="display: block; margin: 0px; padding: 0px;" src="/img/video_controls.gif"/>
	</a>
	<!--
	<a class="flaglink" href="/member_videos/flag/<?=$video['MemberVideo']['member_video_id']?>">Flag</a>
	-->
<? } else { ?>
	<img src="/img/no_video.gif" />
<? } ?>

</div>
