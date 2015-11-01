<?php
class MemberPhotosController extends AppController {

	var $name = 'MemberPhotos';
	var $helpers = array('Html', 'Form','Thumbnail','Lightbox','Javascript','Ajax');

	var $paginate = array(
		'limit'=>6, # Since all there's room for...
		'order'=>array('is_primary'=>'DESC', 'album_order' => 'ASC','photo_id'=>'ASC'),
		# ALWAYS put primary FIRST!
	);
	var $uses = array('MemberPhoto','Member');


	function beforeFilter()
	{
		#$this->Auth->allow('view','view_primary','viewlist');
		$this->Auth->allow('view','view_primary','flag','viewlist');
		parent::beforeFilter();
	}

	function editlist() {
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->MemberPhoto->recursive = 0;
		$this->set("memberPhotos", $this->MemberPhoto->find('all',array('conditions'=>"MemberPhoto.member_id = '$member_id'",'order'=>"album_order ASC, photo_id ASC")));
		#$this->set('memberPhotos', $this->paginate('MemberPhoto',array('MemberPhoto.member_id'=>$member_id)));
		$this->set("member_id", $member_id); # For top menu to appear.

		# Show OWN photos ONLY....
	}

	function viewlist($id = null) { # DONE FOR AJAX LOAD...
		$this->layout = 'xml';
		if (!$id) {
			$this->Session->setFlash(__('Invalid Member ID.', true));
			#$this->redirect(array('action'=>'index'));
		}
		$this->MemberPhoto->recursive = -1;
		$max_photos = $this->member_limits($id,'max_photos'); # Want member limits of REQUESTED user, NOT Logged in user.
		if (isset($max_photos)) { $this->MemberPhoto->set_pagination_limit($max_photos); }

		$photos = $this->paginate('MemberPhoto', array('member_id'=>$id));
		$this->Member->recursive = -1;
		$this->set('member_id', $id);
		$member =$this->Member->read(null, $id);

		$this->set('member', $member);
		$this->set('photos', $photos);
	}

	function _scalePhoto($basefolder, $filename, $size, $ext = 'jpg')
	{
		error_log("SCALE_PHOTO=$basefolder, $filename $size, $ext");
		if ($size != 'smallmedium' && $size != 'medium' && $size != 'small') { error_log("Invalid call to _scalePhoto($basefolder, $filename, $size"); }
		$sizeto = array(
			'medium'=>array(198,206), # Since array, MUST be AT LEAST, so not short of rigid ratio
			'smallmedium'=>125,
			'small'=>85, # 85x85
		);



        	#App::import('Vendor','phpthumb',array('file'=>'phpThumb'.DS.'phpthumb.class.php'));  
		#$thumb = new phpthumb;
		#$thumb->src = "$basefolder/large/$filename";
		#$thumb->w = $w;
		#$thumb->h = $h;
		#$thumb->q = 100;
	        #$thumb->config_imagemagick_path = '/usr/bin/convert';  
	        #$thumb->config_prefer_imagemagick = true;  
	        #$thumb->config_output_format = $ext;
	        #$thumb->config_error_die_on_error = true;  
	        #$thumb->config_document_root = '';  
	        #$thumb->config_temp_directory = APP . 'tmp';  
	        #$thumb->config_cache_directory = "$basefolder/$size/";
	        #$thumb->config_cache_disable_warning = true;  
		#$thumb->cache_filename = "$basefolder/$size/$filename";

		$srcfile = "$basefolder/large/$filename";
		$dstfile = "$basefolder/$size/$filename";

		list($oldwidth, $oldheight) = getimagesize($srcfile);

		$newsize = $sizeto[$size];

		if (is_array($newsize))
		{
			$neww = $newsize[0];
			$newh = $newsize[1];
			$h2w_ratio = $oldwidth / $oldheight;

			$h = $newh; # So we can have all in a row of equal heights...
			$w = $h2w_ratio * $newh;

			##error_log("OLD=$oldwidth / $oldheight, NEWH=$newh, NEWW=$neww, H2W=$h2w_ratio, H=$h, W=$w");

			if ($w < $neww) # Not wide enough to fit....
			{
				$w = $neww;
				$h = $newh / $h2w_ratio;
				#error_log("CHANGING W=$w, H=$h");
			}
		} else {
			$h = $newsize; # So we can have all in a row of equal heights...
			$w = $oldwidth / $oldheight * $newsize;
		}

		# We want it to be NO MORE than height, NO MORE than width.
		# so, if try to fit width and bigger than height, then need to switch to height and appropriate width.
		

		if (!is_dir("$basefolder/$size")) { mkdir("$basefolder/$size", 0755, true); }

		# Generate thumbnail...
		#error_log("EXT=$ext");
		if (strtolower($ext) == 'jpg')
		{
			$src = imagecreatefromjpeg($srcfile);
		} else if (strtolower($ext) == 'gif') {
			$src = imagecreatefromgif($srcfile);
		} else if (strtolower($ext) == 'png') {
			$src = imagecreatefrompng($srcfile);
		} else {
			# Invalid....
			# BARF somehow...
			error_log("Could not generate thumbnail, unknown filetype");
			return;
		}

		if (strtolower($ext) == 'gif')
		{
			$newimg = imagecreate($w, $h);
		} else {
			$newimg = imagecreatetruecolor($w, $h);
		}

		imagecopyresampled($newimg,$src,0,0,0,0,$w,$h,$oldwidth,$oldheight); 
		#imagecopyresized($newimg,$src,0,0,0,0,$w,$h,$oldwidth,$oldheight); 
		
		# Just straight copy, maybe imagecreate original is broken?


		# Save to disk.
		if (strtolower($ext) == 'jpg')
		{
			$ok = imagejpeg($newimg, $dstfile);
		} else if (strtolower($ext) == 'gif') {
			$ok = imagegif($newimg, $dstfile);
		} else if (strtolower($ext) == 'png') {
			$ok = imagejpeg($newimg, $dstfile);
			#$ok = imagepng($newimg, $dstfile);
		}

		if (!$ok)
		{
			error_log("Could not generate thumbnail to ". $dstfile);
		}
	}

