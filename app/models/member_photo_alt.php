<?php
class MemberPhoto extends AppModel {

	var $name = 'MemberPhoto';
	var $primaryKey = 'photo_id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	function upload($data) # 
	{
		if (!isset($data['ext']) && isset($data['file']))
		{
			preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
			$ext = $matcher[1];
			if ($ext) { $data['ext'] = $ext; }
		}

		if (!isset($data["photo_id"])) # Adding...
		{
			$this->create();
		} else {
			$photo_id = isset($data["photo_id"]);
		}

		if (!$this->MemberPhoto->save($data))
		{
			$errors = array("The photo could not be saved. Please, try again");
			return $errors;
		}

		$photo_id = $this->id; # In case adding...
		$errors = $this->saveUpload($data);
		return $errors;
	}

	function saveUpload($data);
	{
		$member_id = $data['member_id'];
		if ($data['file']) { # IF uploading too...
			# Either in upload data struct format ('name', '???') or single string (filename)

			# Get extension from filename....
			preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
			$ext = $matcher[1];

			#error_log("NAME=$fileobj[name], EX=$ext");

			if ($fileobj["size"] < 1024) # Probably invalid!
			{
				return array("Invalid image. File size too small.");
			}

			# Now save file to disk...
			#$dest_path = sprintf(APP . "/webroot/images/members/%d/%d/large/%d.%s",
			$dest_path = sprintf(APP . "/webroot/images/members/%d/large/%d.%s",
				$member_id,
				$photo_id,
				$ext
			);

			$dest_dir = dirname($dest_path);
			$dest_file = basename($dest_path);

			error_log("DEST_DIR=$dest_dir, DEST_FILE=$dest_file");

			if (!is_dir($dest_dir))
			{
				if (!mkdir($dest_dir, 0755, true))
				{
					return array("Unable to create folder $dest_dir");
				} else {
					error_log("CREATED $dest_dir");
				}
			}

			error_log("SAVING AS $dest_path");

			# GRRR, we dont have this available!!!! unless some damned wrapper!
			$this->Upload->upload($fileobj, "$dest_dir/", $dest_file);
			$errors = $this->Upload->errors;

			return $errors;
		}
	}

}
?>
