<?php


class MembersController extends AppController
{
	# class vars
	var $components = array('Auth', 'RequestHandler','Youtube');
	var $uses = array('Member','MemberModelProfile','MemberPhoto','MemberResume','MemberVideo','BannedEmail');
	#var $helpers = array('Thumbnail','Ajax','chat/AjaxChat','Time');
	var $helpers = array('Thumbnail','Ajax','Time');
	#var $helpers = array('Thumbnail','Ajax');

	var $paginate = array(
		'NewestFashion' => array(
			'limit'=>3, # Since all there's room for...
			'order'=>array('NewestFashion.registration_date' => 'DESC','NewestFashion.member_id' => 'DESC'),
		),
	);

	function beforeFilter()
	{
		if (!method_exists($this, $this->params['action']))
		{
			$this->params['action'] = 'defaultaction';
		}
		#$this->Auth->allow('home', 'forgot','signup','view','index','browse','search'); # Anonymous pages....
		$this->Auth->allow('banned', 'defaultaction','home', 'chat','forgot','signup','view','index','browse','search','search_advanced', 'create_random_member','newest_fashions','spotlight','flag'); # Anonymous pages....
		#error_log("MEMBERS BEFORE FILTER");
		parent::beforeFilter();
	}

	function banned()
	{
	}

	function defaultaction()
	{
		echo "DEFAULT CONTENT....";
		exit(0);
	}

	function beforeRender() # Global vars.. across all views...
	{
		parent::beforeRender();

		switch ($this->action)
		{
			case 'edit':
			case 'admin_edit':
			case 'signup':
				$this->set('member_types', $this->Member->getEnumValues('member_type'));
				$this->set('member_levels', $this->Member->getEnumValues('membership_level'));
				$this->set('thisYear', date('Y'));
				$this->set('states', $this->states_list());

				break;
			default:
				break;
		}
	}
	

	function newest_fashions()
	{
		$this->layout = 'xml';
		$members =& ClassRegistry::init(array('class'=>'Member','alias'=>'NewestFashion'));
		$members->recursive = 0;

		$newest_fashions = $this->paginate($members,array("NewestFashion.active = 1"));
		if (count($newest_fashions))
		{
			foreach($newest_fashions as &$member) # Do formatting on friend info...
			{
				$member['NewestFashion']['calculated_age'] = $this->Member->get_age($member['NewestFashion']['birthdate']);
			}
		}
		$this->set('newest_fashions', $newest_fashions);
	}

	function home() # Display main page...
	{
	}

	function login() # Kept blank, since logins go to profile page!
	{
		$email = $this->Session->read("Auth.Member.email");
		$member_id = $this->Session->read("Auth.Member.member_id");

		if (isset($email))
		{
			if ($this->BannedEmail->hasAny("email = '$email'"))
			{
				# Email banned.
				$this->redirect("/members/banned");
			}
		}
		error_log("AUTH=".print_r($this->Session->read("Auth"),true));

		if ($this->Auth->user())
		{
			# Save session_id into Member table, so can reference and clear session.
			$this->Member->id = $member_id;
			$this->Member->set("session_id", session_id());
			$this->Member->save();

			# DO NOT redirect ot HTTP_REFERER, etc IF we directly go to this url via a link.

			$this->redirect($this->Auth->redirect()); # Now continue...
		}
	}

	function logout()
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->Member->id = $member_id;
		$this->Member->set("session_id", null);
		$this->Member->save();

		#error_log("REDIRECTING FOR LOGOUT...");

