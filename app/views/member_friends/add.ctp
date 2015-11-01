<div class="memberFriends form">
<?php echo $form->create('MemberFriend');?>
	<fieldset>
 		<legend><?php __('Add MemberFriend');?></legend>
	<?php
		echo $form->input('owner_member_id');
		echo $form->input('friend_member_id');
		echo $form->input('authorized');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List MemberFriends', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Owner', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
