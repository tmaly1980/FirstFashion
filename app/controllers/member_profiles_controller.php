<?php
class MemberProfilesController extends AppController {

	var $name = 'MemberProfiles';
	var $uses = array('MemberModelProfile','Member','MemberResume','MemberPhotographerProfile','MemberHairMakeupProfile','MemberAgencyProfile','MemberDesignerProfile');

	function beforeRender()
	{
		$this->set('genders', $this->MemberModelProfile->getEnumValues('gender'));
		$this->set('states', $this->states_list(true));
		$this->set('eye_colors', $this->MemberModelProfile->getEnumValues('eye_color'));
		$this->set('hair_colors', $this->MemberModelProfile->getEnumValues('hair_color'));
		$this->set('ethnicitys', $this->MemberModelProfile->getEnumValues('ethnicity'));
		$this->set('skintones', $this->MemberModelProfile->getEnumValues('skintone'));
		$this->set('heights', $this->Member->get_height());
		$this->set('thisYear', date('Y'));
		parent::beforeRender();
	}

	function edit() {
		$id = $this->Session->read("Auth.Member.member_id");
		$type = $this->Session->read("Auth.Member.member_type");


		$modelclass = 'MemberModelProfile'; # Default...
		if ($type == 'model')
		{
			$modelclass = 'MemberModelProfile';
			$this->action = 'edit_model';
		} else if ($type == 'photographer') {
			$modelclass = 'MemberPhotographerProfile';
			$this->action = 'edit_photographer';
		} else if ($type == 'hair/makeup') {
			$modelclass = 'MemberHairMakeupProfile';
			$this->action = 'edit_hair_makeup';
		} else if ($type == 'agency') {
			$modelclass = 'MemberAgencyProfile';
			$this->action = 'edit_agency';
		} else if ($type == 'designer') {
			$modelclass = 'MemberDesignerProfile';
			$this->action = 'edit_designer';
		} else {
			$this->redirect("/members/view"); # Invalid...
		}

		# XXX TODO, since may not exist, will submit to /controller/add !!!
		# need to get THAT working!

		$this->set("member_id", $id); 
		# So we can get a control nav even if we dont have a record
		# in the current section.


		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Member', true));
			$this->redirect(array('controller'=>'members','action'=>'view'));
		}
		if (!empty($this->data)) {

			$this->data[$modelclass]['since_experience']['day'] = "01";
			
			# Set experience date to include day.
			if ($this->$modelclass->save($this->data)) {
				$this->Session->setFlash(__('Your Profile have been saved', true));
				#$this->redirect(array('action'=>'index'));
			} else {
				$this->setError("Your Profile could not be saved. Please, try again.", true);
			}

			# Now save new resume, if specified....
			if (isset($this->data[$modelclass]['resume']))
			{
				$resume = $this->data[$modelclass]['resume'];
				error_log("RESUME=".print_r($resume,true));
				if (is_uploaded_file($resume['tmp_name']))
				{
					error_log("UPLOADING...");
					$filename = $resume['tmp_name'];
					$givenname = $resume['name'];
					$fileData = fread(fopen($filename, "r"),
	                                	$resume['size']);
	
					$ext = trim(substr($givenname,strrpos($givenname,".")+1,strlen($givenname)));
	
					$this->data['MemberResume'] = $resume; # So save properly!
	
					# Find old entry and replace, if possible
					$old_resume = $this->MemberResume->find("member_id = '$id'");
					if (!empty($old_resume)) { $this->data['MemberResume']['resume_id'] = $old_resume['MemberResume']['resume_id']; }
	
	            			$this->data['MemberResume']['member_id'] = $id;
	            			$this->data['MemberResume']['mimetype'] = $resume['type'];
	            			$this->data['MemberResume']['ext'] = $ext;
	            			$this->data['MemberResume']['data'] = base64_encode($fileData);
	            			#$this->MemberResume->save($this->data['MemberResume']);
	            			$this->MemberResume->save($this->data); 
				}
			}
		}
		if (empty($this->data)) { # To repopulate forms..
			$this->data = $this->$modelclass->read(null, $id);
			# MIGHT not return anything, especially if don't exist!
			$this->data[$modelclass]["member_id"] = $id; # No matter what
			# Since may not exist!
		} else {
			#echo "D2=";
			#print_r($this->data);

		}

		$this->set("member_id", $id);
		$this->set("resumeExists", $this->MemberResume->hasAny("member_id = '$id'"));

		#echo "D=";
		#print_r($this->data);
		#$members = $this->MemberModelProfile->Member->find('list');
		#$this->set(compact('members'));
	}

}
?>
