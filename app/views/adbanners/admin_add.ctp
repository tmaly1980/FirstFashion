<div class="adbanner_add">
<?php echo $form->create('Adbanner',array('type'=>'file'));
		echo $form->input('adbanner_id');
		echo $form->hidden('section');
?>
	<fieldset>
 		<legend><?php __('Create Ad');?></legend>
		<table border=0>
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('file',array('type'=>'file','label'=>'Upload File: ','after'=>'<br/>Optimal width: 300px')),
			),
			array(
				$form->input('link_url',array('label'=>'Link URL')),
			),
			array(
				$form->input('disabled',array('label'=>'Disabled?')),
			),
		));

	?>
		</table>
	</fieldset>
<?php echo $form->end('Upload');?>
</div>
