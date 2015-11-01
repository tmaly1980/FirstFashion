<div id="profile_page">
	<?  if (isset($current_is_admin) && $current_is_admin > 0) { echo $this->element('admin_moderate_nav', $this->viewVars); } ?>
	<?= $this->element('member_profile', $this->viewVars); ?>
	<? 
		#echo $this->element('member_ratings', $this->viewVars); 
	?>
	<? if ($member['Member']['member_type'] != 'agency') { ?>
	<div id="profile_secondary">
		<?= $this->element('member_friends', $this->viewVars); ?>
		<?= $this->element('member_album', $this->viewVars); ?>
		<?= $this->element('member_video', $this->viewVars); ?>
	</div>
	<? } ?>
</div>
<div class="clear"></div>
