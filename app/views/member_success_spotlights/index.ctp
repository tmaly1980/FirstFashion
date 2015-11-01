<div class="memberSuccessSpotlights index">
<h2><?php __('MemberSuccessSpotlights');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('spotlight_id');?></th>
	<th><?php echo $paginator->sort('member_id');?></th>
	<th><?php echo $paginator->sort('start_date');?></th>
	<th><?php echo $paginator->sort('end_date');?></th>
	<th><?php echo $paginator->sort('content');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($memberSuccessSpotlights as $memberSuccessSpotlight):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id']; ?>
		</td>
		<td>
			<?php echo $html->link($memberSuccessSpotlight['Member']['member_id'], array('controller'=> 'members', 'action'=>'view', $memberSuccessSpotlight['Member']['member_id'])); ?>
		</td>
		<td>
			<?php echo $memberSuccessSpotlight['MemberSuccessSpotlight']['start_date']; ?>
		</td>
		<td>
			<?php echo $memberSuccessSpotlight['MemberSuccessSpotlight']['end_date']; ?>
		</td>
		<td>
			<?php echo $memberSuccessSpotlight['MemberSuccessSpotlight']['content']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id'])); ?>
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
		<li><?php echo $html->link(__('New MemberSuccessSpotlight', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Members', true), array('controller'=> 'members', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Member', true), array('controller'=> 'members', 'action'=>'add')); ?> </li>
	</ul>
</div>
