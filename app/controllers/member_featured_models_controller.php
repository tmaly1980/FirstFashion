<?php
class MemberFeaturedModelsController extends AppController {

	var $name = 'MemberFeaturedModels';
	var $helpers = array('Html', 'Form');
	var $uses = array('MemberFeaturedModel','Member');

	var $paginate = array(
		'limit'=>3, # Since all there's room for...
		# !!! make sure only let 9 sign up at a time!
		#'order'=>array('end_date' => 'ASC','start_date'=>'ASC'),
		# Show those who are expiring soon, and who have longer timeframes if ending on same day....
		#'order'=>array('end_date' => 'ASC','start_date'=>'ASC'),

		# ONLY SHOW THOSE WITH ACTIVE DATE RANGES!!!
		'conditions'=>array("start_date <= now()", "now() <= end_date"),
	);

	function beforeFilter()
	{
		$this->Auth->allow('view'); # Others, ie 'add' restricted.
		parent::beforeFilter();
	}

	function view($seed = '') { # DONE FOR AJAX LOAD...
		# HOW do ajax pagination ??? there IS 'paginate'....
		$this->layout = 'xml';

		error_log("RAND=$seed");


		#$this->params['order'] = array("RAND($seed)" => "ASC");
		$this->params['order'] = "RAND($seed)";

		$this->MemberFeaturedModel->recursive = 0;
		$models = $this->paginate('MemberFeaturedModel', array("Member.active"=>'1'));
		if (count($models))
		{
			foreach($models as &$featured) # Do formatting on info...
			{
				$featured['Member']['calculated_age'] = $this->Member->get_age($featured['Member']['birthdate']);
			}
		}
		#$this->Member->recursive = -1;
		#$member = $this->Member->find("member_id = '$id'");
		#$this->set('member', $member);
		$this->set('featured_models', $models);
	}

	function manage()
	{
		# management page of whether enabled or disabled....
		# marketing content should be only when can still enable.
		# Marketing page to display what about, when (probably) start, etc.

		# ONLY FOR MODELS.
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->set("member_id", $member_id);

		$member_type = $this->Session->read("Auth.Member.member_type");
		if ($member_type != 'model')
		{
			$this->Session->setFlash("We're sorry, Featured Members is only available for 'model' members.");
			$this->redirect("/members/view");
		}

		$featured_price = $this->appconfig['featured_price'];
		$featured_days = $this->appconfig['featured_days'];

		$this->set("featured_price", $featured_price);
		$this->set("featured_days", $featured_days);

		# Check to see if they have an EXISTING 'feature' to manage (cancel)
		# IF ANY, provide status (Active, Pending, Expired) -- tho limit to most recent record.
		# Perhaps provide a full list of all records? but only let sign up for another if NO active/pending

		$has_existing = false;

		$existing_featured = $this->MemberFeaturedModel->findAll("MemberFeaturedModel.member_id = '$member_id'");

		$now = time();

		error_log("EXISTING=".print_r($existing_featured,true));

		if (isset($existing_featured) && count($existing_featured))
		{
			foreach ($existing_featured as $existing_featured_entry)
			{
				$start_date = $existing_featured_entry['MemberFeaturedModel']['start_date'];
				$end_date = $existing_featured_entry['MemberFeaturedModel']['end_date'];
	
				$start_time = strtotime($start_date);
				$end_time = strtotime($end_date);
	
				$start_time = strtotime($existing_featured_entry['MemberFeaturedModel']['start_date']);
				$end_time = strtotime($existing_featured_entry['MemberFeaturedModel']['end_date']);
	
				if ($end_time > $now) 
				{ 
					$has_existing = true; 
				}
			}
		}

		$this->set("existing_featured_list", $existing_featured);

		if ($has_existing) # Active or pending, don't allow more signups.
		{
			$this->set("enable_signup",false);
		} else { # No active/pending, can sign up for further ones...
			# Now calculate the dates where they're going to be listed.
			list($start_date, $end_date) = $this->get_featured_dates();
			$this->set("start_date", $start_date);
			$this->set("end_date", $end_date);
			$this->set("enable_signup",true);
		}
	}

