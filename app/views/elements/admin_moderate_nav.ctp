<div id="admin_moderate_nav">
	<?
	echo "<b>Moderate this user:</b> ";
	echo $html->link("View Portfolio", "/members/view/$member_id", array('class'=>'localnavitem')); ?> | <?
	echo $html->link("Edit Account", "/admin/members/edit/$member_id", array('class'=>'localnavitem')); ?> | <?
	if ($member['Member']['active']) {
		echo $html->link("Suspend", "/admin/members/suspend/$member_id", array('class'=>'localnavitem')); ?> | <?
	} else {
		echo $html->link("Reinstate", "/admin/members/reinstate/$member_id", array('class'=>'localnavitem')); ?> | <?
	} 
	echo $html->link("Delete", "/admin/members/delete/$member_id", array('class'=>'localnavitem','onClick'=>"return confirm('Are you sure you want to delete this account? Once removed, it CANNOT be restored.');")); ?> | <?
	echo $html->link("Ban Email", "/admin/banned_emails/add/{$member['Member']['email']}", array('class'=>'localnavitem')); ?> | <?
	echo $html->link("Spotlight", "/admin/member_success_spotlights/editmember/$member_id", array('class'=>'localnavitem','rel'=>"shadowbox;width=450;height=450;player=iframe")); ?>

	</div>
</div>
