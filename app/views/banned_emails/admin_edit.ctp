<div class="bannedEmails form">
<?php echo $form->create('BannedEmail');?>
	<fieldset>
 		<legend><?php __('Edit BannedEmail');?></legend>
	<?php
		echo $form->input('banned_email_id');
		echo $form->input('email');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('BannedEmail.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('BannedEmail.id'))); ?></li>
		<li><?php echo $html->link(__('List BannedEmails', true), array('action'=>'index'));?></li>
	</ul>
</div>
