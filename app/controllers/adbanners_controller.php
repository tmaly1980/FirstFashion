<?php
class AdbannersController extends AppController {

	var $name = 'Adbanners';
	var $helpers = array('Html', 'Form','Thumbnail','Lightbox','Javascript','Ajax');

	var $paginate = array(
		#'limit'=>6, # Since all there's room for...
		#'order'=>array('is_primary'=>'DESC', 'album_order' => 'ASC','photo_id'=>'ASC'),
		# ALWAYS put primary FIRST!
	);
	var $uses = array('Adbanner');


	function beforeFilter()
	{
		$this->Auth->allow('viewlist', 'view');
		parent::beforeFilter();
	}

	function admin_editlist($section) {
		$this->Adbanner->recursive = 0;
		$this->set("section", $section);
		$this->set("adbanners", $this->Adbanner->find('all',array('conditions'=>"Adbanner.section = '$section'",'order'=>"adbanner_order ASC, adbanner_id ASC")));
	}

	function viewlist($section = null, $limit = null) { # AJAX viewing of list.
		$this->layout = 'xml';
		if (!$section) {
			$this->Session->setFlash(__('Unable to load ad banners, invalid section.', true));
		}
		$this->Adbanner->recursive = -1;
		$order = "adbanner_order ASC";
		if ($limit > 0)
		{
			$order = "RAND()";
		}
		$adbanners = $this->Adbanner->findAll("section = '$section' AND disabled = FALSE",null,$order, $limit);

		$this->set('section', $section);
		$this->set('adbanners', $adbanners);
	}

	function view($id = null)  # For display of image itself, (someday use for impression tracking)
	{
		$photo = $this->Adbanner->read(null, $id);
		$ext = $photo["Adbanner"]["ext"];
		if (!$ext) { $ext = "jpg"; }

		$base = APP;
		$file = "$base/webroot/images/ads/$id.$ext";

		#error_log("F=$file");

		$params = array(
			'id' => basename($file),
			'name' => '',
			'download' => false,
			'extension' => $ext,
			'path' => dirname("$file") . DS
		);

		$this->set($params);

		$this->tm_media_view($params); # Hack until Media viewer gets fixed!
	}

	function admin_add($section = '') {
		if (!empty($this->data)) {
			$errors = $this->_updatePhoto();
			$section = $this->data['Adbanner']['section'];
			if (is_array($errors)) 
			{ 
				$errors = implode("<br/> ", $errors); 
				$this->Session->setFlash($errors);
			} else {
				$this->Session->setFlash(__('The Ad Banner has been saved', true));
				$this->redirect(array('action'=>'editlist',$section));
			}
		}
		$this->set("section", $section);
		$this->data["Adbanner"]["section"] = $section;
	}

	function admin_edit($id = null)
	{
		if (!empty($this->data)) {
			$errors = $this->_updatePhoto();
			$section = $this->data['Adbanner']['section'];
			if (is_array($errors)) 
			{ 
				$errors = implode("<br/> ", $errors); 
				$this->Session->setFlash($errors);
			} else {
				$this->Session->setFlash(__('The Ad Banner has been saved', true));
				$this->redirect(array('action'=>'editlist',$section));
			}
		}

		$memberPhoto = null;

		if ($id)
		{
			$this->data = $this->Adbanner->find("adbanner_id = '$id'");
		}
	}

	function _updatePhoto() # For edit AND add...
	{
		if (!empty($this->data))
		{
			$section = $this->data["Adbanner"]["section"];

			$adbanner_id = "";

			error_log(print_r($this->data['Adbanner']['file'],true));

			if ($fileobj = $this->data["Adbanner"]["file"])
			# Want to take a peak at extension...
			{
				if ($fileobj['name'] != "")
				{
					preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
					$ext = $matcher[1];
					if ($ext) { $this->data["Adbanner"]["ext"] = $ext; }
				} else {
					return; # NOT uploading...
				}
			} else { 
				return; # NOT uploading....
			}

			if (!isset($this->data["Adbanner"]["adbanner_id"])) # Adding...
			{
				$this->Adbanner->create();
			} else {
				$adbanner_id = $this->data["Adbanner"]["adbanner_id"];
			}

			if (!$this->Adbanner->save($this->data))
			{
				$this->Session->setFlash(__('The ad banner could not be saved. Please, try again.', true));
				return;
			}

			$adbanner_id = $this->Adbanner->id; # In case adding...

			$errors = $this->_saveUpload($adbanner_id);
			return $errors;
		}
		return;
	}

	function _saveUpload($adbanner_id)
	{
		if (($fileobj = $this->data["Adbanner"]["file"])) { # IF uploading too...

			# Get extension from filename....
			preg_match("/[.](\w+)$/", $fileobj["name"], $matcher);
			$ext = $matcher[1];

			if ($fileobj["size"] < 1024) # Probably invalid!
			{
				return array("Invalid image. File size too small.");
			}

			# Now save file to disk...
			$dest_path = sprintf(APP . "/webroot/images/ads/%d.%s",
				$adbanner_id,
				$ext
			);

			$dest_dir = dirname($dest_path);
			$dest_file = basename($dest_path);

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

			$this->Upload->upload($fileobj, "$dest_dir/", $dest_file);
			$errors = $this->Upload->errors;

			return $errors;
		}
	}

	function admin_resort($section) # For rearranging.
	{
		#exit(0);
		$this->layout = 'xml';
		$this->Adbanner->recursive = -1;
		if (empty($this->params['form'])) { return; }
		$order = $this->params['form']['adbanner_sortable'];
		error_log(print_r($order,true));

		if ($order && count($order))
		{
			error_log("RESORTING...");
			foreach ($order as $adbanner_order => $adbanner_id)
			{
				error_log("CHANGING PHOTO_ID=$adbanner_id, ORDER=$adbanner_order, ID=$adbanner_id");
				$adbanner = $this->Adbanner->find("adbanner_id = '$adbanner_id' AND section = '$section'");
				if ($adbanner)
				{
					error_log("SAVING...");
					$adbanner['Adbanner']['adbanner_order'] = $adbanner_order;
					#$this->Adbanner->set("album_order", $album_order);
					$this->Adbanner->save($adbanner);
				}
			}
		}
	}

	function admin_delete($section, $id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ad banner', true));
			$this->redirect(array('action'=>'editlist'));
		}
		$this->Adbanner->read(null, $id);
		if ($this->Adbanner->del($id)) {
			# REMOVE FROM DISK TOO???? (someday)
			$this->Session->setFlash(__('Ad banner deleted', true));
			$this->redirect(array('action'=>'editlist',$section));
		}
	}

}
?>
