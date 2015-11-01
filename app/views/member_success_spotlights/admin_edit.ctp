<div class="memberSuccessSpotlights form">
<?= $this->element("admin_spotlight_nav"); ?>
<?php echo $form->create('MemberSuccessSpotlight');?>
	<fieldset>
 		<legend><?php __('Edit MemberSuccessSpotlight');?></legend>
	<?php
		echo $form->input('spotlight_id');
		#echo $form->input('member_id');
		echo "<div><label>Member</label> {$this->data['Member']['firstname']} {$this->data['Member']['lastname']}</div>";
		echo $form->input('start_date',array('dateFormat'=>'MDY'));
		#echo $form->input('end_date');
		echo $form->input('content');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('MemberSuccessSpotlight.spotlight_id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('MemberSuccessSpotlight.spotlight_id'))); ?></li>
	</ul>
</div>
