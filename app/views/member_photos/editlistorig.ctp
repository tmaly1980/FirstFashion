<?php echo $this->element("member_edit_menu"); ?>
<div class="memberPhotos index">
<h2><?php __('Photo Album');?></h2>

<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('photo_id');?></th>
	<th><?php echo $paginator->sort('member_id');?></th>
	<th><?php echo $paginator->sort('ext');?></th>
	<th><?php echo $paginator->sort('album_id');?></th>
	<th><?php echo $paginator->sort('album_order');?></th>
	<th><?php echo $paginator->sort('is_primary');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('comment');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($memberPhotos as $memberPhoto):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['photo_id']; ?>
		</td>
		<td>
			<?php echo $html->link($memberPhoto['Member']['member_id'], array('controller'=> 'members', 'action'=>'view', $memberPhoto['Member']['member_id'])); ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['ext']; ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['album_id']; ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['album_order']; ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['is_primary']; ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['title']; ?>
		</td>
		<td>
			<?php echo $memberPhoto['MemberPhoto']['comment']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $memberPhoto['MemberPhoto']['photo_id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $memberPhoto['MemberPhoto']['photo_id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $memberPhoto['MemberPhoto']['photo_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $memberPhoto['MemberPhoto']['photo_id'])); ?>
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
		<li><?php echo $html->link(__('New MemberPhoto', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Member', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
