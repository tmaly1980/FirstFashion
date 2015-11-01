<div id="" class="members form">
<?php echo $form->create('Member');
	echo $form->input('member_id');

	if ($this->data['Member']['member_type'] == 'model')
	{
	
		if ($this->data['Member']['membership_level'] != 'basic')
		{
			$upgrade_downgrade = array(
				$html->link("Downgrade to Free Membership", "/members/downgrade", array('class'=>'localnavitem')),
				"To downgrade from a paid membership to a free membership, click here.",
			);
	
		} else {
			$upgrade_downgrade = array(
				$html->link("Upgrade to Paid Membership", "/members/upgrade", array('class'=>'localnavitem')),
				"To review or upgrade to a paid monthly membership with added features, click here.",
			);
	
		}
		$featured_membership = array(
			$html->link("Featured Listing", "/member_featured_models/manage", array('class'=>'localnavitem')),
			"To sign up for a 'Featured Listing', or to manage your existing 'Featured Listing' (including cancellation), click here.",
		);
	} else {
		$upgrade_downgrade = array(); # Nothing for non-models
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
				$form->input('member_type',array('options'=>$member_types,'disabled'=>true)),
				$form->input('membership_level',array('options'=>$member_levels,'disabled'=>true)),
			),
			array(
				"<h3>Other Tasks</h3>",
			),
			array(
				$html->link("Change Password", "/members/change_password/$member_id", array('class'=>'localnavitem'))
			),
			array(
				$html->link("Change Primary Photo", "/member_photos/editlist/$member_id", array('class'=>'localnavitem')),
				"To change your primary photo, click on an image within your gallery and select 'Set as Primary'."
			),
			$upgrade_downgrade,
			$featured_membership,
		));
	?>
		</table>
	</fieldset>
<?php echo $form->end('Save');?>
</div>

