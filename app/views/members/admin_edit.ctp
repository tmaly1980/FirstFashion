<div id="" class="members form">
	<?  if (isset($current_is_admin) && $current_is_admin > 0) { echo $this->element('admin_moderate_nav', $this->viewVars); } ?>
<?php echo $form->create('Member');
	echo $form->input('member_id');

	if ($this->data['Member']['member_type'] == 'model')
	{
		$featured_membership = array(
			$html->link("Featured Listing", "/admin/member_featured_models/manage/$member_id", array('class'=>'localnavitem')),
			"To sign up for a 'Featured Listing', or to manage your existing 'Featured Listing' (including cancellation), click here.",
		);
	} else {
		$featured_membership = array();
	}

?>
	<fieldset>
 		<legend><?php __('Edit Portfolio');?></legend>
		<table class="editform">
	<?php
		$name = array(
				$form->input('firstname',array('label'=>'First Name')),
				$form->input('lastname',array('label'=>'Last Name')),
			);
		if ($this->data['Member']['member_type'] == 'agency')
		{
			$name = array(
				$form->input('firstname',array('label'=>'Company Name')),
			);
		}

		echo $html->tableCells(array(
			$name,
			array(
				$form->input('city'),
				$form->input('state',array('options'=>$states,'default'=>'PA'))
			),
			array(
				array($form->input('birthdate',array('after'=>'<br/> for age calculation','minYear'=>$thisYear-100,'maxYear'=>$thisYear)),
					array('colspan'=>2),
				)
				# Hopefully insert
			),
			array(
				$form->input('is_admin',array('label'=>'Enable Administrator')),
			),
			array(
				$form->input('member_type',array('options'=>$member_types)),
				$form->input('membership_level',array('options'=>$member_levels)),
			),
			$featured_membership,
		));
	?>
		</table>
	</fieldset>
<?php echo $form->end('Save');?>
</div>