	function admin_manage($member_id)
	{
		# management page of whether enabled or disabled....
		# marketing content should be only when can still enable.
		# Marketing page to display what about, when (probably) start, etc.

		# ONLY FOR MODELS.
		#$member_id = $this->Session->read("Auth.Member.member_id");
		$this->set("member_id", $member_id);

		$member = $this->Member->read(null, $member_id);

		$member_type = $member['Member']['member_type'];
		if ($member_type != 'model')
		{
			$this->Session->setFlash("We're sorry, Featured Members is only available for 'model' members.");
			$this->redirect("/admin/members/view/$member_id");
		}

		$featured_price = $this->appconfig['featured_price'];
		$featured_days = $this->appconfig['featured_days'];

		$this->set("featured_price", $featured_price);
		$this->set("featured_days", $featured_days);

		# Check to see if they have an EXISTING 'feature' to manage (cancel)
		# IF ANY, provide status (Active, Pending, Expired) -- tho limit to most recent record.
		# Perhaps provide a full list of all records? but only let sign up for another if NO active/pending

		$has_existing = false;

		$existing_featured = $this->MemberFeaturedModel->findAll("MemberFeaturedModel.member_id = '$member_id'");

		$now = time();

		error_log("EXISTING=".print_r($existing_featured,true));

		if (isset($existing_featured) && count($existing_featured))
		{
			foreach ($existing_featured as $existing_featured_entry)
			{
				$start_date = $existing_featured_entry['MemberFeaturedModel']['start_date'];
				$end_date = $existing_featured_entry['MemberFeaturedModel']['end_date'];
	
				$start_time = strtotime($start_date);
				$end_time = strtotime($end_date);
	
				$start_time = strtotime($existing_featured_entry['MemberFeaturedModel']['start_date']);
				$end_time = strtotime($existing_featured_entry['MemberFeaturedModel']['end_date']);
	
				if ($end_time > $now) 
				{ 
					$has_existing = true; 
				}
			}
		}

		$this->set("existing_featured_list", $existing_featured);

		if ($has_existing) # Active or pending, don't allow more signups.
		{
			$this->set("enable_signup",false);
		} else { # No active/pending, can sign up for further ones...
			# Now calculate the dates where they're going to be listed.
			list($start_date, $end_date) = $this->get_featured_dates();
			$this->set("start_date", $start_date);
			$this->set("end_date", $end_date);
			$this->set("enable_signup",true);
		}
	}

	function get_featured_dates()
	{
		$max_featured = $this->appconfig['max_featured'];
		$featured_days = $this->appconfig['featured_days'];

		# Find the first date that has LESS than $max_featured active listings....
		# SELECT MIN(start_date), COUNT(*) AS FROM member_featured_models WHERE ... GROUP BY start_date
		# If there are no dates returned, start immediately (tomorrow).

		# GROUP BY DATE, COUNT THE NUMBER OF RECORDS ON THAT DATE.
		#$featured_records = $this->MemberFeaturedModel->findAll("end_date > now()", null, "start_date DESC");

		$featured_records = $this->MemberFeaturedModel->query(
			"SELECT COUNT(*) AS active_count, start_date, end_date FROM ff_member_featured_models MemberFeaturedModel ".
			"WHERE end_date > now() GROUP BY DATE_FORMAT(start_date, '%Y-%m-%d') ORDER BY start_date ASC" # Earliest date.
			);


		$tomorrow_secs = time() + 60*60*24;
		$start_date = date('Y-m-d H:i:s',$tomorrow_secs); # Default to tomorrow.

		foreach ($featured_records as $record)
		{
			#error_log("FEAT=".print_r($record,true));
			$active_count = $record[0]['active_count'];
			$record_start_date = $record['MemberFeaturedModel']['start_date'];
			$record_start_secs = strtotime($record_start_date);

			# MUST be in future....
			if ($active_count < $max_featured && $record_start_secs > $tomorrow_secs)
			{
				$start_date = $record_start_date;
				break;
			}
		}

		$end_date = date('Y-m-d H:i:s', strtotime($start_date) + $featured_days*24*60*60);

		return array($start_date, $end_date);
	}

