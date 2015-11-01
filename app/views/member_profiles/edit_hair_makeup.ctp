<div class="memberHairMakeupProfiles form">
<?php 
	echo $form->create('MemberHairMakeupProfile',array('type'=>'file','url'=>'/member_profiles/edit')); 
		echo $form->input('member_id',array('type'=>'hidden'));

?>
	<fieldset>
 		<legend><?php __('Edit Profile');?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('employer_name', array('label'=>'Employer Name')),
			),
			array(
				$form->input('gender', array('options'=>$genders)),
			),
			array(
				$form->input('since_experience',array('dateFormat'=>'MY','label'=>'Experience Since','minYear'=>$thisYear-50,'maxYear'=>$thisYear)),
			),
			array(
				array(
				$form->input('resume', array('type'=>'file','label'=>'Upload New Portfolio','after'=>'<br/> Replaces existing. PDF, Word DOC, etc')) .
				($resumeExists ? "<br/><a target='_new' href='/member_resumes/view/$member_id'>View Current Portfolio</a> | <a href='/member_resumes/delete/$member_id'>Delete Portfolio</a>" : "<br/><i>No existing portfolio</i>"),
				array('colspan'=>2),
				),
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
	<?= $javascript->event('MemberHairMakeupProfileAboutMe', 'keyup', ' textareaLimit(event, 200); '); ?>
	<?= $javascript->event('MemberHairMakeupProfileAboutMe', 'keydown', ' textareaLimit(event, 200); '); ?>
	<script type="text/javascript">
	</script>
<input type=submit name="submit" value="Save">
</form>
</div>
