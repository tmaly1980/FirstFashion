<?php
/* SVN FILE: $Id: app_controller.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 22:33:52 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
class AppController extends Controller 
{
	# DEFINITELY NEED A WEB INTERFACE FOR UPDATING CONFIGS, etc... (db, paypal, etc)
	# AND FOR UPLOADING SCHEMA....
	# WOULD BE NICE TO HAVE CGI TO UPDATE FILES (for whole app)
	# like a small little plain php app that does all this app mgmt. ie deployer tool.
	# BETTER CONFIG FILE MANAGER!
	# BETTER MANAGEMENT INTERFACE....

	# *** SINGLE PHP FILE THAT does-it-all, all needed to deploy site, etc.
	# and stuff so dont have to fuck around with file contents when moving 
	# from one server to another.... (conditions, etc...)

	var $helpers = array('Html','Session','Form','Javascript','Firstfashion','Ajax','Time');
	var $components = array('Auth','Upload','Email','Payment');
	var $uses = array('MemberFriend','Member','MemberMessage','MemberSession');
	var $appconfig = null;
	
	function beforeFilter()
	{
		# Maybe there's a startup() we can use???
		if (!defined('BASE')) {
			define('BASE', $this->base); # So can reference BASE in views for portable absolute paths...
		}
		if (!defined('HTTPHOST')) {
			define('HTTPHOST', "http://" . $_SERVER['HTTP_HOST']); # for urls esp in emails.
		}

		#$this->Auth->allow('forgot','signup','view','index','browse','search'); # Anonymous pages....
		# Each controller specifies which actions are anonymous....
		$this->Auth->allow('*'); # Default allow access to stuff, unless controller says differently.
		#$this->Auth->userScope = array('Member.active' => 1);
		$this->Auth->fields = array('username'=>'eMail_Address','password'=>'Password');
		$this->Auth->loginRedirect = array('action'=>'index'); 
		#$this->Auth->logoutRedirect = array('controller'=>'members','action'=>'index'); # Go to homepage
		$this->Auth->logoutRedirect = "/";#array('controller'=>'members','action'=>'index'); # Go to homepage
		$this->Auth->loginError = 'Unable to login. Did you <a href="/members/forgot">forget your password?</a>';
		#$this->Auth->authorize = 'controller';
		$this->Auth->autoRedirect = false; # sends to members::login() before continues.... for banned email check.

		$this->Auth->authError = '<h2>Please login to continue.</h2>';
		$this->Auth->userModel = 'Member';

		#error_log("APP BEFORE FILTER");
		#$this->Auth->autoRedirect = false;

		#if (!$this->data)
		#{
			#$this->data = array(); # So we dont get errors.... ???
		#}

		if (isset($this->params['admin']))
		{
			$this->checkAdminSession();
		}

		# read ALL files within config/firstfashion
		$member = $this->Session->read("Auth.Member");
		$c = $this->params['controller'];
		$a = $this->params['action'];
		$ca = "$c/$a";
		$valid_pages = array(
			'members/edit',
			'member_model_profiles/edit', # Will need to add entry for professionals
			'member_photos/editlist',
			'member_photos/edit',
			'member_videos/editlist',
			'member_videos/edit',
			'members/logout',
			'members/suspended',
			'pages/display',
			'members/reinstate_request',
		);
		#error_log("REQUESTING $ca");
		if (isset($member) && $member['active'] == 0 && !in_array($ca, $valid_pages))
		#$a != 'edit' && $a != 'editlist' && $ca != 'members/suspended' && $ca != 'members/logout')
		{
			#$this->Session->setFlash("Bad account!");
			#$this->redirect("/members/edit");
			$this->redirect("/members/suspended");
		}

		$this->load_appconfig();
	}

   	function checkAdminSession() {  
   		// if the admin session hasn't been set  
		$authorized = false;

		if (!$this->isAdminAuthorized())
		{
   			// set flash message and redirect  
			$this->Security->requireLogin();
   		} else {
		}
	}

	function isAdminAuthorized()
	{
		# Either is_admin flag set in DB (good to not have web interface so not abused), or email in list.
		$is_admin = $this->Session->read("Auth.Member.is_admin");
		if ($is_admin) { return true; }
		return false;
   	}  

	function isAuthorized_old()
	{
		# DOESNT QUITE WORK BECAUSE ONLY CHECKS AGAINST NON-PUBLIC ACTIONS... (/members/view ignored)
		$c = $this->params['controller'];
		$a = $this->params['action'];
		$ca = "$c/$a";

		$is_active = $this->Auth->user('active');
		error_log("IS_ACT=$is_active, C=$c, A=$a");
		if (!$is_active)
		{
			if ($a != 'edit' && $a != 'editlist' && $ca != 'members/suspended' && $c != 'pages' && $ca != 'members/logout' && $ca != 'members/reinstate') # If not editing something to fix...
			{
				error_log("SUSPENDED=".$_SERVER['REQUEST_URI']);
				$this->redirect("/members/suspended"); # Bad user!
			}

		}
		return true;
	}

	function load_appconfig()
	{
		if (!$this->appconfig)
		{
			$this->appconfig = include_once(dirname(__FILE__) . "/config/ff.conf.php");
		}
	}

	function load_appconfig_alt()
	{
		$this->appconfig = array();

		# maybe someday have a generic appconfig.php to load some globals out of...

		$basedir = CONFIGS . "/site";
		$dir = opendir($basedir);
		while($filename = readdir($dir))
		{
			if (preg_match("/^(\w+)[.]config[.]php/", $filename, $matches))
			{
				$prefix = $matches[1];
				$file_config = include($basedir . "/" . $filename);
				$this->appconfig[$prefix] = $file_config;
			}
		}
	}

	function member_limits($member_id = '', $key = null)
	{
		if (!$member_id) { $member_id = $this->Session->read('Auth.Member.member_id'); }
		if (!$member_id) { return array(); }
		if (is_array($member_id)) { $member = $member_id; }
		else { $member = $this->Member->read(null, $member_id); }
		#print_r($member);
		if (!$member || empty($member)) { return array(); }
		$limits = $this->Member->member_limits($member);
		#error_log("KEY=$key, LIM=".print_r($limits,true));
		if (isset($key)) { if(isset($limits[$key])) { return $limits[$key]; } else { return null; } }
		return $limits;
	}

	function beforeRender()
	{

		$member_id = $this->Session->read('Auth.Member.member_id');
		$member = $this->Session->read('Auth.Member');
		$friends = $member_id ? $this->MemberFriend->findAll("owner_member_id = '$member_id'") : null;

		$logged_in = $member_id ? true : false;
		$this->set('current_member_id', $member_id);
		$this->set('current_member', $member);
		$newmsgcount = $this->MemberMessage->findCount("member_id_to = '$member_id' AND is_read != true");

		$this->set('current_member_new_message_count', $newmsgcount);
		$is_admin = isset($member['is_admin']) ? $member['is_admin'] : false;
		$this->set("current_is_admin",$is_admin);

		$this->set('current_member_friends', $friends);
		$current_member_limits = $this->member_limits($member_id);
		$this->set('current_member_limits', $current_member_limits);
		#error_log("MEMBER_LIMITS=".print_r($current_member_limits,true));

		$this->set('logged_in', $logged_in);
		$this->set('page', $this->params['url']['url']);

		$trail = array();
		#$this->set('trail',$this->generate_trail($this->params['url']['url'], $trail));

		# Below so we can have CSS for page content, etc... per page.
		$this->set('controller', $this->params['controller']);
		$this->set('action', $this->params['action']);
	}

	function can_upgrade($member)
	{
		#error_log("CAN_UPGRADE MEM=".print_r($member,true));
		if (!$member || empty($member)) { return false; }
		$membership_levels = $this->Member->getEnumValues("membership_level");
		$my_level = $member['Member']['membership_level'];
		$maxlevel = count($membership_levels)-1;
		#error_log("MYLEVEL=$my_level, MAX=$maxlevel");
		#error_log("MEMS=".print_r($membership_levels,true));
		#echo "MAXLEVEL=$maxlevel";
		$i = 0;
		#for($i = 0; $i < $maxlevel; $i++)
		foreach($membership_levels as $key => $value)
		{
			#echo "KEY=$key, MY=$my_level, I=$i, MAX=$maxlevel";
			if ($key == $my_level && $i < $maxlevel)#my_level < $maxlevel)
			{
				return true;
			}
			$i++;
		}
		#error_log("NOPE");
		return false;
	}
	function require_upgrade_popup($feature, $failurl = '')
	{
		$member = $this->Session->read("Auth");
		if ($this->can_upgrade($member))
		{
			$this->Session->setFlash("Upgrade required");
			$this->redirect("/members/feature_upgrade_popup");
			# Eventualyl should go to page where can choose level based on chart of features...
		} else if ($failurl) {
			$this->Session->setFlash("Unable to $feature: membership limits exceeded.");
			$this->redirect($failurl);
		} # Else, silently ignore.

	}

	function require_upgrade($feature, $failurl = '')
	{
		$member = $this->Session->read("Auth");
		if ($this->can_upgrade($member))
		{
			$this->Session->setFlash("Upgrade required");
			$this->redirect("/members/upgrade/premium?feature=$feature");
			# Eventualyl should go to page where can choose level based on chart of features...
		} else if ($failurl) {
			$this->Session->setFlash("Unable to $feature: membership limits exceeded.");
			$this->redirect($failurl);
		} # Else, silently ignore.
	}

	function generate_trail($url, &$trail)
	{
		# BLAH, FUCK IT....

		#
		# HONESTLY...
		# seems like better done through cookie variables (as array)....
		# ie check if url matches something in file.
		# if mat#ches, start popping items in cookie until find common ancestor.
		# then append.

		# we need to consider situations like when we're at search results pages and we want to go back!
		# so we cant just wipe out all entries in trail, we have to have a list of ACCEPTABLE parent
		# items....
		# or maybe each page has acceptable child items? dunno..

		# Take a look at current url. If in list, start backtracking to generate parents.

		if ($trail_info = $this->find_trail_item($url))
		{
			#$trail[] = array($url, 
			# Add to list.
			# get parent, add THAT to list.
		}
		$trail_specs = include(dirname(__FILE__)."/../config/trail.php");
		if ($trail_specs && count($trail_specs))
		{
			$trail = array();
			$url = $this->params['url']['url'];
			foreach ($trail_specs as $trail_item => $trail_item_info)
			{
				#$trail[] = 
			}

			return $trail;
		}
	}

	function setError($msg, $model = null)
	{
		$this->setMessage($msg, $model); # For now, do same thing.
	}

	function setMessage($msg, $model = null)
	{
		$merged_msg = $msg;
		if ($model)
		{
			$model_errors = $model->validationErrors;
			if ($model_errors && count($model_errors))
			{
				$merged_msg .= "<br/><br/>Reason: ";
				foreach($model_errors as $model_field => $model_error)
				{
					$merged_msg .= "<br/> $model_field: $model_error";
				}
			}
		}
		$this->Session->setFlash(__($merged_msg, true));
	}

	function expressCheckout($callback = null) # Generic such that can be moved to app_controller.php!
	{
	    error_log("CALLBACK=$callback");

	    if (isset($callback) && isset($_REQUEST['csid']))
	    {
	        // Restore session
	        
	        if (!$this->Payment->restoreSession($_REQUEST['csid']))
	        {
	    		#$this->Session->setFlash(__('Could not restore session.',true));
	    		$this->Session->setFlash(__('Could not complete transaction (retrieving session). Please try again.',true));
			$this->redirect("/members/edit");
	        }
	    }


	    if (!isset($callback))
	    {
		if (!$this->Payment->submitCheckout())
		{
	    		$this->Session->setFlash(__('Could not submit order: ' . $this->Payment->getError(),true));
			$this->redirect("/members/edit");
		}
	    }
	    else if ($callback == 'cancel')
	    {
	    	$this->Session->setFlash(__('Payment canceled.',true));
		$this->redirect("/members/edit");
	        #echo 'SNIFF... Why not?';
	        #exit;
	    }
	    else if ($callback == 'pay')
	    {
	        // Second call, make payment via PayPal

		$result = $this->Payment->getCheckoutResponse();
	        
	        if ($result === false)
	        {
	    	    $this->Session->setFlash(__('Unable to process payment: ' . $this->Payment->getError(), true));
	   	    $this->redirect("/members/edit");
	        }
	        else # Did payment... so do post-processing....
	        {
		    $transaction_id = $result["transaction"];
		    return $transaction_id;
		    # Save transaction_id into 'sales' so can do refund, etc...
		    #$this->setAction($this->payment_process_callback, $transaction_id);
	        }
	    }
	}

	function states_list($optional_value = false)
	{
		if ($optional_value === true) { $optional_value = 'None'; }

		$states = array(
			'' => $optional_value,
			'AK' => 'Alaska', 
			'AL' => 'Alabama', 
			'AR' => 'Arkansas', 
			'AZ' => 'Arizona', 
			'CA' => 'California', 
			'CO' => 'Colorado', 
			'CT' => 'Connecticut', 
			'DC' => 'District of Columbia', 
			'DE' => 'Delaware', 
			'FL' => 'Florida', 
			'GA' => 'Georgia', 
			'HI' => 'Hawaii', 
			'IA' => 'Iowa', 
			'ID' => 'Idaho', 
			'IL' => 'Illinois', 
			'IN' => 'Indiana', 
			'KS' => 'Kansas', 
			'KY' => 'Kentucky', 
			'LA' => 'Louisiana', 
			'MA' => 'Massachusetts', 
			'MD' => 'Maryland', 
			'ME' => 'Maine', 
			'MI' => 'Michigan', 
			'MN' => 'Minnesota', 
			'MO' => 'Missouri', 
			'MS' => 'Mississippi', 
			'MT' => 'Montana', 
			'NC' => 'North Carolina', 
			'ND' => 'North Dakota', 
			'NE' => 'Nebraska', 
			'NH' => 'New Hampshire', 
			'NJ' => 'New Jersey', 
			'NM' => 'New Mexico', 
			'NV' => 'Nevada', 
			'NY' => 'New York', 
			'OH' => 'Ohio', 
			'OK' => 'Oklahoma', 
			'OR' => 'Oregon', 
			'PA' => 'Pennsylvania', 
			'RI' => 'Rhode Island', 
			'SC' => 'South Carolina', 
			'SD' => 'South Dakota', 
			'TN' => 'Tennessee', 
			'TX' => 'Texas', 
			'UT' => 'Utah', 
			'VA' => 'Virginia', 
			'VT' => 'Vermont', 
			'WA' => 'Washington', 
			'WV' => 'West Virginia', 
			'WY' => 'Wyoming', 
		);

		if (!$optional_value) { unset($states['']); }

		return $states;
	}

	function sendAdminEmail($subject, $template, $vars = array(), $from = '')
	{
		$this->sendEmail($this->appconfig['admin_email'], $subject, $template, $vars, $from);
	}

	function sendEmail($member, $subject, $template, $vars = array(), $from = '')
	# Likely pass $this->data, $this->data["Member"], or $this->data["Member"]["email"] (tho cant read info in latter)
	{
		if (isset($vars['member']) && is_array($vars['member']) && isset($vars['member']["Member"])) { $vars['member'] = $vars['member']["Member"]; } # So can $member['email'], etc

		if (is_array($member) && isset($member["Member"])) { $member = $member["Member"]; } # So can $member['email'], etc
		$member_email = is_array($member) ? $member["email"] : $member;
		if (is_string($member) && preg_match("/.+@.+/", $member)) { $member_email = $member; } # In case given direct email, ie admin.
		if (!$member_email) { $this->Session->setFlash("Unable to send email. No email address found."); return false; }

		#error_log("USER=$member, SUB=$subject, TEM=$template");

		if (!$from) { $from = $this->appconfig['admin_email']; }
		if (!preg_match('/@/', $from)) { $from = "$from@$_SERVER[HTTP_HOST]"; }
		# SHOULD AUTOMATICALLY DO FROM SENDMAIL...
		# If relative membername, set to domain!


		$this->Email->reset();
		$this->Email->from = $from;
		$this->Email->to = $member_email;
		$this->Email->subject = $subject;
		$this->Email->template = $template;
		$this->Email->sendAs = 'html';

		if (is_array($member)) { $this->set("member", $member); } # If we do not pass, we can't read name, act codes, etc.

		if (is_array($vars))
		{
			foreach ($vars as $var => $value)
			{
				$this->set($var, $value);
			}
		}

		return $this->Email->send();
	}

	function get_date($time = null)
	{
		return date('Y-m-d H:i:s', $time);
	}

	function get_url_host()
	{
                $hostUrl = 'http://';
                if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0)
                {
                    $hostUrl = 'https://';
                }

		$hostUrl .= $_SERVER['HTTP_HOST'];
		return $hostUrl;
	}

	function get_url($query_string = '') # Something a bit more sane.
	{
		$hostUrl = $this->get_url_host();
	
		$pageUrl = $hostUrl . "/" . $this->params['url']['url'];
		if ($query_string)
		{
			$pageUrl .= "?$query_string";
		}

		return $pageUrl;
	}

	function get_url_raw($query_string = '') # Nasty since does /app/webroot shit,etc... might even be wrong.
	{
		$serverName = $_SERVER['SERVER_NAME'];
                $serverPort = $_SERVER['SERVER_PORT'];

                $pathParts = pathinfo($_SERVER['SCRIPT_NAME']);
                $pathInfo = $pathParts['dirname'];

                $pageUrl = 'http://';
                if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0)
                {
                    $pageUrl = 'https://';
                }

                $pageUrl .= $serverName . ($serverPort != 80 ? ':' . $serverPort : '');
                $pageUrl .= $pathInfo;
                $pageUrl .= '/' . $_SERVER['SCRIPT_NAME'];

		if (isset($query_string))
		{
			$_SERVER['QUERY_STRING'] .= "&" . $query_string;
		}

                if (!empty($_SERVER['QUERY_STRING']))
                {
                    $pageUrl .= '?' . $_SERVER['QUERY_STRING'];
                }


		return $pageUrl;
	}

	function redirect_referer($alt = '/') # Go back to previous page OR to alt page if referer not set.
	{
		$url = $_SERVER["HTTP_REFERER"];
		if (!$url) { $url = $alt; }
		$this->redirect($url);
	}

	function close_session_by_member_id($id = '')
	{
		if (!$id) { return false; }
		$this->Member->recursive = -1;
		$member = $this->Member->read(null, $id);
		$session_id = $member['Member']['session_id'];
		if ($session_id != '')
		{
			$this->MemberSessions->del($session_id);
			$x = 1;
		}
	}

	function tm_media_view($params)
	{
		App::import('View', 'Media');

		$viewClass = "MediaView";
		$mediaObj =& new $viewClass($this);

		# may already be abs path.
		$filename = ((substr($params['path'],0,1) != "/") ? APP : "") . $params['path'] . $params['id'];
		#error_log("FILE=$filename");
		if (!file_exists($filename))
		{
			$this->redirect('/pages/404');
		}
		$file = fopen($filename, 'r');
		
		# Print header
		$mimeType = 'application/octet-stream'; 
		if (!$params['download']) { $mimeType = $mediaObj->mimeType[ $params['extension'] ]; }

		$size = filesize($filename);

		$cachetime = 60*60; # 1 hour.

		$modified_time = filemtime($filename);
		$exists = file_exists($filename);

		$modified = gmdate('D, d M Y H:i:s', $modified_time) . ' GMT';
		error_log( "MOD=$modified @ $filename");


		if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && $exists && $modified !== false)
		{
			$if_modified_since_time = strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]);
			$imd = gmdate("D, d M Y H:i:s", $if_modified_since_time);
			error_log("IFST=$if_modified_since_time, $imd, MT=$modified_time");
			if ($modified_time <= $if_modified_since_time) {	
				header("HTTP/1.0 304 Not Modified");
				exit(0);
			}
			# FUCKER SOME APACHE VERSIONS BARFING IF WE DONT DO THIS PROPERLY....
		}
			#error_log("ENV=".print_r($_SERVER,true));

		#$_SERVER['If-Modified-Since']
		#

		#header("Date: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
		header("Date: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
#		header("Server: Apache");

		#header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		#header("Pragma: no-cache");
		header("Last-Modified: $modified"); # AT LEAST so can reload changes immediately.... no resync required. keep caching up to browser, but let it know that it shouldnt cache too much.

		#header('ETag: "572d3-2baf-48efaae1"');

		header("Accept-Ranges: bytes");
		header("Content-Length: $size");
		header("Keep-Alive: timeout=15, max=100");
		header("Connection: Keep-Alive");

		header("Content-Type: $mimeType");
		#header("Cache-Control: max-age=$cachetime, must-revalidate");
		@ob_end_clean();

		# XXX TOMAS_MALY XXX
		# CACHING IS BROKEN, causes errors on every-other reload on godaddy.
		# (like apache can't properly do caching response)
		# i think we're missing some headers per caching
		# tho media.php works, so try copying all headers from there
		# and try again sometime.

		# read content, print
		while(!feof($file) && connection_status() == 0) {
			set_time_limit(0);
			$fileData = fread($file, $size);
			echo $fileData;
			@flush();
			@ob_flush();
		}

		fclose($file);

		#error_log("DONE DONE!");

		#if (connection_status() == 0 && !connection_aborted()) {
		#		return;
		#	}

		exit(0);
	}

	function copy_file($src, $dst, $default = '')
	{
		if (!is_dir(dirname($dst))) { mkdir(dirname($dst), 0755, true); }
		if (is_file($src)) { copy($src, $dst); }
		else if ($default != '') { copy($default, $dst); }
	}

}
?>