	function checkout($callback = null)
	{
		$featured_price = $this->appconfig['featured_price'];

		# We need to MASK calls, so we know what to do when we're done! (to db, etc)

		$this->Payment->setOrder($featured_price, "FirstFashionSite.com Featured Listing Fee");

		$rc = $this->expressCheckout($callback);
		# If failed, will go to '/members/edit' automatically....

		if ($callback == 'pay' && $rc) # DONE!
		{
			$transaction_id = $rc;
			$this->redirect(array('action'=>"checkout_process", $transaction_id));
		} else {

		}
		error_log("DONE CHECKOUT PROCESS... AND BACK AT CHECKOUT...");
	}

	function checkout_process($transaction_id)
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		# Now get start dates again, and add record. And place 'transaction_id' into table so can cancel.
		list($start_date, $end_date) = $this->get_featured_dates();

		$this->data['MemberFeaturedModel'] = array(
			'member_id' => $member_id,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'transaction_id' => $transaction_id
		);

		$this->MemberFeaturedModel->create();
		$this->MemberFeaturedModel->save($this->data);

		$this->set("start_date", $start_date);
		$this->set("end_date", $end_date);
		# So confirm page mentions actual dates.

		$this->Session->setFlash("Order Successful");

		$this->redirect("/member_featured_models/manage");
	}

	function admin_checkout($member_id)
	{
		#$member_id = $this->Session->read("Auth.Member.member_id");
		$transaction_id = "";
		# Now get start dates again, and add record. And place 'transaction_id' into table so can cancel.
		list($start_date, $end_date) = $this->get_featured_dates();

		$this->data['MemberFeaturedModel'] = array(
			'member_id' => $member_id,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'transaction_id' => $transaction_id
		);

		$this->MemberFeaturedModel->create();
		$this->MemberFeaturedModel->save($this->data);

		$this->set("start_date", $start_date);
		$this->set("end_date", $end_date);
		# So confirm page mentions actual dates.

		$this->Session->setFlash("Order Successful");

		$this->redirect("/admin/member_featured_models/manage/$member_id");
	}

	function cancel($id)
	{
		# Make sure listing is not started yet....
		$member_id = $this->Session->read("Auth.Member.member_id");
		$featured_record = $this->MemberFeaturedModel->find("featured_model_id = '$id' AND MemberFeaturedModel.member_id = '$member_id'");
		if (!$featured_record)
		{
			$this->Session->setFlash("Invalid listing.");
			$this->redirect("/member_featured_models/manage");
		}

		$transaction_id = $featured_record['MemberFeaturedModel']['transaction_id'];

		$featured_start_secs = strtotime($featured_record['MemberFeaturedModel']['start_date']);

		$now = time();

		if ($featured_start_secs > $now) # NOT too late....
		{
			# Process cancellation.
			if (!$this->Payment->refundTransaction($transaction_id,"Featured Model Listing Cancellation"))
			{
	    			$this->Session->setFlash(__('Could not process refund: ' . $this->Payment->getError(),true));
				$this->redirect("/member_featured_models/manage");
			}

			$this->MemberFeaturedModel->del($id);


			$this->Session->setFlash("Cancellation Complete");

			$this->redirect("/member_featured_models/manage");

			# Display confirmation page.
		} else { 
			$this->action = 'cancel_toolate';
		}
	}

	function admin_cancel($id, $member_id)
	{
		# Make sure listing is not started yet....
		#$member_id = $this->Session->read("Auth.Member.member_id");

		$featured_record = $this->MemberFeaturedModel->find("featured_model_id = '$id' AND MemberFeaturedModel.member_id = '$member_id'");
		if (!$featured_record)
		{
			$this->Session->setFlash("Invalid listing.");
			$this->redirect("/admin/member_featured_models/manage/$member_id");
		}

		$transaction_id = $featured_record['MemberFeaturedModel']['transaction_id'];

		$featured_start_secs = strtotime($featured_record['MemberFeaturedModel']['start_date']);

		$now = time();

		$this->MemberFeaturedModel->del($id);
		$this->Session->setFlash("Featured Cancellation Complete");
		$this->redirect("/admin/members/edit/$member_id");
	}

}
?>
