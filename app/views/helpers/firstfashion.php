<?

# All stuff firstfashion oriented....

class FirstfashionHelper extends AppHelper
{
	var $helpers = array('Session');

	function randseed()
	{
		# Generate seed, return.
		return intval(rand(100,10000));
	}

	function isOwnProfile()
	{
		$view =& ClassRegistry::getObject('view');

		#print_r($view->viewVars);

		#print_r($view->data);

		# XXX TODO
		# If under section where we dont have any db records yet,
		# we WONT have 'Member' record!
		# Thats why we need to have 'member_id' set from CONTROLLER!

		# Alt way, assuming set via controller's $this->set("member", $member):
		# $current_memberid = $view->viewVars["member"]["member_id"];
		# or viewVars accessibel via $view->getVar("member")

		$current_memberid = $view->getVar("member_id");
		if (!$current_memberid && isset($view->data["Member"]["member_id"])) { $current_memberid = $view->data["Member"]["member_id"]; }
		#$current_memberid = $current_member["member_id"];

		$my_memberid = $this->Session->read("Auth.Member.member_id");
		#error_log("CUR=$current_memberid, MY=$my_memberid");

		if ($my_memberid > 0 && $my_memberid == $current_memberid)
		{
			return true;
		} else {
			return false;
		}
		# Check whether page on is for SELF or NOT (check $this->Session->... vs $this->Member->... ???)
	}
}

?>