	function _scalePhoto_old($basefolder, $filename, $size, $ext = 'jpg')
	{
		#error_log("SCALE_PHOTO=$basefolder, $filename $size, $ext");
		if ($size != 'medium' && $size != 'small') { error_log("Invalid call to _scalePhoto($basefolder, $filename, $size"); }
		$sizeto = array(
			'medium'=>200, # 200x200
			'small'=>85 # 85x85
		);

		$w = $sizeto[$size];
		$h = $sizeto[$size];

        	App::import('Vendor','phpthumb',array('file'=>'phpThumb'.DS.'phpthumb.class.php'));  
		$thumb = new phpthumb;
		$thumb->src = "$basefolder/large/$filename";
		$thumb->w = $w;
		$thumb->h = $h;
		$thumb->q = 100;
	        $thumb->config_imagemagick_path = '/usr/bin/convert';  
	        $thumb->config_prefer_imagemagick = true;  
	        $thumb->config_output_format = $ext;
	        $thumb->config_error_die_on_error = true;  
	        $thumb->config_document_root = '';  
	        $thumb->config_temp_directory = APP . 'tmp';  
	        $thumb->config_cache_directory = "$basefolder/$size/";
	        $thumb->config_cache_disable_warning = true;  
		$thumb->cache_filename = "$basefolder/$size/$filename";

		if (!is_dir("$basefolder/$size")) { mkdir("$basefolder/$size", 0755, true); }

		if ($thumb->GenerateThumbnail())
		{
			$thumb->RenderToFile($thumb->cache_filename);
		} else {
			error_log("Could not generate thumbnail to ".$thumb->cache_filename);
		}
	}

	function view($size = null, $id = null) 
	{
		$this->_displayPhoto($size, $id);
		# XXX FIX SO WE PASS OWN TYPES OF PARAMETERS, view_primary PASSES OTHER...
	}

