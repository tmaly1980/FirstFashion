<?
	$params = array('type'=>'select', 'multiple'=>true,'options'=>$options,'size'=>3);
	if (isset($label)) { $params['label'] = $label; }
	echo $form->input($name, $params);
?>
