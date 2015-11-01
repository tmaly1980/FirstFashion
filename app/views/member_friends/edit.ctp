<div class="memberFriends form">
<?php echo $form->create('MemberFriend');?>
	<fieldset>
 		<legend><?php __('Edit MemberFriend');?></legend>
	<?php
		echo $form->input('friend_id');
		echo $form->input('owner_member_id');
		echo $form->input('friend_member_id');
		echo $form->input('authorized');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('MemberFriend.friend_id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('MemberFriend.friend_id'))); ?></li>
		<li><?php echo $html->link(__('List MemberFriends', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Owner', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