	function _displayPhoto($size = null, $id = null, $member_id = null, $is_primary = false) 
	{
		# MODIFY SO can handle given error images
		# and can handle photo_id and size
		# THIS should handle thumbnails! Not damned helper!

		#if (!$id || !$size) {
		#	$this->Session->setFlash(__('Invalid photo.', true));
		#	$this->redirect(array('action'=>'editlist'));
		#}

		# SINCE NO PHOTO, no member to cross reference!

		$photo = $this->MemberPhoto->read(null, $id);
		if (!$member_id) { $member_id = $photo["MemberPhoto"]["member_id"]; }

		$member = $this->Member->read(null, $member_id);

		if ($member['Member']['member_type'] == 'model')
		{
			$profile = $member['MemberModelProfile'];
		} else {
			#$profile = $member['MemberProfessionalProfile'];
			$profile = $member['MemberModelProfile'];
			# FOR NOW...
		}

		$genderpostfix = ($profile['gender'] == 'Female' ? '_female' : '_male'); # Default male.

		$ext = $photo["MemberPhoto"]["ext"];
		#error_log("EXT=$ext");
		if (!$ext) { $ext = "jpg"; }
		$prefix = $is_primary ? "primary" : $id;

		if (!preg_match("/[.]\w+$/", $prefix)) # Only add extension if not already there...
		{
			$relname = "$prefix.$ext";
		} else {
			$relname = $prefix;
		}

		###$this->view = 'Media';
		# BROKEN AS HELL!

		$base = APP;
		$img = "webroot/images";
		$memberfolder = "$img/members/$member_id";
		$file = "";

		$defaultfile = "default$genderpostfix.jpg";



		$defaultimage = array(
			#'large'=> "$img/members/default/large/error.jpg",
			#'medium'=> "$img/members/default/medium/error.jpg",
			#'small'=> "$img/members/default/small/error.jpg",

			'large'=> "$img/members/default/large/$defaultfile",
			'medium'=> "$img/members/default/medium/$defaultfile",
			'smallmedium'=> "$img/members/default/smallmedium/$defaultfile",
			'small'=> "$img/members/default/small/$defaultfile",
		);

		if ($size == 'large')
		{
			$file = $defaultimage['large'];
			if (file_exists("$base/$memberfolder/large/$relname"))
			{
				$file = "$memberfolder/large/$relname";
			}
		} else if ($size == 'smallmedium' || $size == 'medium' || $size == 'small') {
			# Resize if necessary.

			$file = $defaultimage[$size];
			# If large version exists and medium doesn't, scale.
			$sized = "$base/$memberfolder/$size/$relname";
			$large = "$base/$memberfolder/large/$relname";
			$default = "$base/".$defaultimage[$size];

			if (file_exists($sized) && filesize($sized))
			{
				$file = "$memberfolder/$size/$relname";
			} else if (file_exists($large) && filesize($large)) { # large exists.
				$this->_scalePhoto("$base/$memberfolder", $relname, $size, $ext);
				$file = "$memberfolder/$size/$relname";
			} else if (!file_exists($default) || !filesize($default)) { 
				# Scale large default.
				$this->_scalePhoto("$base/$img/members/default", "$defaultfile", $size, 'jpg');
				#error_log("SCALED!");
				$file = $defaultimage[$size];
			} # else, medium default exists, use THAT.
		}

		#error_log("DISPLAYING PHOTO=$file");

		$params = array(
			'id' => basename($file),
			'name' => '',
			'download' => false,
			'extension' => $ext,
			'path' => dirname("$file") . DS
		);

		#error_log("APP=".APP);

		#error_log("VIEW SETTING=".print_r($params,true));

		$this->set($params);

		$this->tm_media_view($params); # Hack until Media viewer gets fixed!
	}

	function add() {
		# Check acl's....
		$member_id = $this->Session->read('Auth.Member.member_id');
		$member = $this->Session->read('Auth.Member');

		$photo_limit = $this->member_limits($member_id, 'max_photos');

		error_log("PHOTO LIMIT=$photo_limit");

		# Only bother with limit IF CAN upgrade AND upgrade can provide MORE.....
		# (difference between limit because of account level and limit IN GENERAL)

		if ($photo_limit > 0)
		{
			$existing_total = $this->MemberPhoto->findCount("MemberPhoto.member_id = '$member_id'");
			if ($existing_total >= $photo_limit)
			{
				$this->require_upgrade("add more photos", "/member_photos/editlist");
			}
		}

		if (!empty($this->data)) {
			$errors = $this->_updatePhoto();
			if (is_array($errors)) 
			{ 
				$errors = implode("<br/> ", $errors); 
				$this->Session->setFlash($errors);
			} else {
				$this->Session->setFlash(__('The Member Photo has been saved', true));
				$this->redirect(array('action'=>'editlist'));
			}
		}
	}

