<div class="member_video_editlist">
<legend><h2>Edit YouTube Video</h2></legend>
<fieldset>
	<form method="post" action="/member_videos/editlist" enctype="multipart/form-data">

	<table class="video_editlist" width="100%" border=0 cellspacing=0>
	<tr>
		<td colspan=3 class="ralign">
			<a href="/member_videos/add">Add New Video</a>
		</td>
	</tr>
	<? $i = 0; foreach($membervideos as $membervideo) { ?>
	<tr class="bg_<?= $i++ % 2 ? 'even' : 'odd' ?>">
		<td width="150">
			<a rel="shadowbox;width=435;height=360;player=iframe" href="/member_videos/view_by_ytid/<?= $membervideo['MemberVideo']['youtube_video_id'] ?>">
				<?= $membervideo['MemberVideo']['title']; ?><br/>
				<img width="130" height="97" src="http://i.ytimg.com/vi/<?= $membervideo['MemberVideo']['youtube_video_id'] ?>/1.jpg" />
			</a>
		</td>
		<td width="100">
			<input type=radio <?= ($membervideo['MemberVideo']['is_active'] == 1 ? "checked='checked'" : "") ?> name="data[is_active]" value="<?= $membervideo['MemberVideo']['member_video_id'] ?>"> Set as Primary
		</td>
		<td class="ralign">
			<a href="/member_videos/delete_youtube/<?= $membervideo['MemberVideo']['member_video_id'] ?>">Delete From YouTube</a>
		</td>
	</tr>
	<? } ?>
	<tr>
		<td colspan=3>
		<input type=submit name="submit" value="Update">
		</td>
	</tr>
	</table>


	<!-- 
	SHOW LIST OF ALL MY VIDEOS...
		perhaps show list of all videos, click to preview, with radio buttons on side to select as default ? 
		MAY want to switch between videos
		MAY want to not erase/overwrite videos...

		have link to ADD video to list

		have link to EDIT video's properties within list?

		SOMEHOW need username, tho only have token info. (can we retrieve their username via their token? or retrieve list of all videos via?)
		
		-->
	</form>
</fieldset>
</div>
