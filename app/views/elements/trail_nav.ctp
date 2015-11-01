<? if($firstfashion->isOwnProfile()) { ?>
<div id="trail_nav">
	<div id="trail">
		<?
			# Will read from trail parameters to see if can form a trail.
			if (isset($trail) && count($trail))
			{
				$i = 0;
				foreach($trail as $trail_item)
				{
					if ($i > 0) { echo " &raquo; "; }
					echo "<a href='$trail_item[0]'>$trail_item[1]</a>\n";
				}
			}
		?>
	</div>
	<div id="localnav">
<?php
	$view =& ClassRegistry::getObject('view');
	$member_id = $view->getVar("member_id");
	# Either way, we will get this cross-ref record
	$newmsgs = $current_member_new_message_count ? "($current_member_new_message_count new)" : "";

	echo $html->link("View Portfolio", "/members/view", array('class'=>'localnavitem')); ?> | <?
	echo $html->link("Messages $newmsgs", "/member_messages/index", array('class'=>'localnavitem')); ?> | <?
	echo $html->link("Edit Account", "/members/edit", array('class'=>'localnavitem')); 
	if (
		$current_member['member_type'] == 'model' ||
		$current_member['member_type'] == 'photographer' ||
		$current_member['member_type'] == 'hair/makeup' ||
		$current_member['member_type'] == 'agency' ||
		$current_member['member_type'] == 'designer'
	)
	{
?> | <?
		echo $html->link("Edit Profile", "/member_profiles/edit", array('class'=>'localnavitem')); 
	}
	if ($current_member['member_type'] != 'agency') {?> | <?
		echo $html->link("Edit Photos", "/member_photos/editlist", array('class'=>'localnavitem'));
		if (true || $current_member_limits['video_enabled'] === true)
		{
			echo " | " . $html->link("Edit Video", "/member_videos/editlist", array('class'=>'localnavitem'));
		}
	
		if ($current_member['member_type'] == 'model' &&
			$current_member['membership_level'] == 'basic') {
			?> | <?
			echo $html->link("Upgrade Membership", "/members/upgrade", array('class'=>'localnavitem'));
	
		}
		if ($current_member['member_type'] == 'model')
		{
			?> | <?
			echo $html->link("Featured Listing", "/member_featured_models/manage", array('class'=>'localnavitem'));
		}
	}
?>
	</div>
</div>
<? } ?>

