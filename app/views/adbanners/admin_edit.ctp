<div class="adbanner_edit">
<?php echo $form->create('Adbanner',array('type'=>'file'));
		echo $form->input('adbanner_id');
		echo $form->hidden('section');
?>
	<fieldset>
 		<legend><?php __('Edit Ad');?></legend>
		<div class="edit_adbanner_preview">
			<img src="/adbanners/view/<?=$this->data['Adbanner']['adbanner_id']?>">
		</div>

		<table border=0>
	<?php
		echo $html->tableCells(array(
			array(
				$form->input('file',array('type'=>'file','label'=>'Upload NEW File: ','after'=>'<br/>Optimal width: 300px')),
			),
			array(
				$form->input('link_url',array('label'=>'Link URL')),
			),
			array(
				$form->input('disabled',array('label'=>'Disabled?')),
			),
			array(
				$html->link(__('Click Here to Delete', true), "/admin/adbanners/delete/" . $form->value('Adbanner.section') . "/" . $form->value('Adbanner.adbanner_id'), null, sprintf(__('Are you sure you want to delete this ad?', true)))
			),
		));

	?>
		</table>
	</fieldset>
<?php echo $form->end('Update');?>
</div>
