<?php


class MemberResumesController extends AppController
{
	# class vars
	var $uses = array('MemberResume','Member');

	function beforeFilter()
	{
		#$this->Auth->allow('home', 'forgot','signup','view','index','browse','search'); # Anonymous pages....
		$this->Auth->allow('view');
		parent::beforeFilter();
	}

	function view($member_id = '') # Only supports one resume.
	{
		if (!$member_id)
		{
			$this->Session->setFlash('Invalid Member specified.');
			$this->redirect('/members'); # Don't know where else to go!
		}
		$file = $this->MemberResume->find("member_id = '$member_id'");
		if (!$file)
		{
			$this->Session->setFlash('No resume available.');
			$this->redirect("/members/view/$member_id"); 
		}

		$this->Member->recursive = -1;
		$member = $this->Member->read(null, $member_id);

		# Generate filename from member info..
		$ext = $file['MemberResume']['ext'];
		if (!$ext) { $ext = 'doc'; } # Default....

		$filename = "FirstFashion-" . $member['Member']['firstname'] . '-' . $member['Member']['lastname'] . '-Portfolio.' . $ext;

	    	header('Content-type: ' . $file['MemberResume']['mimetype']);
		header('Content-Disposition: attachment; filename='.$filename);
		echo base64_decode($file['MemberResume']['data']);

		exit();
	}

	function delete()
	{
		$id = $this->Session->read("Auth.Member.member_id");
		$this->MemberResume->deleteAll("MemberResume.member_id = '$id'");
		$this->Session->SetFlash("Resume deleted.");
		$this->redirect(array('controller'=>'member_model_profiles', 'action'=>'edit'));
	}

}

