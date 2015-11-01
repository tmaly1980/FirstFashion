<?php
class BannedEmailsController extends AppController {

	var $name = 'BannedEmails';
	var $helpers = array('Html', 'Form');

	function admin_index() {
		$this->BannedEmail->recursive = 0;
		$this->set('bannedEmails', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid BannedEmail.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('bannedEmail', $this->BannedEmail->read(null, $id));
	}

	function admin_add($email = '') {
		if ($email != '')
		{
			$this->data['BannedEmail']['email'] = $email;
		}
		if (!empty($this->data)) {
			$this->BannedEmail->create();
			if ($this->BannedEmail->save($this->data)) {
				$this->Session->setFlash(__('The Email has been added to the banlist', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Email could not be added to the banlist. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid BannedEmail', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->BannedEmail->save($this->data)) {
				$this->Session->setFlash(__('The BannedEmail has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The BannedEmail could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BannedEmail->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for BannedEmail', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BannedEmail->del($id)) {
			$this->Session->setFlash(__('Banned email removed', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
