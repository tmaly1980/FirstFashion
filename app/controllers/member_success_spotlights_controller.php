<?php
class MemberSuccessSpotlightsController extends AppController {

	var $name = 'MemberSuccessSpotlights';
	var $helpers = array('Html', 'Form','Time');
	var $uses = array('MemberSuccessSpotlight','Member');

	var $paginate = array(
		'order'=>array('start_date' => 'DESC'),
	);

	#var $paginate = array(
	#	'limit'=>3, # Since all there's room for...
	#	# !!! make sure only let 9 sign up at a time!
	#	'order'=>array('end_date' => 'ASC','start_date'=>'ASC'),
	#	# Show those who are expiring soon, and who have longer timeframes if ending on same day....
#
#		# ONLY SHOW THOSE WITH ACTIVE DATE RANGES!!!
#		'conditions'=>"start_date <= now() AND now() <= end_date",
#	);


	function index() {
		$this->MemberSuccessSpotlight->recursive = 0;
		$this->set('memberSuccessSpotlights', $this->paginate());
	}

	function view()
	{
		# Show only one, but pick RANDOM one....
		# pick random one? if so, how sort on admin side?
		# maybe have large popup with member info and form on left, LARGE calendar on right,
		# with calendar showing names for each day, and buttons on left (date form) saying 'choose by day' to 
		# click on calendar's day number to fill in form, then ajax update 'save' button... 

		# TO MANAGE, ADMIN JUST NEEDS TO LOG IN, GO TO HOME PAGE, CLICK ON MEMBER MENTIONED IN SPOTLIGHT AND THEN
		# click on 'manage spotlight' button...


		# WAIT!!!! WHAT IF THIS ISNT WHATS WANTED? I HAVE TO READ THOSE EMAILS!!!!!

		# *** JUST GET FRONTEND TO EVERYTHING DONE, THEN PAYPAL AND ACLS DONE, THEN CHECK EMAILS ABOUT BACKEND, etc..

		$this->layout = 'xml';

		# SOMEDAY IMPLEMENT RANDOM PICKING OF LIST... 'order by rand()' ...
		$this->MemberSuccessSpotlight->recursive = 0;
		#$spotlight = $this->MemberSuccessSpotlight->find("start_date <= now() AND now() <= end_date AND Member.active = 1", null, "end_date ASC, start_date ASC");
		$spotlight = $this->MemberSuccessSpotlight->find("first", array('conditions'=>"start_date <= now() AND Member.active = 1", "limit"=>1, "order"=>"start_date DESC"));

		$registration_date = $spotlight['Member']['registration_date'];
		if (!$registration_date) { $registration_date = date('Y-m-d'); }
		$reg_parts = split("-", $registration_date);
		$member_since = $reg_parts[0];
		if (!$member_since) { $member_since = date('Y'); }
		
		$this->set('member_since', $member_since);
		$this->set('success_spotlight', $spotlight);
	}

	function admin_index() {
		$this->layout = 'popup';
		$this->MemberSuccessSpotlight->recursive = 0;
		$this->set('memberSuccessSpotlights', $this->paginate());
	}

	function admin_add($id = '') {
		$this->layout = 'popup';
		if (!$id && !$this->data)
		{
			$this->Session->setFlash("Invalid member.");
			$this->redirect(array('action'=>'index'));
		}
	
		if (!empty($this->data)) {
			$this->MemberSuccessSpotlight->create();
			if ($this->MemberSuccessSpotlight->save($this->data)) {
				$this->Session->setFlash(__('The MemberSuccessSpotlight has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The MemberSuccessSpotlight could not be saved. Please, try again.', true));
			}
		}

		if ($id)
		{
			$this->Member->read(null, $id);
			$this->data['MemberSuccessSpotlight']['member_id'] = $id;
			$this->data['Member'] = $member['Member'];
		}

		# Get default start date. First sunday of a free week.
		$this->MemberSuccessSpotlight->recursive = -1;
		$spotlight_max = $this->MemberSuccessSpotlight->find("first", array('conditions'=>"start_date >= now() OR end_date IS NULL OR end_date >= now()", 'fields'=>"MAX(start_date) AS max_start_date, MAX(end_date) AS max_end_date")); # 200829, etc
		# LAST entry IN THE FUTURE...

		#print_r($spotlight_max);

		$max_date = date('Y-m-d'); # Default to this week.
		if (count($spotlight_max) && $spotlight_max[0]['max_end_date'] > 0) { $max_date = $spotlight_max[0]['max_end_date']; }
		else if (count($spotlight_max) && $spotlight_max[0]['max_start_date'] > 0) { $max_date = $spotlight_max[0]['max_start_date']; }

		# Translate to timestamp.
		$time = strtotime($max_date);

		$timelist = localtime($time,true);

		$dayofweek = $timelist["tm_wday"];

		error_log("MAX_DATE=$max_date, TIME=$time");

		# Now add 7 days to such.
		# Now subtract days, so turn into sunday.
		$time += 60*60*24*7 - 60*60*24*$dayofweek;

		# Try to split into parts, translate weekday to '0' (sunday)

		$default_start_date = date("Y-m-d", $time);

		error_log("DSD=".$default_start_date);

		# calculate the sunday's date from that week...
		$this->data['MemberSuccessSpotlight']['start_date'] = $default_start_date;

		#$members = $this->MemberSuccessSpotlight->Member->find('list');
		#$this->set(compact('members'));
	}

	function admin_editmember($id = null)
	{
		$this->layout = 'popup';
		if (!$id)
		{
			$this->Session->setFlash('Invalid member');
			$this->redirect(array('action'=>'index'));
		}

		# If has an ACTIVE entry (current or in the future), SHOW IT, else, show a new form....

		$spotlight = $this->MemberSuccessSpotlight->find("MemberSuccessSpotlight.member_id = '$id' AND (start_date >= now() OR (start_date < now() AND end_date >= now()))");
		if ($spotlight)
		{
			$spid = $spotlight['MemberSuccessSpotlight']['spotlight_id'];
			error_log("ID=$id, SPID=$spid");
			$this->setAction("admin_edit", $spid);
		} else {
			$this->setAction("admin_add", $id);
		}

	}

	function admin_edit($id = null) {
		$this->layout = 'popup';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid MemberSuccessSpotlight', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->MemberSuccessSpotlight->save($this->data)) {
				$this->Session->setFlash(__('The MemberSuccessSpotlight has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The MemberSuccessSpotlight could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MemberSuccessSpotlight->read(null, $id);
		}
		#$members = $this->MemberSuccessSpotlight->Member->find('list');
		#$this->set(compact('members'));
	}

	function admin_delete($id = null) {
		$this->layout = 'popup';
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for MemberSuccessSpotlight', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MemberSuccessSpotlight->del($id)) {
			$this->Session->setFlash(__('MemberSuccessSpotlight deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
