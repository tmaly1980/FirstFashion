<div class="memberSuccessSpotlights index">
<?= $this->element("admin_spotlight_nav"); ?>
<h2><?php __('Success Spotlight');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, entries %start% - %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('member_id');?></th>
	<th><?php echo $paginator->sort('start_date');?></th>
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
			<?php echo $html->link(
				$memberSuccessSpotlight['Member']['firstname'] . ' ' . $memberSuccessSpotlight['Member']['lastname'], 
				array('action'=>'edit', $memberSuccessSpotlight['MemberSuccessSpotlight']['spotlight_id'])); 
				?>
		</td>
		<td>
			<?php echo $time->nice($memberSuccessSpotlight['MemberSuccessSpotlight']['start_date']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<p>To add new members to the Spotlight, go to their portfolio page and click on the 'Spotlight' in the moderator menu.
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
