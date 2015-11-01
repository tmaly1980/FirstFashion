<div class="member_video_add_upload">
<legend><h2>Add YouTube Video: Upload File</h2></legend>
<fieldset>
	<form method="post" action="<?= $upload_url ?>?nexturl=<?= $return_url ?>" enctype="multipart/form-data">

	<table class="video_add_upload" width="100%" border=0 cellspacing=0>
		<input type=hidden name="token" value="<?= $upload_token ?>">
	<?

		echo $html->tableCells(array(
			array(
				"<label>Title</label>" . $video["MemberVideo"]['title'],
			),
			array(
				"<label>Description</label>" . $video["MemberVideo"]['description'],
			),
			array(
				$form->input('file',array('name'=>'file','type'=>'file')),
			),
			array(
				"<b>Please note: Video upload may take several minutes to complete, or time out and fail if video is too long or your Internet connection is too slow. If this happens, please go back to this page and try again.</b>",
			),
		));

	?>
	</table>

	<?= $form->end("Upload"); ?>

</fieldset>
</div>
