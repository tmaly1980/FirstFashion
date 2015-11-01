<div class="memberSuccessSpotlights form">
<?php echo $form->create('MemberSuccessSpotlight');?>
	<fieldset>
 		<legend><?php __('Edit MemberSuccessSpotlight');?></legend>
	<?php
		echo $form->input('spotlight_id');
		echo $form->input('member_id');
		echo $form->input('start_date');
		echo $form->input('end_date');
		echo $form->input('content');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('MemberSuccessSpotlight.spotlight_id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('MemberSuccessSpotlight.spotlight_id'))); ?></li>
		<li><?php echo $html->link(__('List MemberSuccessSpotlights', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Member', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
