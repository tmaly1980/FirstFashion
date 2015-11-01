<?
return array(
	'default'=>array( # Anyone whose info is NOT listed elsewhere... (ie non-models)
		'basic'=>array(
			'max_photos'=>null,
			'can_send_messages'=>true, # Now all free...
			'video_enabled'=>true, # So all other account types can do.
		),
	),

	'agency'=>array(
		'basic'=>array(
			'max_photos'=>1,
		),
	),

	'model'=>array(
		'basic'=>array(
			'max_photos'=>1,
			'video_enabled'=>false,
			#'can_send_messages'=>false,
		),
		'premium'=>array(
			'max_photos'=>null, # Infinite!
			'video_enabled'=>true,
			#'can_send_messages'=>true,
		),

	),


);

?>
