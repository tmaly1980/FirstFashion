<div class="memberModelProfiles form">
<?php 
	echo $form->create('MemberModelProfile',array('type'=>'file','url'=>'/member_profiles/edit')); 
		echo $form->input('member_id',array('type'=>'hidden'));

?>
	<fieldset>
 		<legend><?php __('Edit Profile');?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('gender', array('options'=>$genders)),
			),
			array(
				$form->input('weight',array('size'=>6,'after'=>' lbs.')),
				$form->input('height',array('options'=>$heights,'default'=>66)),
			),
			array(
				$form->input('measurements',array('size'=>8,'after'=>'<br/> in BUST-WAIST-HIPS format')),
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
				$form->input('eye_color', array('options'=>$eye_colors)),
				$form->input('hair_color', array('options'=>$hair_colors)),
			),
			array(
				$form->input('skintone', array('label'=>'Skin Tone', 'options'=>$skintones)),
				$form->input('ethnicity', array('options'=>$ethnicitys)),
			),
			array(
				array($form->input('availability',array('class'=>'block full_width')), array('colspan'=>2)),
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
	<?= $javascript->event('MemberModelProfileAboutMe', 'keyup', ' textareaLimit(event, 200); '); ?>
	<?= $javascript->event('MemberModelProfileAboutMe', 'keydown', ' textareaLimit(event, 200); '); ?>
	<script type="text/javascript">
	</script>
<input type=submit name="submit" value="Save">
</form>
</div>
