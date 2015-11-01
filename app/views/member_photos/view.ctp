<div class="memberPhotos view">
<h2><?php  __('MemberPhoto');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Photo Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['photo_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Member'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($memberPhoto['Member']['member_id'], array('controller'=> 'members', 'action'=>'view', $memberPhoto['Member']['member_id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ext'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['ext']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Album Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['album_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Album Order'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['album_order']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Is Primary'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['is_primary']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $memberPhoto['MemberPhoto']['comment']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit MemberPhoto', true), array('action'=>'edit', $memberPhoto['MemberPhoto']['photo_id'])); ?> </li>
		<li><?php echo $html->link(__('Delete MemberPhoto', true), array('action'=>'delete', $memberPhoto['MemberPhoto']['photo_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $memberPhoto['MemberPhoto']['photo_id'])); ?> </li>
		<li><?php echo $html->link(__('List MemberPhotos', true), array('action'=>'editlist')); ?> </li>
		<li><?php echo $html->link(__('New MemberPhoto', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Member', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
