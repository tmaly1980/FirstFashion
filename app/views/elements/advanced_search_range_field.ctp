<?
	$opvalues = array(
		'' => '[Any]',
		'<' => 'less than',
		'<=' => 'less than/equals',
		'=' => 'equals', 
		'!=' => 'does not equal',
		'>=' => 'greater than/equals',
		'>' => 'greater than',
		'between' => 'between'
	);
	echo $form->input("$name.op", array('label'=>$label,'type'=>'select','options'=>$opvalues,'onChange'=>'updateSearchRangeField(this);'));
	$params = array('label'=>'');
	if (isset($options)) { $params['options'] = $options; }
	if (isset($after)) { $params['after'] = $after; }
	echo $form->input("$name.0", $params);
	$domid = $form->domId($name);
	$display = 'none';
	
	# If value found in form via post, show field.
	# just need to check for 'between' being set!
	$parts = split("[.]", $name);
	$opfield = "";
	if (count($parts) > 1) { 
		#echo "D[$parts[0][$parts[1]][op] = " . $data[$parts[0]][$parts[1]]['op'];
		if (isset($data) && $data[$parts[0]][$parts[1]]['op'] == "between") { $display = 'block'; }
	} else { 
		#echo "D[$parts[0]][op] = " . $data[$parts[0]]['op'];
		if (isset($data) && $data[$parts[0]]['op'] == "between") { $display = 'block'; }
	} # No model specified....

	echo "<div id='$domid' style='display: $display;'>";
	echo $form->input("$name.1", $params);
	echo "</div>";

?>
