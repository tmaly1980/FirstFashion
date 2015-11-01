<div class="memberFriends index">
<h2><?php __('MemberFriends');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('friend_id');?></th>
	<th><?php echo $paginator->sort('owner_member_id');?></th>
	<th><?php echo $paginator->sort('friend_member_id');?></th>
	<th><?php echo $paginator->sort('authorized');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($memberFriends as $memberFriend):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $memberFriend['MemberFriend']['friend_id']; ?>
		</td>
		<td>
			<?php echo $html->link($memberFriend['Owner']['member_id'], array('controller'=> 'members', 'action'=>'view', $memberFriend['Owner']['member_id'])); ?>
		</td>
		<td>
			<?php echo $html->link($memberFriend['Friend']['member_id'], array('controller'=> 'members', 'action'=>'view', $memberFriend['Friend']['member_id'])); ?>
		</td>
		<td>
			<?php echo $memberFriend['MemberFriend']['authorized']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $memberFriend['MemberFriend']['friend_id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $memberFriend['MemberFriend']['friend_id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $memberFriend['MemberFriend']['friend_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $memberFriend['MemberFriend']['friend_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New MemberFriend', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Owner', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
