<div id="" class="members form">
<?php echo $form->create('Member');
	echo $form->input('member_id');
?>
	<fieldset>
 		<legend><?php __('Change Password');?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('password',array('type'=>'password','value'=>'')),
			),
			array(
				$form->input('password2',array('type'=>'password','value'=>'','label'=>"Verify Password")),
			)
		));
	?>
		</table>
	</fieldset>
<?php echo $form->end('Change Password');?>
</div>


