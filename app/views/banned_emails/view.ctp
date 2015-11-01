<div class="bannedEmails view">
<h2><?php  __('BannedEmail');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Banned Email Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bannedEmail['BannedEmail']['banned_email_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bannedEmail['BannedEmail']['email']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit BannedEmail', true), array('action'=>'edit', $bannedEmail['BannedEmail']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete BannedEmail', true), array('action'=>'delete', $bannedEmail['BannedEmail']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $bannedEmail['BannedEmail']['id'])); ?> </li>
		<li><?php echo $html->link(__('List BannedEmails', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New BannedEmail', true), array('action'=>'add')); ?> </li>
	</ul>
</div>
