<div class="memberPhotos form editform">
<?php echo $form->create('MemberPhoto',array('type'=>'file'));

	echo $form->hidden('member_id',array('value'=>$session->read("Auth.Member.member_id")));
	echo $form->hidden('album_id',array('value'=>0));

?>
	<fieldset>
 		<legend><?php __('Add Gallery Photo');?></legend>
		<table border=0>
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('file',array('type'=>'file','label'=>'Upload File: ')),
			),
			array(
				$form->input('title'),
			),
			array(
				$form->input('comment',array('type'=>'text','size'=>50)),
			),
		));
	?>
		</table>
	</fieldset>
<?php echo $form->end('Upload');?>
</div>
