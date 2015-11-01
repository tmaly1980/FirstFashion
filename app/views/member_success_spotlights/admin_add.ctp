<div class="memberSuccessSpotlights form">
<?= $this->element("admin_spotlight_nav"); ?>
<?php echo $form->create('MemberSuccessSpotlight');?>
	<fieldset>
 		<legend><?php __('Add Member to Success Spotlight');?></legend>
	<?php
		echo "<div><label>Member</label> {$this->data['Member']['firstname']} {$this->data['Member']['lastname']}</div>";
		echo $form->hidden("member_id", $this->data['Member']['member_id']);
		echo $form->input('start_date',array('dateFormat'=>'MDY','after'=>'<br/>Closest available week chosen, starting with Sunday.<br/>'));
		#echo $form->input('end_date');
		echo $form->input('content');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
