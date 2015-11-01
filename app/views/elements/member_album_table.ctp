<table id="member_album" cellpadding=0 cellspacing=0 border=0>
	<tr>
		<td id="member_album_top_left"></td>
		<td id="member_album_top"></td>
		<td id="member_album_top_right"></td>
	</tr>

	<tr><td colspan=3 id="member_album_content">
	<h2>My Gallery</h2>
	<? 
	if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
		echo "<div class='clear editlink'>[<a href='/member_photos/editlist'>Edit Photos</a>]</div>";
	}
	
	if (isset($member["MemberPhoto"]) && count($member["MemberPhoto"]))
	{
	
		foreach($member["MemberPhoto"] as $member_photo)
		{
			$photo_title = $member_photo["title"];
			$photo_id = $member_photo["photo_id"];
			$is_primary = $member_photo["is_primary"] ? "primary_photo" : "";
		?>
			<a class='member_album_photo Xlightwindow' params='lightwindow_type=image' href='/member_photos/view/large/<?=$photo_id?>' title="<?= $photo_title ?>">
				<img src="/member_photos/view/small/<?=$photo_id?>">
			</a>
		<?
		}
	
	}
	?>
	</td></tr>

	<tr>
		<td id="member_album_bottom_left"></td>
		<td id="member_album_bottom"></td>
		<td id="member_album_bottom_right"></td>
	</tr>

</table>
