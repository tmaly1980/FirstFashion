<div class="memberAgencyProfiles form">
<?php 
	echo $form->create('MemberAgencyProfile',array('type'=>'file','url'=>'/member_profiles/edit')); 
		echo $form->input('member_id',array('type'=>'hidden'));

?>
	<fieldset>
 		<legend><?php __('Edit Profile');?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('address'),
			),
			array(
				$form->input('since_experience',array('dateFormat'=>'MY','label'=>'Experience Since','minYear'=>$thisYear-50,'maxYear'=>$thisYear)),
			),
			array(
				$form->input('phone'),
			),
			array(
				array($form->input('website',array('class'=>'full_length')), array('colspan'=>2)),
			),
			array(
				array($form->input('about_me',array('class'=>'full_length about_me','after'=>'<br/>200 characters or less')), array('colspan'=>2)),
			),

		));
	?>
		</table>
	</fieldset>
	<?= $javascript->event('MemberAgencyProfileAboutMe', 'keyup', ' textareaLimit(event, 200); '); ?>
	<?= $javascript->event('MemberAgencyProfileAboutMe', 'keydown', ' textareaLimit(event, 200); '); ?>
	<script type="text/javascript">
	</script>
<input type=submit name="submit" value="Save">
</form>
</div>
