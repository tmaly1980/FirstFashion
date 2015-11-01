<div id="members_signup" class="members form">

<div class="form_sidebar_text" id="members_signup_text">
<h3> Start Your Fashion Career Now! </h3>

<p>Content regarding signup, what to expect, etc...

<p>Signup is FREE! etc...

<p>Further content, etc....

<p><a href="#">Links</a> to important pages, documents, etc

<p><a href="#">Terms of Service</a>
</div>

<?php echo $form->create("Member", array('action'=>'signup')); 
?>
	<fieldset>
		<legend><?php __('Member Signup'); ?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('member_type',array('label'=>'Membership Type','options'=>$member_types,'onChange'=>'reloadSignupForm(this);','default'=>'agency')),
			),
			array(
				$form->input('firstname',array('label'=>'Company Name')),
			),
			array(
				$form->input('username',array('label'=>'Desired Username')),
				$form->input('email',array('label'=>'Email','size'=>30)),
			),

			array(
			),
			array(
				$form->input('password',array('type'=>'password','value'=>'')),
				$form->input('password2',array('type'=>'password','value'=>'','label'=>'Password Verification','after'=>'')),
			),

		));
	?>
		<tr><td colspan=2>
			<input type=checkbox name="terms" value="1"> I have read and agree to the <a target="_new" href="/pages/terms">Terms of Service</a>
		</td></tr>

		</table>
	</fieldset>

<?php echo $form->end("Signup"); ?>
</div>
