<div class="memberPhotos form">
<?php echo $form->create('MemberPhoto',array('type'=>'file'));
		echo $form->input('photo_id');
		echo $form->hidden('member_id');
?>
	<fieldset>
 		<legend><?php __('Edit MemberPhoto');?></legend>
		<div class="edit_photo_preview">
			Click to zoom into full size.<br/>

			<a href="/member_photos/view/large/<?=$this->data['MemberPhoto']['photo_id']?>">
				<img src="/member_photos/view/medium/<?=$this->data['MemberPhoto']['photo_id']?>">
			</a>
		</div>

		<table border=0>
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('file',array('type'=>'file','label'=>'Upload NEW File: ')),
			),
			array(
				$form->input('title'),
			),
			array(
				$form->input('comment',array('type'=>'text','size'=>50)),
			),
			array(
				$html->link(__("Set as Primary",true), "/member_photos/set_primary/".$this->data['MemberPhoto']['photo_id'],array('class'=>'block'))
			),
			array(
				$html->link(__('Delete', true), array('action'=>'delete', $form->value('MemberPhoto.photo_id')), null, sprintf(__('Are you sure you want to delete "%s"?', true), $form->value('MemberPhoto.title'))) 
			),
		));

		#echo $html->link("Delete Photo", "/member_photos/delete/".$this->data['MemberPhoto']['photo_id'],array('class'=>'block'));
	?>
		</table>
	</fieldset>
<?php echo $form->end('Update');?>
</div>
