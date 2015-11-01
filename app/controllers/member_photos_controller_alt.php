<?php
class MemberPhotosController extends AppController {

	var $name = 'MemberPhotos';
	var $helpers = array('Html', 'Form','Thumbnail','Lightbox','Ajax');

	function beforeFilter()
	{
		$this->Auth->allow('view','view_primary','getPhotoList');
		parent::beforeFilter();
	}

	function editlist() {
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->MemberPhoto->recursive = 0;
		$this->set("memberPhotos", $this->MemberPhoto->find('all',array('conditions'=>"MemberPhoto.member_id = '$member_id'")));
		#$this->set('memberPhotos', $this->paginate('MemberPhoto',array('MemberPhoto.member_id'=>$member_id)));
		$this->set("member_id", $member_id); # For top menu to appear.

		# Show OWN photos ONLY....
	}

	function _scalePhoto($basefolder, $filename, $size, $ext = 'jpg')
	{
		error_log("SCALE_PHOTO=$basefolder, $filename $size, $ext");
		if ($size != 'medium' && $size != 'small') { error_log("Invalid call to _scalePhoto($basefolder, $filename, $size"); }
		$sizeto = array(
			'medium'=>200, # 200x200
			'small'=>85 # 85x85
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

		$h = $sizeto[$size]; # So we can have all in a row of equal heights...
		$w = $oldwidth / $oldheight * $sizeto[$size]; 

		if (!is_dir("$basefolder/$size")) { mkdir("$basefolder/$size", 0755, true); }

		# Generate thumbnail...
		error_log("EXT=$ext");
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
		error_log("SCALE_PHOTO=$basefolder, $filename $size, $ext");
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

	function _displayPhoto($size = null, $id = null) 
	{
		# MODIFY SO can handle given error images
		# and can handle photo_id and size
		# THIS should handle thumbnails! Not damned helper!

		#if (!$id || !$size) {
		#	$this->Session->setFlash(__('Invalid photo.', true));
		#	$this->redirect(array('action'=>'editlist'));
		#}

		$photo = $this->MemberPhoto->read(null, $id);
		$member_id = $photo["MemberPhoto"]["member_id"];
		$ext = $photo["MemberPhoto"]["ext"];
		error_log("EXT=$ext");
		if (!$ext) { $ext = "jpg"; }
		$relname = "$id.$ext";

		$this->view = 'Media';

		$base = APP;
		$img = "webroot/images";
		$memberfolder = "$img/members/$member_id";
		$file = "";

		$defaultimage = array(
			#'large'=> "$img/members/default/large/error.jpg",
			#'medium'=> "$img/members/default/medium/error.jpg",
			#'small'=> "$img/members/default/small/error.jpg",

			'large'=> "$img/members/default/large/default.jpg",
			'medium'=> "$img/members/default/medium/default.jpg",
			'small'=> "$img/members/default/small/default.jpg",
		);

		if ($size == 'large')
		{
			$file = $defaultimage['large'];
			if (file_exists("$base/$memberfolder/large/$relname"))
			{
				$file = "$memberfolder/large/$relname";
			}
		} else if ($size == 'medium' || $size == 'small') {
			# Resize if necessary.

			$file = $defaultimage[$size];
			# If large version exists and medium doesn't, scale.
			if (file_exists("$base/$memberfolder/$size/$relname"))
			{
				$file = "$memberfolder/$size/$relname";
			} else if (file_exists("$base/$memberfolder/large/$relname")) { # large exists.
				$this->_scalePhoto("$base/$memberfolder", $relname, $size, $ext);
				$file = "$memberfolder/$size/$relname";
			} else if (!file_exists("$base/".$defaultimage[$size])) { 
				# Scale large default.
				$this->_scalePhoto("$base/$img/members/default", "default.jpg", $size, 'jpg');
				error_log("SCALED!");
				$file = $defaultimage[$size];
			} # else, medium default exists, use THAT.
		}

		error_log("DISPLAYING PHOTO=$file");

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
	}

	function add() {
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
			$album_id = $this->data["MemberPhoto"]["album_id"];
			$this->data["MemberPhoto"]["member_id"] = $member_id;

			$photo_info = $this->data['MemberPhoto'];
			# contains 'photo_id', 'album_id', 'file' (path), 'ext' (auto gen from path)

			$errors = $this->MemberPhoto->upload($photo_info);
			return $errors;
		}
		return;
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
			error_log("ID=$id");
			$memberPhotos = $this->MemberPhoto->find('all',array('conditions'=>"MemberPhoto.member_id = '$id'"));
			#error_log("MP=".print_r($memberPhotos,true));
		}
		$this->set("mode", $mode);
		$this->set("memberPhotos", $memberPhotos);
		$this->layout = 'ajax';
	}

	function sortPhoto() # For rearranging.
	{
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
		if (!$member_id) { $member_id = $this->Session->read("Auth.Member.member_id"); }
		$primary = $this->MemberPhoto->find('first',array('conditions'=>array("MemberPhoto.member_id" => $member_id, "MemberPhoto.is_primary" => 1)));
		$primary_photo_id = (isset($primary) ? $primary["MemberPhoto"]["photo_id"] : null);

		error_log("PRIMRY=$primary");

		error_log(print_r($primary, true));

		# If primary doesnt exist, will show default....
		$this->_displayPhoto($size, $primary_photo_id);
	}

	function set_primary($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid photo', true));
			$this->redirect(array('action'=>'editlist'));
		}
		$this->MemberPhoto->updateAll(array('is_primary'=>0),array('is_primary'=>1));
		$this->MemberPhoto->id = $id;
		if ($this->MemberPhoto->saveField('is_primary', 1))
		{
			$this->Session->setFlash(__('Photo set as primary', true));
			#$this->redirect("/member_photos/edit/$id");
			$this->redirect("/member_photos/editlist");
		} else {
			$this->Session->setFlash(__('Unable to set photo as primary', true));
			$this->redirect("/member_photos/edit/$id");
		}
	}


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
