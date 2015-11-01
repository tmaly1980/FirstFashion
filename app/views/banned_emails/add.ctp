<div class="bannedEmails form">
<?php echo $form->create('BannedEmail');?>
	<fieldset>
 		<legend><?php __('Add BannedEmail');?></legend>
	<?php
		echo $form->input('banned_email_id');
		echo $form->input('email');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List BannedEmails', true), array('action'=>'index'));?></li>
	</ul>
</div>