		$this->redirect($this->Auth->logout());
	}

	function forgot()
	{
		if (!empty($this->data)) # Specified email to send new password to.
		{
			$email = $this->data['Member']['email'];
			$member = $this->Member->find("email = '$email'");

			# Somehow there's a disconnect from findByEmail to saveField...
			# (since it creates a new record!!!!)

			if ($member)
			{ # FOUND!
				#error_log("FOUND!=");
				#print_r($member);
				# XXX TODO (email send, regen password)
				
				# For now, just reset password to 'username1';
				$username = $member["Member"]["username"];
				$member_id = $member["Member"]["member_id"];
				#$new_password = $username . "1";

				$new_password = $this->Member->generate_password();


				# SET...
				#$id = $this->Member->member_id = $member["Member"]['member_id'];
				#$id = $this->Member->id = $member["Member"]['member_id'];
				#error_log( "ID=$id\n");
				$enc_password = $this->Auth->password($new_password);
				$this->Member->id = $member["Member"]['member_id'];
				$this->Member->saveField("password", $enc_password);
				
				#$member["Member"]["password"] = $enc_password;
				#$this->Member->save($member);

				# Send email out.
				$this->sendEmail($member, "FirstFashionSite.com New Password", "password_reset", array("new_password" => $new_password));

				#$this->Session->setFlash("Password changed. You should recieve an email shortly. ($new_password = $enc_password)");
				$this->Session->setFlash("Password changed. You should recieve an email shortly.");
				#$this->redirect(array('action'=>"login"));
				# this is breaking it!
				$this->Session->delete('Auth.redirect');

			
			} else { # Doesn't exist.
				$this->Session->setSplash(__('Sorry, that email does not exist.', true));
			}


		}

	}

	function change_password()
	{
		$sessid = $this->Session->read("Auth.Member.member_id");
		
		if (!empty($this->data)) # Sent back...
		{
			# If member_id isn't theirs, BARF!
			$id = $this->data["Member"]["member_id"];
			if ($id !== $sessid)
			{
				$this->Session->setFlash('Unauthorized. You are not this user.');
				return;
			}

			$enc_password = $this->data["Member"]["password"];
			$password2 = $this->data["Member"]["password2"];
	
			if ($enc_password != "") # Changing...
			{
				if (strlen($password2) < 6)
				{
					$this->Session->setFlash('Password too short. Must be 6 or more characters.');
					return; # Go straight to error page
				}
				else if ($enc_password != $enc_password2) 
				{
					$this->Session->setFlash("Passwords do not match.");
					return; # Go straight to error page
				}

				if ($this->Member->save($this->data)) {
					$this->Session->setFlash(__('Your password has been changed', true));
					#$this->redirect(array('action'=>'view'));
				} else {
					$this->Session->setFlash(__('Your password could not be saved. Please, try again.', true));
				}
			}

		} # Else display form
		$this->data["Member"] = array();
		$this->data["Member"]["member_id"] = $sessid;
		# All we really need anyway.
	}

	function view($id = null)
	{
		$current_member_id = $this->Session->read('Auth.Member.member_id');

		# If no user, default to self if logged in (else, redirect to homepage)
		if (!isset($id))
		{
			# Try own.
			$id = $current_member_id;
			#error_log("ID=$id");
			#echo "SESS=";
			#print_r($this->Session->read());
		}


		if (!isset($id)) # Redirect to homepage
		{
			$this->Session->setFlash("You have selected an invalid member page.");
			$this->redirect("/members"); # Go to homepage instead!
		} else {

			# Now read...
			# Support both numerical ids (member_id) AND usernames!
			#$member = is_numeric($id) ? $this->Member->read(null, $id) :
			$member = is_numeric($id) ? $this->Member->find("Member.member_id = '$id'") :
				$this->Member->find("Member.username = '$id' $activesql");
			$member_id = $member['Member']['member_id']; # Since url accepts names, we need id no matter what


			$type = $member['Member']['member_type'];
			$modelclass = 'MemberModelProfile';
			if ($type == 'model')
			{
				$modelclass = 'MemberModelProfile';
			} else if ($type == 'photographer') {
				$modelclass = 'MemberPhotographerProfile';
			} else if ($type == 'hair/makeup') {
				$modelclass = 'MemberHairMakeupProfile';
			} else if ($type == 'designer') {
				$modelclass = 'MemberDesignerProfile';
			} else if ($type == 'agency') {
				$modelclass = 'MemberAgencyProfile';
			}

			if (!$member['Member']['active'] && !$this->Session->read("Auth.Member.is_admin"))
			{
				$this->action = "view_suspended";
				return;
			}


			$viewing_self = ($member_id === $current_member_id);
			#error_log("VIEWING_SELF=$viewing_self, CURR=$current_member_id, MEM=$member_id");

			$this->set('member', $member); # !!!! we need this to access !!!!
			if ($type == 'model')
			{
				$this->set('member_height', $this->Member->get_height_name($member["MemberModelProfile"]["height"]));
				$this->set('member_years_old', $this->Member->get_age($member["Member"]["birthdate"]));
			}
			$this->set('member_years_experience', $this->Member->get_years_experience($member[$modelclass]["since_experience"]));
			$this->set('member_id', $member_id); # !!!! we need this to access !!!!

			$member_limits = $this->member_limits($member_id);
			$this->set("member_limits", $member_limits);


			if ($member_limits['video_enabled'] === true)
			{
				$video_thumbnail_url = null;
				if ($video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id' AND is_active = 1 AND disabled = FALSE"))
				{
					$video_thumbnail_url = $this->Youtube->getPreviewUrl($video['MemberVideo']['youtube_video_id']);
				}

				$this->set('video', $video);
				$this->set('video_thumbnail_url', $video_thumbnail_url);
			}

			$this->set("resumeExists", $this->MemberResume->hasAny("member_id = '$member_id'"));

			# Now mark 'views' flag so know another visit has occurred....
			if (!$viewing_self)
			{
				$this->Member->recursive = -1;
				$this->Member->updateAll(array("views"=>" views + 1 "), "Member.member_id = '$member_id'");
			}
		}
	}

	function latest_talk()
	{
		header('Content-Type: text/plain');
		echo "TODO. No talk available";
		exit(0);
	}

	function edit($id = null) {
		if (!$id) # Action is adjusted to have in pathinfo, NOT form...
		{
			$id = $this->Session->read('Auth.Member.member_id'); # Try own.
		}

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Member', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) { # SAVING form values....
			if (isset($this->data["Member"])) # Editing member profile...
			{
				$this->_saveMember();
			}
		}

		# *** since things are saved, (all we needed was key in form)
		# we can safely just re-read from the db!

		#if (empty($this->data)) { $this->data = array(); }
		$member = $this->Member->read(null, $id);
		$this->data["Member"] = $member["Member"];
		# etc for album, etc...

		error_log(print_r($member, true));

		$this->set('member', $member); # !!!! we need this to access !!!!
		$this->set('member_id', $id); # !!!! we need this to access !!!!
		# So we have ALL fields, even ones we cannot change....

		#$this->set("member_id", $id);

		if (isset($this->data["defaultpane"]))
		{
			$this->set("defaultpane", $this->data["defaultpane"]);
		} else {
			$this->set("defaultpane", "");
		}
	}

	function admin_edit($id = null) {
		if (!$id) # Action is adjusted to have in pathinfo, NOT form...
		{
			$id = $this->Session->read('Auth.Member.member_id'); # Try own.
		}

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Member', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) { # SAVING form values....
			if (isset($this->data["Member"])) # Editing member profile...
			{
				$this->_saveMember();
			}
		}

		# *** since things are saved, (all we needed was key in form)
		# we can safely just re-read from the db!

		#if (empty($this->data)) { $this->data = array(); }
		$member = $this->Member->read(null, $id);
		$this->data["Member"] = $member["Member"];
		# etc for album, etc...

		error_log(print_r($member, true));

		$this->set('member', $member); # !!!! we need this to access !!!!
		$this->set('member_id', $id); # !!!! we need this to access !!!!
		# So we have ALL fields, even ones we cannot change....

		#$this->set("member_id", $id);

		if (isset($this->data["defaultpane"]))
		{
			$this->set("defaultpane", $this->data["defaultpane"]);
		} else {
			$this->set("defaultpane", "");
		}

		$this->set("member_id", $id);
	}

	function _saveMember()
	{
		# Manage password change...
		# If empty string, delete key, so dont erase.
		# Else, verify matching.
		#
		# XXX TODO

		# Need to set day for since-experience so no barfing...
		if (!isset($this->data['Member']['since_experience']['day']))
		{
			$this->data['Member']['since_experience']['day'] = "01";
		}



		if ($this->Member->save($this->data)) {
			$this->Session->setFlash(__('The profile has been saved', true));
			#$this->redirect(array('action'=>'view'));
		} else {
			$this->setError('Could not save profile information.', $this->Member);
			#$this->Session->setFlash(__('The Member could not be saved. Please, try again.', true));
		}
	}


	function index() # Homepage, here so we at least have a view page...
	{
	}

	function search_advanced()
	{
		$default_member_type = isset($_GET['member_type']) ? $_GET['member_type'] : "";
		if(empty($this->data)){ $this->data = $this->expand_search_data($this->passedArgs); }

		#error_log("FORM DATA=".print_r($this->data,true));
		$member_type = isset($this->data['Member']['member_type']) ? $this->data['Member']['member_type'] : "";

		if (!$default_member_type) { $default_member_type = $member_type; }

		$this->set("default_member_type", $default_member_type);

		#error_log("MEM_TYPE=$member_type");

		$this->prepare_search_fields($member_type);
		$this->set('model',$this->Member); # So we can pretty-ify some values....
		$this->set("include_form", true);

		$this->set("search_title", "Advanced Member Search");

		if (!empty($this->data))
		{
			$form = $this->create_model_query($this->data);
			$this->set("data", $this->data);
			#error_log("DATA=".print_r($this->data,true));

			$this->set('members', $this->paginate("Member", $form));
		}

		$this->passedArgs = $this->flatten_search_data($this->data);

		if ($member_type == 'model' || $default_member_type == 'model')
		{
			$this->action = 'model_member_search_results';
			# Load: height, gender, eye, hair, ethnicity, skin
			$this->set("height_options", $this->Member->get_height());
			$this->set("gender_options", $this->MemberModelProfile->getEnumValues("gender"));
			$this->set("eye_colors", $this->MemberModelProfile->getEnumValues("eye_color"));
			$this->set("hair_colors", $this->MemberModelProfile->getEnumValues("hair_color"));
			$this->set("ethnicity_options", $this->MemberModelProfile->getEnumValues("ethnicity"));
			$this->set("skin_tones", $this->MemberModelProfile->getEnumValues("skintone"));
		} else {
			$this->action = 'member_search_results';
		}

	}

	function get_sub_array($keyparts,$v)
	{
		#error_log("JP=".print_r($keyparts,true));
		$key = array_shift($keyparts);
		#error_log("JPKEY=$key");
		if (count($keyparts))
		{
			$value = $this->get_sub_array($keyparts,$v);
		} else {
			$value = $v;
		}
		return array($key => $value);
	}

	function expand_search_data($passedArgs) # Turn Foo.bin.bar into multidimensional... For decoding passedArgs into $this->data
	{
		#error_log("EXPANDING=".print_r($passedArgs,true));
		$data = array();
		foreach ($passedArgs as $k => $v)
		{
			$keyparts = split("[.]", $k);
			$kvp = $this->get_sub_array($keyparts,$v);
			$data = array_merge($data, $kvp);
			#error_log("K=$key, V=$value");
			#$data[$key] = $value;
		}

		#error_log("DATA NOW=".print_r($data,true));

		return $data;
	}

	function flatten_search_data($data)
	{
		#error_log("FLATTENING=".print_r($data,true));
		$flat_data = array();
		foreach ($data as $model=>$model_values) { 
			if (is_array($model_values)) 
			{ # Otherwise it's some pagination crap..
				foreach($model_values as $field => $field_value)
				{
					if (is_array($field_value))
					{
						foreach($field_value as $fvk => $fvv)
						{
							$flat_data["$model.$field.$fvk"] = $fvv;
						}
					} else {
						$flat_data["$model.$field"] = $field_value;
					}
				}
			}
		}

		#error_log("FLAT=".print_r($flat_data,true));

		return $flat_data;
	}

	function search($advanced = '')
	{
		$member_type = '';

		$this->set("search_title", "Member Search");

		if (!empty($this->data))
		{
			$keyword = $this->data['Member']['keyword'];
			# Search username, fullname (firstname + lastname)

			$this->set('model',$this->Member); # So we can pretty-ify some values....
			$this->set("members", $this->paginate('Member',
				array("MATCH (username,firstname,lastname) AGAINST ('$keyword')"))); 
			# MATCH (username,firstname,lastname) AGAINST ('$keyword')

			# (probably will still have to do FULLTEXT searches with multiple keywords
			# since may want to look for "tom maly" and find "tomas maly" or partial
			# match of "thomas maly"

			# ie concatenate as "username;firstname;lastname", 
			# search against EACH keyword separately?!?!? (for partial matching)

		}



		#$this->action = 'member_search_form';
		$this->action = 'member_search_results';


	}

	function browse($member_type = '') {
		$this->Member->recursive = 0;
		$this->set('model',$this->Member);

		if($member_type == 'hair_makeup') { $member_type = 'hair/makeup'; } # alias that wont confuse urls.

		# Prepare generic fields.
		$this->prepare_search_fields($member_type);

		$this->set("search_title", "Member List");


		#echo "MT=$member_type";

		if (!isset($this->data['Member']['member_type']))
		{
			$this->data['Member']['member_type'] = $member_type;
		}

		$form = $this->create_model_query($this->data);
		$this->set('members', $this->paginate("Member", $form));

		# what we want is the form to work with paginate....

		$this->set("include_form",true);

		if ($this->data['Member']['member_type'] == 'model')
		{
			$this->action = 'model_member_search_results';
		} else {
			$this->action = 'member_search_results';
		}
	}

	function create_model_query($data)
	{
		$query = array();

		# Model.field OR Model.field.op, Model.field.0, Model.field.1
		# op being BETWEEN, <  , > , =, !=, <=, >=
		error_log(print_r($data,true));
		foreach ($data as $model => $model_data)
		{
			if (is_array($model_data)) {
				foreach($model_data as $field => $value)
				{
					if(is_array($value))
					{
						$op = "";
						$ignore = false;
						if(isset($value['op']))
						{
							$op = $value['op']; 
							unset($value['op']);
							#if ($op != 'BETWEEN' && $op != 'between') { unset($value[1]); } # Ignore.
							if ($op == 'BETWEEN' || $op == 'between')
							{
								$op = " $op ? and ?";
							} else if ($op == '') { # Ignore field...
								$ignore = true;

							} else {
								$op = " $op";
								if (is_array($value[0]) && 
									( # It's a date, so convert....
										isset($value[0]['year']) ||
										isset($value[0]['month']) ||
										isset($value[0]['day'])
									)
								)
								{
									$year = $value[0]['year'];
									$month = $value[0]['month'];
									$day = $value[0]['day'];
									if ($day == '') { $day = '1'; } 
									if ($month == '') { $month = '1'; } 
									$value = sprintf("%04d-%02d-%02d", $year, $month, $day);
								} else {
									$value = $value[0]; # Ignore second field.
								}
							}
						}
						if (!$ignore)
						{
							$query["$model.$field$op"] = $value; # May be just one value, may be two.
						}
					} else if ($value != '') { # Ignore blank fields....
						$query["$model.$field"] = $value;
					}
				}
			}
		}

		error_log("QUERY=".print_r($query,true));

		return $query;
	}

	function prepare_search_fields($member_type)
	{
		$this->set("member_types", $this->Member->getEnumValues("member_type",null,'[Any]'));
		$this->set('member_levels', $this->Member->getEnumValues('membership_level'));
		$this->set("states", $this->states_list(true));

		# Prepare fields for type.
		$this->prepare_member_type_search_fields($member_type);
	}

	function prepare_member_type_search_fields($member_type = 'model')
	{
		if ($member_type == 'model')
		{
			$this->set("genders", $this->MemberModelProfile->getEnumValues("gender"));
			$this->set("eye_colors", $this->MemberModelProfile->getEnumValues("eye_color"));
			$this->set("hair_colors", $this->MemberModelProfile->getEnumValues("hair_color"));
			$this->set("ethnicities", $this->MemberModelProfile->getEnumValues("ethnicity"));
			$this->set("skin_tones", $this->MemberModelProfile->getEnumValues("skintone"));
		} else {

		}
	}

	function model_register()
	{
		$this->set('states_values', $this->states_list());
		$this->set('gender_values', $this->MemberModelProfile->getEnumValues('gender'));

		if (!empty($this->data))
		{
			$recipient = 'ffmodels@firstfashionsite.com';
			#$recipient = 'tomas@localhost';

			$this->sendEmail($recipient, "FirstFashionSite.com Model Registration", "model_register",
				array("model"=>$this->data));

			$this->action = 'model_register_complete';
		}
	}


	function signup($member_type = '')
	{
		if (!empty($this->data))
		{
			#error_log(print_r($this->data,true));
			$email = $this->data['Member']['email'];

			# Make sure username is alphanumeric, 6+ chars.
			# Make sure password matches password2, at least 6 chars.
			$firstname = $this->data['Member']['firstname'];
			$lastname = $this->data['Member']['lastname'];

			$username = $this->data['Member']['username'];
			$enc_password = $this->data['Member']['password'];
			$password2 = $this->data['Member']['password2'];

			# BECAUSE the $password gets encoded, we need to check length
			# against password2 and compare with the encrypted version of password2
			$enc_password2 = $this->Auth->password($password2);

			if (isset($email) && $this->BannedEmail->hasAny("email = '$email'"))
			{
				$this->Session->setFlash("Email has been blacklisted. It is not allowed as a primary account email. Please choose another email.");
			}
			else if (strlen($firstname) <= 0 || strlen($lastname) <= 0)
			{
				$this->Session->setFlash('Must enter in your complete first and last names.');
			}
			else if (strlen($username) < 6)
			{
				$this->Session->setFlash('Username too short. Must be 6 or more characters.');
			}
			else if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]+$/', $username))
			{
				$this->Session->setFlash("Username must start with a letter, with letters, numbers, dashes '-', and underscores '_' allowed only.");

			}
			else if (strlen($password2) < 6)
			{
				$this->Session->setFlash('Password too short. Must be 6 or more characters.');
			}
			else if ($enc_password != $enc_password2) 
			{
				$this->Session->setFlash("Passwords do not match.");
			}
			else if (!preg_match("/^.+@.+[.].+$/", $email)) 
			{
				$this->Session->setFlash("Invalid email.");
			}
			else if (!isset($this->params['form']['terms']) || !$this->params['form']['terms'])
			{
				$this->Session->setFlash("You must agree to the Terms of Service to sign up.");
			}
			else if ($this->Member->hasAny(array('email' => $email)))
			# Verify email not already signed up....
			{
				$this->Session->setFlash('Email already in use. Choose another or <a href="/members/forgot">retrieve your password</a>.');
				# Hopefully this will display on THIS page, and not require a redirect.
			} else {
				# All good, do actual signup...
				$this->Member->create();

				# Now set registration date...
				$regdate = date('Y-m-d H:i:s');
				$this->data['Member']['registration_date'] = $regdate;
				#error_log("TRYING TO SAVE");

				if ($this->Member->save($this->data))
				{
					$this->Session->setFlash(__('Account created', true));
					# Send out email confirmation.... with username, password, email, etc.
					# XXX TODO
					$this->sendEmail($this->data, "FirstFashionSite.com Account", "account_created", array('member'=>$this->data['Member']));

					# redirect to thank you page, with login form. 
					$this->set('email', $email);
					$this->set('content_file', "content/signup_thanks");
					# under elements/content/signup_thanks.ctp
					$this->action = "login_form";
				} else {
					$this->setError("Cannot create account", $this->Member);

				}
			}
		}
		if ($member_type)
		{
			$this->data['Member']['member_type'] = $member_type;

		}

		$this->set('member_types', $this->Member->getEnumValues('member_type'));

		if ($member_type == 'agency')
		{
			$this->action = 'signup_agency';
		}


	}

	function _random_populate($data)
	{
			$output = array();

			foreach ($data as $field => $values)
			{
				if (isset($values))
				{
					if (is_array($values))
					{
						$value = $values[ rand(0, count($values)-1) ];
						$output[$field] = $value;
					} else { # Single option, set.
						$output[$field] = $values;
					}
				} # Else, ignore.
			}
			return $output;
	}

	function create_random_member($count = 1)
	{
		$sample_data = include("../sql/sample_users.php");

		for ($i = 0; $i < $count; $i++)
		{
			$member = $this->_random_populate($sample_data['Member']);

			#error_log("GENERATED MEMBER=".print_r($member,true));

			$username = $member['firstname'] . substr($member['lastname'], 0, 1); # first linit

			$this->Member->recursive = -1; # No joining at all...
			$last_member = $this->Member->find("username LIKE '$username%'", array("MAX(username) AS last_username"));

			if (count($last_member) && !empty($last_member[0]))
			{
				$last_name = $last_member[0]['last_username'];
				preg_match("/^$username(\d+)$/", $last_name, $matches);
				$ix = 0;
				if ($matches)
				{
					$ix = $matches[1];
				}

				$username .= $ix+1; # Always have no matter what i guess...
			}


			$member['username'] = $username;

			# Make sure username is unique, appending sequential number...
			$member['email'] = $member['username'] . '@' . $_SERVER['HTTP_HOST'];

			$member['password'] = $this->Auth->password('firstfashion1');

			#error_log("COMPLETED MEMBER=".print_r($member,true));

			$model = $this->_random_populate($sample_data['MemberModelProfile']);

			#error_log("GENERATED MODELPROFILE=".print_r($model,true));

			$this->Member->create();
			$this->Member->save(array('Member'=>$member));

			$member_id = $this->Member->id;

			$model['member_id'] = $member_id;

			$this->MemberModelProfile->create();
			$this->MemberModelProfile->save(array('MemberModelProfile'=>$model));

			# Now upload photo....

			$randix = rand(0, count($sample_data['MemberPhoto']['file'])-1);
			$photo_file = $sample_data['MemberPhoto']['file'][ $randix ];

			preg_match("/[.](\w+)$/", $photo_file, $matcher);
			if ($matcher) { $ext = $matcher[1]; } else { $ext = ''; }

			$photo = array(
				'member_id'=>$member_id,
				'ext'=>$ext,
				'title'=>'Primary Photo',
				'comment'=>'Taken last year',
				'is_primary'=>1,
			);

			#error_log("PHOTO INFO=".print_r($photo,true));

			$this->MemberPhoto->create();
			$this->MemberPhoto->save($photo);
			$photo_id = $this->MemberPhoto->id;

			# Now copy the file where it belongs (since cant use Upload component...)
			$photo_dest = APP . "webroot/images/members/$member_id/large";
			if (!is_dir($photo_dest)) { mkdir($photo_dest, 0755, true); }

			# Copy file over....
			#error_log("COPYING PIC FROM ../sql/$photo_file TO $photo_dest/$photo_id.$ext");
			copy("../sql/$photo_file", "$photo_dest/$photo_id.$ext");
		}

		$this->redirect("/members/view/$member_id"); # Go to page for last member created...
	}

	function chat()
	{
		#$rc = App::import('Helper', 'chat.AjaxChat');
		#error_log("RC=$rc");
		# Or helpers above be chat.AjaxChat ??? look at inner code!
	}

	function upgrade($level = 'premium') # Just the marketing page for...
	{
		$member = $this->Session->read("Auth.Member");
		$feature = isset($_REQUEST['feature']) ? $_REQUEST['feature'] : "access this feature";
		$member_id = $member['member_id'];
		$member_levels = $this->Member->getEnumValues('membership_level');
		$existing_level = $member['membership_level'];
		if ($existing_level == $level)
		{
			$this->Session->setFlash("Membership already upgraded.");
			$this->redirect("/members/edit");
		}

		$this->set("level", $level);
		$price = $this->get_membership_costs($level);
		$this->set("price", $price);
		$this->set("feature", $feature);
		$this->action = "upgrade_level_$level";

	}


	function get_membership_costs($level = 'premium')
	{
		$monthly_costs = array(
			'premium' => '4.99',
		);
		$price = $monthly_costs[$level];
		return $price;
	}

	function upgrade_checkout($level = 'premium', $callback = null)
	{
		$member = $this->Session->read("Auth.Member");
		$member_id = $member['member_id'];

		#if (isset($_REQUEST['csid']))
		#{
	        #	if (!$this->Payment->restoreSession($_REQUEST['csid']))
	        #	{
		 #   		error_log("CANT RESTORE SESSION UPON PAYPAL CALLBACK!");
	           # 		$this->redirect('/members/edit');
	            #		exit;
	        #	}
		#}

	
		$member_levels = $this->Member->getEnumValues('membership_level');
		$existing_level = $member['membership_level'];

		# For now, only supports 'basic' and 'premium'
		# ie one level of paid....
		if ($existing_level == $level)
		{
			$this->Session->setFlash("Membership already upgraded.");
			$this->redirect("/members/edit");
		}

		# Hardcode monthly costs for now....
		$price = $this->get_membership_costs($level);

		# We need to MASK calls, so we know what to do when we're done! (to db, etc)

		# XXX TODO MAY NOT PROPERLY BE DOING AS MONTHY VS ONE-TIME PAYMENT (check paypal side)

		$order = array(
			'action' => CAKE_COMPONENT_PAYPAL_ORDER_TYPE_SALE, # Sale
			'description'=> "FirstFashionSite.com $level Monthly Membership Fee", 
			'billing_description' => "$price per month",
			'total' => $price,
			'frequency' => CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_MONTHLY,
		);

		$this->Payment->setOrder($order);

		# IN _HERE_ we restore the session!!!!
		if (!$callback)
		{
			#$rc = $this->expressCheckout($callback);
			$rc = $this->recurringCheckout($callback);
		} else if ($callback == 'pay') {
			$rc = $this->recurringCheckout($callback);
		}

		if ($callback == 'pay' && $rc) # DONE!
		{
			$transaction_id = $rc;
			$this->setAction("upgrade_checkout_process", $level, $transaction_id);
			# Process from here...
		}
	}



	function upgrade_checkout_process($level, $transaction_id)
	{
		$member = $this->Session->read("Auth.Member");
		$member_id = $member['member_id'];
		$this->Member->recursive = -1;

		$today = $this->get_date();

		#$this->Member->read(null, $member_id);
		$member['membership_level'] = $level;
		$member['membership_upgrade_date'] = $today;
		$member['membership_level_transaction_id'] = $transaction_id;



		#$this->Member->set('membership_level',$level);
		#$this->Member->set('membership_upgrade_date',$today);
		#$this->Member->set('membership_level_transaction_id',$transaction_id); # So can downgrade later
		$this->Member->save(array('Member'=>$member));
		#$this->Session->write("Auth.Member", $member); # Update session.
		$this->Auth->login($member);

		# Now show thankyou page...
		$this->action = "upgrade_complete_$level";

		$this->set("level", $level);
	}

	function recurringCheckout($callback = null) # Generic such that can be moved to app_controller.php!
	{
	    if (isset($callback) && isset($_REQUEST['csid']))
	    {
	        // Restore session
	        
	        if (!$this->Payment->restoreSession($_REQUEST['csid']))
	        {
	    		$this->Session->setFlash(__('Could not complete transaction (retrieving session). Please try again.',true));
			$this->redirect("/members/edit");
	        }
	    }

	    if (!isset($callback))
	    {
		if (!$this->Payment->submitRecurringCheckout())
		{
	    		$this->Session->setFlash(__('Could not submit order: ' . $this->Payment->getError(),true));
			$this->redirect("/members/edit");
		}
	    }
	    else if ($callback == 'cancel')
	    {
	    	$this->Session->setFlash(__('Payment canceled.',true));
		$this->redirect("/members/edit");
	        #echo 'SNIFF... Why not?';
	        #exit;
	    }
	    else if ($callback == 'pay')
	    {
	        // Second call, make payment via PayPal

		$result = $this->Payment->getRecurringCheckoutResponse();
		#$result = $this->Payment->getCheckoutResponse();
		# XXX FIX HERE
	        
	        if ($result === false)
	        {
	    	    $this->Session->setFlash(__('Unable to process payment: ' . $this->Payment->getError(), true));
	   	    $this->redirect("/members/edit");
	        }
	        else # Did payment... so do post-processing....
	        {
		    $transaction_id = $result["transaction"];
		    return $transaction_id;
		    # Save transaction_id into 'sales' so can do refund, etc...
		    #$this->setAction($this->payment_process_callback, $transaction_id);
	        }
	    }
	}

	function downgrade($level = 'basic') # TO $level
	{
		# May want to add in confirm page....

		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->Member->recursive = -1;
		$member = $this->Member->read(null, $member_id); # So session not out of date from db.

		# Get profile ID
		$profile_id = $member["Member"]["membership_level_transaction_id"];

		if (!$profile_id)
		{
			$this->Session->setFlash(__('Unable to cancel membership: no reference ID found', true));
			$this->redirect("/members/edit");
		}

		# Cancel paypal, etc... needs transaction_id  in members!!!!
		# XXX TODO

		$order = array(
			'action' => CAKE_COMPONENT_PAYPAL_RECURRING_STATUS_CANCEL, # Cancel
			'note'=> "FirstFashionSite.com $level Monthly Membership Fee Cancelation", 
			'profile_id' => $profile_id,
		);

		#error_log("DOWNGRDE ORDER=".print_r($order,true));
		$this->Payment->setOrder($order);
		$result = $this->Payment->recurringUpdateCheckout();

	        if ($result === false)
	        {
	    		$this->Session->setFlash(__('Unable to cancel membership: ' . $this->Payment->getError(), true));
	        } else { # OK process.

			#
			$this->Member->set('membership_level',$level);
			$this->Member->save();
			#$this->Auth->login();
			$this->Session->write("Auth.Member.membership_level", $level);

			# Now show thankyou page...
			$this->Session->setFlash("Membership downgraded.");
		}
		$this->redirect("/members/edit");
	}

	function message($member_id = '')
	{
		if (!$member_id)
		{
			$this->setFlash("No member specified.");
			return;
		}
		$member = $this->Member->read(null, $member_id);

		$sending_member = $this->Session->read("Auth.Member");

		if (!$member || empty($member))
		{
			$this->setFlash("Invalid member specified.");
			return;
		}

		$this->set("member_id", $member_id);
		$this->set("member", $member);

		$this->layout = 'popup';

		if (!empty($this->data) && isset($this->data['message']))
		{
			$message = $this->data['message'];
			$this->sendEmail($member, "FirstFashionSite.com Private Message", "message",
				array('member'=>$member['Member'], 'message'=>$message,'sending_member'=>$sending_member));
			$this->Session->setFlash("Message sent.");
		} else {
			$this->action = 'message_form';
		}
	}

	function feature_upgrade_popup() # Page to go to for requesting upgrading...
	{
		$this->layout = 'popup';
		$feature = isset($_REQUEST['feature']) ? $_REQUEST['feature'] : "";
		if (!$feature) { $feature = 'access this feature'; }
		$this->set("feature", $feature);
	}

	function flag($id = '')
	{
		$current_is_admin = $this->Session->read("Auth.Member.is_admin");
		if ($id == '')
		{
			$this->Session->setFlash("Invalid member.");
			$this->redirect("/members/view"); 
		}
		$member = $this->Member->read(null, $id);
		$flag_count = $member['Member']['flag_count'];
		$flag_count++;

		# IF ADMIN, IMMEDIATELY DELETE....
		if ($current_is_admin || $flag_count >= $this->appconfig["max_flag_count"])
		{
			# Suspend and notify.
			$this->Member->set("flag_count", $flag_count);
			$this->Member->set("active", false);
			$this->Member->save();
			$this->sendEmail($member, "FirstFashionSite.com Inappropriate Profile Content", "profile_suspended", array("member"=>$member));
		} else { # Just save flag status.
			$this->Member->set("flag_count", $flag_count);
			$this->Member->save();
		}

		$this->set("member_id", $id);
		
	}

	function suspended() # Page to show user on how to remedy suspension
	{
	}

	function reinstate_request()
	{
		$id = $this->Session->read("Auth.Member.member_id");
		if (!$id) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member = $this->Member->read(null, $id);

		$this->sendAdminEmail("Member Reinstate Request", "reinstate_request", array("member"=>$member), $member['Member']['email']);
	}

	function admin_suspend($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member = $this->Member->read(null, $id);
		if (!$member) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member['Member']['active'] = 0;
		$this->Member->save($member);
		$this->sendEmail($member, "FirstFashionSite.com Account Suspended", "profile_suspended", array("member"=>$member));
		$this->set("member_id", $id);
		$this->set("member", $member); # For moderator nav to show up properly
		$this->close_session_by_member_id($id);
	}

	function admin_reinstate($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member = $this->Member->read(null, $id);
		if (!$member) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member['Member']['active'] = 1;
		$member['Member']['flag_count'] = 0;
		$this->Member->save($member);
		$this->sendEmail($member, "FirstFashionSite.com Account Reinstated", "profile_reinstated", array("member"=>$member));
		$this->set("member_id", $id);
		$this->set("member", $member); # For moderator nav to show up properly
	}

	function admin_delete($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$member = $this->Member->read(null, $id);

		$this->Member->del($id);
		$this->sendEmail($member, "FirstFashionSite.com Account Deleted", "profile_deleted", array("member"=>$member));
		$this->close_session_by_member_id($id);
	}

	function admin_close_session($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid Member"); $this->redirect_referer(); }
		$this->close_session_by_member_id($id);
	}


}