	function edit($id = null)
	{
		if (!empty($this->data)) {
			$errors = $this->_updatePhoto();
			if (is_array($errors)) 
			{ 
				$errors = implode("<br/> ", $errors); 
				$this->Session->setFlash($errors);
			} else {
				$this->Session->setFlash(__('The Member Photo has been saved', true));
				$this->redirect(array('action'=>'editlist'));
			}
		}

		$memberPhoto = null;

		if ($id)
		{
			$this->data = $this->MemberPhoto->find("photo_id = '$id'");
		}
	}

	function _updatePhoto() # For edit AND add...
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		if (!empty($this->data))
		{
			$album_id = isset( $this->data["MemberPhoto"]["album_id"]) ? $this->data["MemberPhoto"]["album_id"] : 0;
			$this->data["MemberPhoto"]["member_id"] = $member_id;

			$photo_id = "";


			if ($fileobj = $this->data["MemberPhoto"]["file"])
			# Want to take a peak at extension...
			{
				error_log("FO=".print_r($fileobj,true));

				preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
				error_log("MAT=".print_r($matcher,true));
				if (isset($matcher) && count($matcher) > 0)
				{
					$ext = strtolower($matcher[1]);
					if ($ext) { $this->data["MemberPhoto"]["ext"] = $ext; }
				}
			}

			if (!isset($this->data["MemberPhoto"]["photo_id"])) # Adding...
			{
				$this->MemberPhoto->create();
			} else {
				$photo_id = isset($this->data["MemberPhoto"]["photo_id"]);
			}

			if (!$this->MemberPhoto->save($this->data))
			{
				$this->Session->setFlash(__('The photo could not be saved. Please, try again.', true));
				return;
			}

			$photo_id = $this->MemberPhoto->id; # In case adding...

			$errors = $this->_saveUpload($photo_id);
			return $errors;
		}
		return;
	}

	function _saveUpload($photo_id)
	{
		$photo = $this->MemberPhoto->find("photo_id = '$photo_id'");

		if (($fileobj = $this->data["MemberPhoto"]["file"])) { # IF uploading too...
			error_log("SAVING UPLOAD...=".print_r($fileobj,true));
			$member_id = $this->Session->read("Auth.Member.member_id");

			# Get extension from filename....
			preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
			$ext = "";
			if ($matcher && count($matcher) > 0)
			{
				$ext = strtolower($matcher[1]);
			}

			#error_log("NAME=$fileobj[name], EX=$ext");

			error_log("UPLOADINGFILEOBJ (check size!)=".print_r($fileobj,true));

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

			#error_log("DEST_DIR=$dest_dir, DEST_FILE=$dest_file");

			if (!is_dir($dest_dir))
			{
				if (!mkdir($dest_dir, 0755, true))
				{
					$this->Session->setFlash("Unable to create folder $dest_dir");
					$this->render();
				} else {
					#error_log("CREATED $dest_dir");
				}
			}

			error_log("SAVING AS $dest_path");

			$this->Upload->upload($fileobj, "$dest_dir/", $dest_file);
			$errors = $this->Upload->errors;

			# Now ERASE other sizes, so can autogenerate again...
			$small_dest_path = sprintf(APP . "/webroot/images/members/%d/small/%d.%s", $member_id, $photo_id, $ext);
			$smallmedium_dest_path = sprintf(APP . "/webroot/images/members/%d/smallmedium/%d.%s", $member_id, $photo_id, $ext);
			$medium_dest_path = sprintf(APP . "/webroot/images/members/%d/medium/%d.%s", $member_id, $photo_id, $ext);

			# Instead of removing old images, we should SYNC (since timestamp of an empty file may be bogus)
			#$this->copy_file($dest_path, $small_dest_path);
			#$this->copy_file($dest_path, $smallmedium_dest_path);
			#$this->copy_file($dest_path, $medium_dest_path);

			$relname = "$photo_id.$ext";
			$base = APP;
			$memberfolder = "/webroot/images/members/$member_id";

			$this->_scalePhoto("$base/$memberfolder", $relname, "small", $ext);
			$this->_scalePhoto("$base/$memberfolder", $relname, "smallmedium", $ext);
			$this->_scalePhoto("$base/$memberfolder", $relname, "medium", $ext);

			# Now if the image is the PRIMARY, update THAT....
			error_log("PRIM=".$photo['MemberPhoto']['is_primary']);
			if ($photo['MemberPhoto']['is_primary'])
			{
				$this->_copy_primary($member_id, $photo_id);
			}


			#unlink($small_dest_path);
			#unlink($smallmedium_dest_path);
			#unlink($medium_dest_path);

			return $errors;
		}
	}

	function getPhotoList($mode = 'view', $id = null) 
	# For updating list when changes made (could be photo changing, adding, etc)
	{
		# Can also be used in getPhotoList/ID syntax for getting others' photos.
		$member_id = $this->Session->read("Auth.Member.member_id");
		if (!$id && $member_id) { $id = $member_id; }
		if (!$id) { error_log("No member specified and not logged in!"); }

		$memberPhotos = array();
		if ($id)
		{
			#error_log("ID=$id");
			$memberPhotos = $this->MemberPhoto->find('all',array('conditions'=>"MemberPhoto.member_id = '$id'"));
			#error_log("MP=".print_r($memberPhotos,true));
		}
		$this->set("mode", $mode);
		$this->set("memberPhotos", $memberPhotos);
		$this->layout = 'ajax';
	}

	function resort() # For rearranging.
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		if (!$member_id)
		{
			$this->setFlash("Not logged in.");
			error_log("Not logged in...");
			return;
		}
		error_log("GOT=".print_r($this->params['form'],true));
		$this->MemberPhoto->recursive = -1;
		$order = $this->params['form']['member_album_sortable'];
		error_log("MEMBER=$member_id, ORDER=$order, COUNT=".count($order));
		if ($order && count($order))
		{
			error_log("RESORTING...");
			foreach ($order as $album_order => $photo_id)
			{
				error_log("CHANGING PHOTO_ID=$photo_id, ORDER=$album_order");
				$photo = $this->MemberPhoto->find("photo_id = '$photo_id' AND member_id = '$member_id'");
				error_log("PHOTO=".print_r($photo,true));
				if ($photo)
				{
					error_log("SAVING...");
					$photo['MemberPhoto']['album_order'] = $album_order;
					#$this->MemberPhoto->set("album_order", $album_order);
					$this->MemberPhoto->save($photo);
				}
			}
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid photo', true));
			$this->redirect(array('action'=>'editlist'));
		}
		if ($this->MemberPhoto->del($id)) {
			$this->Session->setFlash(__('Photo deleted', true));
			$this->redirect(array('action'=>'editlist'));
		}
	}

	function view_primary($size = null, $member_id = null)
	{
		if (!$size) { $size = 'large'; }
		if ($member_id) # REMOVE EXTENSION if there (as needed by shadowbox)
		{
			$member_id = preg_replace("/[.]\w+$/", "", $member_id);
		}
		error_log("MEMBER_ID=$member_id");
		if (!$member_id) { $member_id = $this->Session->read("Auth.Member.member_id"); }
		$primary = $this->MemberPhoto->find('first',array('conditions'=>array("MemberPhoto.member_id" => $member_id, "MemberPhoto.is_primary" => 1)));
		$primary_photo_id = (isset($primary) ? $primary["MemberPhoto"]["photo_id"] : null);

		$this->_assert_primary_path($member_id, $primary_photo_id);

		#error_log("PRIMRY=$primary");

		#error_log(print_r($primary, true));

		# If primary doesnt exist, will show default....
		$this->_displayPhoto($size, $primary_photo_id, $member_id, true);
		#$this->_displayPhoto($size, $primary_photo_id, $member_id);
	}

	function _assert_primary_path($member_id, $id)
	{
		$photo = $this->MemberPhoto->find("photo_id = '$id'");
		# Now save file path.
		$base = APP;
		$img = "webroot/images";
		$memberfolder = "$base/$img/members/$member_id";
		$ext = $photo['MemberPhoto']['ext'];
		if (!$ext) { $ext = 'jpg'; }

		#error_log("ASSERT=$memberfolder/large/primary.$ext");

		if (!file_exists("$memberfolder/large/primary.$ext"))
		{
			$this->copy_file("$memberfolder/large/$id.$ext", "$memberfolder/large/primary.$ext", $this->get_default_image_path($member_id, "large"));
			$this->copy_file("$memberfolder/small/$id.$ext", "$memberfolder/small/primary.$ext", $this->get_default_image_path($member_id, "small"));
			$this->copy_file("$memberfolder/smallmedium/$id.$ext", "$memberfolder/smallmedium/primary.$ext", $this->get_default_image_path($member_id, "smallmedium"));
			$this->copy_file("$memberfolder/medium/$id.$ext", "$memberfolder/medium/primary.$ext", $this->get_default_image_path($member_id, "medium"));
		}
	}

	function get_default_image_path($member_id, $size)
	{
		$member = $this->Member->find("Member.member_id = '$member_id'");
		$member_type_class = $this->Member->get_member_type_class($member_id);
		$gender = (isset($member[$member_type_class]['gender']) && $member[$member_type_class]['gender'] == 'Female') ? 'female' : 'male';
		$base = APP;
		$img = "webroot/images";
		return "$base/$img/members/default/$size/default_$gender.jpg";
	}

	function _copy_primary($member_id, $id)
	{
		$photo = $this->MemberPhoto->find("photo_id = '$id'");
		# Now save file path.
		$base = APP;
		$img = "webroot/images";
		$memberfolder = "$base/$img/members/$member_id";
		$ext = $photo['MemberPhoto']['ext'];

		$this->copy_file("$memberfolder/large/$id.$ext", "$memberfolder/large/primary.$ext", $this->get_default_image_path($member_id, "large"));
		$this->copy_file("$memberfolder/small/$id.$ext", "$memberfolder/small/primary.$ext", $this->get_default_image_path($member_id, "small"));
		$this->copy_file("$memberfolder/smallmedium/$id.$ext", "$memberfolder/smallmedium/primary.$ext", $this->get_default_image_path($member_id, "smallmedium"));
		$this->copy_file("$memberfolder/medium/$id.$ext", "$memberfolder/medium/primary.$ext", $this->get_default_image_path($member_id, "medium"));
	}

	function set_primary($id = null)
	{
		$member_id = $this->Session->read("Auth.Member.member_id");

		if (!$id) {
			$this->Session->setFlash(__('Invalid photo', true));
			$this->redirect(array('action'=>'editlist'));
		}
		$this->MemberPhoto->updateAll(array('is_primary'=>0),array('is_primary'=>1));
		$this->MemberPhoto->id = $id;

		if ($this->MemberPhoto->saveField('is_primary', 1))
		{
			$this->_copy_primary($member_id, $id);

			$this->Session->setFlash(__('Photo set as primary', true));
			#$this->redirect("/member_photos/edit/$id");
			$this->redirect("/member_photos/editlist");
		} else {
			$this->Session->setFlash(__('Unable to set photo as primary', true));
			$this->redirect("/member_photos/edit/$id");
		}
	}

	function flag($id = '')
	{
		$current_is_admin = $this->Session->read("Auth.Member.is_admin");

		if ($id == '')
		{
			$this->Session->setFlash("Invalid photo.");
			$this->redirect("/members/view"); 
		}
		$photo = $this->MemberPhoto->read(null, $id);

		$member_id = $photo['MemberPhoto']['member_id'];
		$member = $photo['Member'];

		error_log("MEMBER_ID=$member_id, MEMZER=$member");

		$flag_count = $photo['MemberPhoto']['flag_count'];
		$flag_count++;

		# IF ADMIN, IMMEDIATELY DELETE....
		if ($current_is_admin || $flag_count >= $this->appconfig["max_flag_count"])
		{
			# Delete and notify.
			$this->sendEmail($member, "FirstFashionSite.com Inappropriate Photo Content", "photo_removed", array("photo"=>$photo));
			$this->MemberPhoto->del($id);
		} else { # Just save flag status.
			$this->MemberPhoto->set("flag_count", $flag_count);
			$this->MemberPhoto->save();
		}

		$this->set("member_id", $member_id);
		
	}






	###########################################


	function admin_index() {
		$this->MemberPhoto->recursive = 0;
		$this->set('memberPhotos', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid MemberPhoto.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('memberPhoto', $this->MemberPhoto->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->MemberPhoto->create();
			if ($this->MemberPhoto->save($this->data)) {
				$this->Session->setFlash(__('The MemberPhoto has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The MemberPhoto could not be saved. Please, try again.', true));
			}
		}
		$members = $this->MemberPhoto->Member->find('list');
		$this->set(compact('members'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid MemberPhoto', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->MemberPhoto->save($this->data)) {
				$this->Session->setFlash(__('The MemberPhoto has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The MemberPhoto could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MemberPhoto->read(null, $id);
		}
		$members = $this->MemberPhoto->Member->find('list');
		$this->set(compact('members'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for MemberPhoto', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MemberPhoto->del($id)) {
			$this->Session->setFlash(__('MemberPhoto deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
