<div class="member_video_add">
<legend><h2>Add YouTube Video: Video Information</h2></legend>
<fieldset>
	<?= $form->create("MemberVideo",array('type'=>'file')); ?>
	<form method="post" action="/member_videos/add" enctype="multipart/form-data">

	<p>Before uploading your video file, please enter in some information about your video clip:
	<table class="video_add" width="100%" border=0 cellspacing=0>
	<?

		$is_active_options = array('type'=>'checkbox', 'label'=>'Set as Active?', 'after'=>'Check this to make this video your default video.');
		if (!$has_active)
		{
			$is_active_options['checked'] = 'checked'; # Since no other 'active' videos, set this one as default
		}

		echo $html->tableCells(array(
			array(
				$form->input('title'),
			),
			array(
				$form->input('description'),
			),
			array(
				$form->input('is_active', $is_active_options),
			),
			array(
				"<b>After you click Continue, you will be able to upload your video file.</b>",
			),
		));

	?>
	</table>

	<?= $form->end("Continue"); ?>

</fieldset>
</div>
