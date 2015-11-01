<div class="memberPhotos form">
<?php echo $form->create('MemberPhoto');?>
	<fieldset>
 		<legend><?php __('Edit MemberPhoto');?></legend>
	<?php
		echo $form->input('photo_id');
		echo $form->input('member_id');
		echo $form->input('ext');
		echo $form->input('album_id');
		echo $form->input('album_order');
		echo $form->input('is_primary');
		echo $form->input('title');
		echo $form->input('comment');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('MemberPhoto.photo_id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('MemberPhoto.photo_id'))); ?></li>
		<li><?php echo $html->link(__('List MemberPhotos', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Member', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
