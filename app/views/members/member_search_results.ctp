<div class="member_search_results">
<h2><?php __($search_title);?></h2>

<?php

if(isset($include_form)) { echo $this->element("member_search_form", $this->viewVars); }

?>

<? if (isset($members) && is_array($members) && count($members)) { ?>

<? echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing members %start% - %end%', true)
));
$paginator->options(array('url' => $this->passedArgs));
?>
<table cellpadding="0" cellspacing="0" class="member_search_results">
<tr class="header">
	<th class="name_col">
		<?php echo $paginator->sort('First', 'firstname');?>, 
		<?php echo $paginator->sort('Last Name /', 'lastname');?>
		<br/>
		<?php echo $paginator->sort('Member Type', 'Member.member_type');?>
	</th>
	<th class="location_col">
		<?php echo $paginator->sort('City', 'city');?>, 
		<?php echo $paginator->sort('State', 'state');?>
	</th>
	<th>
		&nbsp;
	</th>
</tr>
<?php

$i = 0;
foreach ($members as $member):
	$member_id = $member["Member"]["member_id"];
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}

	$location = $member['Member']['city'];
	if ($location != "" && $member['Member']['state'] != "") { $location .= ", "; }
	$location .= $member['Member']['state'];

	echo $html->tableCells(array(
		array(
			array(
				$html->link( $member['Member']['firstname'] . " " . $member['Member']['lastname'],
					"/members/view/$member_id") . "<br/><br/>" .  $member['Member']['member_type'],
				array('class'=>'member_thumb_col'),
			),
			$location,
			"&nbsp;",
		),
		array(
			array($html->link("<img src='/member_photos/view_primary/small/$member_id' class='member_thumb'>",
				"/members/view/$member_id", array('class'=>'member_thumb_link'), false, false), array('rowspan' => 1,'class'=>'member_thumb_col')),
			array("&nbsp;", array('class'=>'','colspan'=>2)),
		),
		array(
			array("&nbsp;", array('class'=>'spacer','colspan'=>7)),
		),
	), array('class'=>"entry $class"),array('class'=>"entry $class"));

	?>

<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>

<? 
} else if (isset($data)) {
	echo "<h3>No members found</h3>";
} ?>
