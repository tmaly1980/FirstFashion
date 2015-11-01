<photos>
<?php
$base_path = WWW_ROOT;
$error_path = "/images/members/default/small/error.jpg";
foreach ($memberPhotos as $entry)
{
	$member_photo = $entry['MemberPhoto'];
	$member_id = $member_photo["member_id"];
	$thumb_path = "/images/members/$member_id/small/";
	$large_path = "/images/members/$member_id/large/";

	$photo_id = $member_photo["photo_id"];
	$ext = $member_photo["ext"];
	if (!$ext) { $ext = 'jpg'; }
	$photo_path = "$photo_id.$ext";
	$photo_title = $member_photo["title"];

	$large_image = "$large_path/$photo_id.$ext";
	$disabled = false;

	if (!is_dir("$base_path/$thumb_path")) { mkdir("$base_path/$thumb_path", 0755, true); }

	if (!is_file("$base_path/$large_path/$photo_path")) 
	{ 
		# Should skip, but should know it's a bogus image so can remove from list or upload properly...

	}

	if ($mode == 'edit')
	{
		echo "<a class='member_album_photo' href='Javascript:void(0);' title='$photo_title' onClick='loadPhotoEditForm(\"photo_".$member_photo["photo_id"]."\");'>\n" ;
	} else {
		echo "<a class='member_album_photo lightwindow' href='$large_image' title='$photo_title'>" ;
	}

	echo	$html->image(
			#$thumb_path . # Needs to be STRING append, not comma separator!
			# Since can return alt image if errors, need to pass back abs url...
			$thumbnail->render($photo_path,
				array(
					'path'=>$large_path,
					'cachepath'=>$thumb_path,
					'errorfile'=>$error_path,
					'width'=>85,
					'height'=>85,
					'quality'=>80
				)
			),
			array('id'=>"photo_".$member_photo["photo_id"], 'title'=>$member_photo["title"],'comment'=>$member_photo['comment'],'photo_id'=>$member_photo["photo_id"],'album_id'=>$member_photo["album_id"])
		)
		. "\n</a>";
}

		?>
</photos>
