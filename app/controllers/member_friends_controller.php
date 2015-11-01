<?php
class MemberFriendsController extends AppController {

	var $name = 'MemberFriends';
	var $helpers = array('Html', 'Form');
	var $uses = array('MemberFriend','Member');

	var $paginate = array(
		'limit'=>3, # Since all there's room for...
		'order'=>array('friend_id' => 'DESC'),
	);

	function beforeFilter()
	{
		$this->Auth->allow('view'); # Others, ie 'add' restricted.
		parent::beforeFilter();
	}

	function index() {
		$this->MemberFriend->recursive = 0;
		$this->set('memberFriends', $this->paginate());
	}

	function view($id = null) { # DONE FOR AJAX LOAD...
		# HOW do ajax pagination ??? there IS 'paginate'....
		$this->layout = 'xml';
		if (!$id) {
			$this->Session->setFlash(__('Invalid Member ID.', true));
			#$this->redirect(array('action'=>'index'));
		}
		$this->MemberFriend->recursive = 0;
		$friends = $this->paginate('MemberFriend', array("owner_member_id"=>$id,"approved"=>1));
		#$friends = $this->MemberFriend->findAll("owner_member_id = '$id'", array(), 'ORDER BY friend_id DESC');
		if (count($friends))
		{
			foreach($friends as &$friend) # Do formatting on friend info...
			{
				$friend['Friend']['calculated_age'] = $this->Member->get_age($friend['Friend']['birthdate']);
			}
		}
		$this->Member->recursive = -1;
		$member = $this->Member->find("member_id = '$id'");
		$this->set('member', $member);
		$this->set('friends', $friends);
	}

	function approve($owner_id = null)
	{
		if (!$owner_id) {
			$this->Session->setFlash(__('Invalid Member ID.', true));
			$this->redirect($_SERVER['HTTP_REFERER']); # Go back.
		}

		$owner = $this->Member->read(null, $owner_id);

		$friend_id = $this->Session->read("Auth.Member.member_id");
		$friend = $this->Session->read("Auth");

		error_log("FINDING FRIEND=$friend_id, OWNER=$owner_id");

		$member_friend = $this->MemberFriend->find("friend_member_id = '$friend_id' AND owner_member_id = '$owner_id'");

		$member_friend['MemberFriend']['approved'] = 1;

		if ($this->MemberFriend->save($member_friend)) {
			$this->Session->setFlash(__("You have been added to this member's Friends List", true));
		} else {
			$this->Session->setFlash(__("Unable to add you to this member's Friends List. Please, try again.", true));
		}
		$this->sendEmail($owner, "FirstFashionSite.com Your Friend Request Has Been Approved", "friend_approved", array('owner'=>$owner,'member'=>$owner,'friend'=>$friend));

		#$owners = $this->MemberFriend->Owner->find('list');
		#$friends = $this->MemberFriend->Friend->find('list');
		#$this->set(compact('owners', 'friends'));

		$this->redirect(array('controller'=>'members','action'=>'view', $owner_id));
		# DEFAULT...

	}

	function reject($owner_id = null)
	{
		if (!$owner_id) {
			$this->Session->setFlash(__('Invalid Member ID.', true));
			$this->redirect($_SERVER['HTTP_REFERER']); # Go back.
		}

		$owner = $this->Member->read(null, $owner_id);

		$friend_id = $this->Session->read("Auth.Member.member_id");
		$friend = $this->Session->read("Auth");

		$member_friend = $this->MemberFriend->find("friend_member_id = '$friend_id' AND owner_member_id = '$owner_id'");

		if ($this->MemberFriend->del($member_friend['MemberFriend']['member_friend_id']))
		{
			$this->Session->setFlash(__("This member's Friend request has been denied", true));
		} else {
			$this->Session->setFlash(__("Unable to reject this member's Friend request. Please, try again.", true));
		}
		$this->sendEmail($owner, "FirstFashionSite.com Your Friend Request Has Been DENIED", "friend_denied", array('owner'=>$owner,'member'=>$owner,'friend'=>$friend));

		#$owners = $this->MemberFriend->Owner->find('list');
		#$friends = $this->MemberFriend->Friend->find('list');
		#$this->set(compact('owners', 'friends'));

		$this->redirect(array('controller'=>'members','action'=>'view', $owner_id));
		# DEFAULT...

	}

	function add($friend_id = null) {
		if (!$friend_id) {
			$this->Session->setFlash(__('Invalid Member ID.', true));
			$this->redirect($_SERVER['HTTP_REFERER']); # Go back.
		}

		$friend = $this->Member->read(null, $friend_id);

		# Ensure logged in!

		$owner_id = $this->Session->read("Auth.Member.member_id");
		$owner = $this->Session->read("Auth");

		$this->data["MemberFriend"] = array(
			"friend_member_id" => $friend_id,
			"owner_member_id" => $owner_id,
			"approved" => false,
		);

		if (!empty($this->data)) {
			$this->MemberFriend->create();
			if ($this->MemberFriend->save($this->data)) {
				$this->Session->setFlash(__('The member has been notified as per your request, and will approve or deny it shortly.', true));
			} else {
				$this->Session->setFlash(__('The member could not be added to your Friends list. Please, try again.', true));
			}
			$this->sendEmail($friend, "FirstFashionSite.com You Have Been Requested To Be Added As a Friend", "friend_request", array('owner'=>$owner,'member'=>$friend,'friend'=>$friend));
		}
		#$owners = $this->MemberFriend->Owner->find('list');
		#$friends = $this->MemberFriend->Friend->find('list');
		#$this->set(compact('owners', 'friends'));

		$this->redirect(array('controller'=>'members','action'=>'view', $friend_id));
		# DEFAULT...
	}

	function delete($friend_member_id = null) {
		if (!$friend_member_id) {
			$this->Session->setFlash(__('Invalid ID for Friend', true));
			$this->redirect($_SERVER['HTTP_REFERER']);
			#array('action'=>'index'));
		}
		$owner_id = $this->Session->read("Auth.Member.member_id");
		if ($this->MemberFriend->deleteAll(array("owner_member_id"=>$owner_id, "friend_member_id"=>$friend_member_id))) {
			$this->Session->setFlash(__('Member has been removed from Friends list', true));
		}
		$this->redirect(array('controller'=>'members','action'=>'view', $friend_member_id));
	}

}
?>
