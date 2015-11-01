<?php


class MemberMessagesController extends AppController
{
	# class vars
	var $components = array('Auth', 'RequestHandler');
	var $uses = array('Member','MemberMessage','MemberSentMessage');
	var $helpers = array('Thumbnail','Ajax','Time');

	var $paginate = array(
		'order' => "sent_time DESC",

	);

	function beforeFilter()
	{
		#$this->Auth->allow();
		#$this->Auth->allow('defaultaction','home', 'chat','forgot','signup','view','index','browse','search','create_random_member','newest_fashions','spotlight'); # Anonymous pages....
		parent::beforeFilter();
		$this->Auth->deny('*');
		# Unset all access AFTER parent allows by default.
	}

	function beforeRender() # Global vars.. across all views...
	{
		#$this->layout = 'popup';
		parent::beforeRender();
	}

	function index()
	{
		$this->setAction("inbox");
	}

	function inbox()
	{
		if (!empty($this->data))
		{
			# Process delete messages...
			if (isset($this->data['action']) && $this->data['action'] == 'Delete Selected Messages')
			{
				foreach($this->data['MemberMessage']['msg_id'] as $msg_id => $checked)
				{
					if ($checked)
					{
						$this->MemberMessage->del($msg_id);
					}
				}
			}
		}

		$this->MemberMessage->recursive = 0;
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->set("messages", $this->paginate('MemberMessage',array("member_id_to"=>$member_id)));
		$this->set("member_id", $member_id);

	}

	function sentbox()
	{
		error_log(print_r($this->data,true));
		if (!empty($this->data))
		{
			# Process delete messages...
			if (isset($this->data['action']) && $this->data['action'] == 'Delete Selected Messages')
			{
				foreach($this->data['MemberSentMessage']['msg_id'] as $msg_id => $checked)
				{
					error_log("MSG=$msg_id, CH=$checked");
					if ($checked)
					{
						$this->MemberSentMessage->del($msg_id);
					}
				}
			}
		}

		$this->MemberMessage->recursive = 0;
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->set("messages", $this->paginate('MemberSentMessage',array("member_id_from"=>$member_id)));
		$this->set("member_id", $member_id);

	}

	function view($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid message."); $this->redirect("/member_messages/index"); }
		$member_id = $this->Session->read("Auth.Member.member_id");
		$message = $this->MemberMessage->find("member_id_to = '$member_id' AND msg_id = '$id'");
		if (!$message)
		{
			$this->Session->setFlash("Invalid message.");
			$this->redirect("/member_messages/index");

		}
		$message['MemberMessage']['is_read'] = 1;
		$this->MemberMessage->save($message); # Mark as read.

		$this->set("message", $message);
		$this->set("member_id", $member_id);
	}

	function view_sent($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid message."); $this->redirect("/member_messages/index"); }
		$member_id = $this->Session->read("Auth.Member.member_id");
		$message = $this->MemberSentMessage->find("member_id_from = '$member_id' AND msg_id = '$id'");
		if (!$message)
		{
			$this->Session->setFlash("Invalid message.");
			$this->redirect("/member_messages/index");

		}
		$message['MemberSentMessage']['is_read'] = 1;
		$this->MemberMessage->save($message); # Mark as read.

		$this->set("message", $message);
		$this->set("member_id", $member_id);
	}

	function send($member_id = '', $re_msg_id = '')
	{
		#$this->layout = 'popup';
		if (!$member_id)
		{
			$this->setFlash("No member specified.");
			return;
		}
		$member = $this->Member->read(null, $member_id);
		$this->set("member_id", $member_id);

		$sending_member = $this->Session->read("Auth");
		$my_member_id = $sending_member['Member']['member_id'];

		if (!$member || empty($member))
		{
			$this->setFlash("Invalid member specified.");
			return;
		}

		$member_limits = $this->member_limits($my_member_id);#$this->viewVars['current_member_limits'];


		if (!$member_limits['can_send_messages'] && $re_msg_id == '')
		{
			$this->require_upgrade("send messages");
		}

		$this->set("member_id", $my_member_id);
		$this->set("member", $member);
		$this->set("re_msg_id", $re_msg_id);
		$re_subject = "";
		$re_message = null;
		if ($re_msg_id != "")
		{
			$re_message = $this->MemberMessage->read(null, $re_msg_id);
			$re_subject = $re_message['MemberMessage']['subject'];
			if (substr($re_subject, 0,4) != 'Re: ') # Put in front if NOT already there...
			{
				$re_subject = "Re: $re_subject";
			}
		}
		$this->set("re_subject", $re_subject);

		if (!empty($this->data))
		{
			$this->data['MemberMessage']['member_id_from'] = $sending_member['Member']['member_id'];
			$this->data['MemberMessage']['member_id_to'] = $member_id;
			if ($re_msg_id != '')
			{
				$this->data['MemberMessage']['re_msg_id'] = $re_msg_id;
				$this->set("re_msg_id", $re_msg_id);
			}
			$this->data['MemberMessage']['sent_time'] = date('Y-m-d H:i:s');
			$this->data['MemberSentMessage'] = $this->data['MemberMessage'];

			$this->set("member_id", $member_id);


			$this->MemberMessage->create();
			$this->MemberMessage->save($this->data);
			$msg_id = $this->MemberMessage->id;

			$this->MemberSentMessage->create();
			$this->MemberSentMessage->save($this->data);

			$this->sendEmail($member, "FirstFashionSite.com Private Message", "message",
				array('receiving_member'=>$member, 'sending_member'=>$sending_member, 'msg_id'=>$msg_id));
			$this->Session->setFlash("Message sent.");
			$this->action = 'send_complete';
			return;
		}

		if ($re_message && !empty($re_message)) {
			$re_content = $re_message['MemberMessage']['content'];
			$re_sent_date = $re_message['MemberMessage']['sent_time'];
			$re_name = $re_message['Sender']['username'];

			$date = date("m/d/Y H:i:s", strtotime($re_sent_date));

			$re_content = "\n\n\n\n--- On $date, $re_name wrote: ---\n\n$re_content";
			$this->data['MemberMessage']['content'] = $re_content;
		}
		$this->data['Member'] = $member;
	}

	function delete($id = '')
	{
		if (!$id) { $this->Session->setFlash("Invalid message."); $this->redirect("/member_messages/index"); }
		$member_id = $this->Session->read("Auth.Member.member_id");
		$message = $this->MemberMessage->find("member_id_to = '$member_id' AND msg_id = '$id'");
		$message_id = $message['MemberMessage']['msg_id'];
		if (!$message)
		{
			$this->Session->setFlash("Invalid message.");
			$this->redirect("/member_messages/index");
		}
		$this->Message->del($message_id);
		$this->Session->setFlash("Message deleted.");
		$this->redirect("/member_messages/index");
	}


}
