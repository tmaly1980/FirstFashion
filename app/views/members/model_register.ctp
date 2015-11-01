<div id="model_register" class="members form">

<div class="form_sidebar_text" id="model_register_text">
<h3> Be a First Fashion Model! </h3>

<p>Content regarding program....

<p>Further content, etc....

</div>

	<form method="POST">
	<fieldset>
		<legend><?php __('Registration'); ?></legend>
		<table class="editform">
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('name',array('label'=>'Name','size'=>30)),
			),
			array(
				$form->input('email',array('label'=>'Email','size'=>30)),
			),

			array(
				$form->input('phone',array('label'=>'Phone','size'=>15)),
			),
			array(
				$form->input('best_time',array('label'=>'Best Time to Call')),
			),
			array(
				$form->input('gender',array('label'=>'Gender','type'=>'select','options'=>$gender_values)),
			),
			array(
				$form->input('age',array('label'=>'Age')),
			),
			array(
				$form->input('city',array('label'=>'City of Residence')),
			),
			array(
				$form->input('state',array('label'=>'State','default'=>'PA','type'=>'select','options'=>$states_values)),
			),

		));
	?>
		</table>
	</fieldset>

<?php echo $form->end("Register"); ?>
</div>
