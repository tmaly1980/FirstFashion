<?
class MemberVideosController extends AppController
{
	var $helpers = array('Html','Form','Javascript','Ajax');
	var $components = array('Youtube');

	var $paginate = array(
		'limit' => 6, # Per page.
		'order' => array('MemberVideo.member_video_id'=>'DESC'),
	);

	function beforeFilter()
	{
		$this->Auth->allow('view','embed','view_latest','view_meta','flag');

		return parent::beforeFilter();
	}

	# honestly should exclude videos from members who arent paying anymore.
	function view_latest() { # DONE FOR AJAX LOAD...
		$this->layout = 'xml';
		$this->MemberVideo->recursive = 0;
		$videos = $this->paginate('MemberVideo',"is_active = 1 AND youtube_video_id != '' AND youtube_video_id IS NOT NULL AND disabled = FALSE ");

		foreach ($videos as &$video)
		{
			$video_id = $video['MemberVideo']['youtube_video_id'];
			$video['MemberVideo']['video_thumbnail_url'] = $this->Youtube->getThumbnailUrl($video_id);
		}

		$this->set('videos', $videos);
	}

	function view_by_ytid($ytid = '')
	{
		$this->layout = 'popup';
		$this->set("youtube_video_id", $ytid);
		$this->set("flash_video_url", $this->Youtube->getFlashPlayerVideoUrl($ytid));
		$this->action = 'view';
	}

	function view($member_id = '')
	{

		$this->layout = 'popup';
		if (!$member_id)
		{
			$member_id = $this->Session->read("Auth.Member.member_id");
		}
		$this->set("member_id", $member_id);
		$this->set("member", $this->Member->read(null, $member_id));

		$video_enabled = $this->member_limits($member_id, 'video_enabled');
		if (!$video_enabled)
		{
			$this->Session->setFlash("Video Unavailable");
			return;
		}


		$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id' AND is_active = 1 AND disabled = FALSE ");
		$this->set("video", $video);
		$this->set("youtube_video_id", $video['MemberVideo']['youtube_video_id']);
		$this->set("flash_video_url", $this->Youtube->getFlashPlayerVideoUrl($video['MemberVideo']['youtube_video_id']));


	}

	function view_meta($member_id)
	{
		if (!$member_id)
		{
			$member_id = $this->Session->read("Auth.Member.member_id");
		}
		$this->set("member_id", $member_id);
		$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id'");

		header('Content-Type: text/plain');

		if (!$video)
		{
			echo "No Video Available";
		}
		else if (!$video_id = $video['MemberVideo']['youtube_video_id'])
		{
			echo "No Video Available";
		} else {
			$meta = $this->Youtube->getVideoEntry($video_id);
			echo "FLASH=".$meta->getFlashPlayerUrl() ."\n";
			echo "Thumbnails:\n";
  			$videoThumbnails = $meta->getVideoThumbnails();

  			foreach($videoThumbnails as $videoThumbnail) 
			{
    				echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
    				echo ' height=' . $videoThumbnail['height'];
    				echo ' width=' . $videoThumbnail['width'] . "\n";
  			}

			echo "\n\n\n\n\n==================================\n";

			print_r($meta);
		}
		exit(0);
	}

	function delete_youtube($id)
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		# Delete from youtube AND local....
		$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id' AND MemberVideo.member_video_id = '$id'");
		if (!$video)
		{
			$this->Session->setFlash("Invalid member video.");
			$this->redirect("/member_videos/editlist");
		}
		$youtube_video_id = $video['MemberVideo']['youtube_video_id'];

		#error_log("YT_VID=$youtube_video_id");

		try {
			$this->Youtube->deleteVideo($youtube_video_id);
		} catch (Exception $e) {
			$this->Session->setFlash("Could not remove video from YouTube: ". $e->getMEssage());
			$this->redirect("/member_videos/editlist");
		}

		$this->MemberVideo->del($id);


		$this->Session->setFlash("Video deleted from YouTube.");
		$this->redirect("/member_videos/editlist");
	}

	function add($callback = '', $id = '')
	{
		$member_id = $this->Session->read("Auth.Member.member_id");
		$this->set('member_id', $member_id);

		if ($callback == 'upload_complete') # Store video id in db.
		{
			$upload_status = $_REQUEST['status'];
			$youtube_video_id = $_REQUEST['id'];

			if ($upload_status > 200) # ERROR!
			{
				$this->Session->setFlash("Unable to upload video: ". $_REQUEST['error']);
				$this->redirect("/member_videos/add");
			}

			$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id' && member_video_id = '$id'");
			if (!$video || !isset($video["MemberVideo"]))
			{
				$this->setFlash("Unable to find video in database to complete transaction. Please, try again.");
				$this->redirect("/member_videos/add");
			} else {
				$this->MemberVideo->id = $id;
				$this->MemberVideo->saveField("youtube_video_id", $youtube_video_id);
			}

			$this->Session->setFlash("Upload Complete.");
			$this->redirect("/member_videos/editlist");
		}


		if (!empty($this->data))
		{
			# Process, get token, then show upload page.
			$this->data['MemberVideo']['member_id'] = $member_id; # Set owner.

			try {
	
				list ($token, $posturl) = $this->Youtube->getUploadTokenAndUrl(array(
					'title'=>$this->data['MemberVideo']['title'],
					'description'=>$this->data['MemberVideo']['description'],
					'category'=>'Entertainment',
					'tags' => "FirstFashion, fashion, model",
				));
	
				if ($this->data['MemberVideo']['is_active']) # Clear all other entries.
				{
					$this->MemberVideo->updateAll(array('is_active' => '0'), "MemberVideo.member_id = '$member_id'");
				}
	
				$this->MemberVideo->create();
				$video = $this->MemberVideo->save($this->data);
				$member_video_id = $this->MemberVideo->id;
	
				$this->set('upload_token', $token);
				$this->set('upload_url', $posturl);
				$this->set('member_video_id', $member_video_id);
				$this->set('return_url', $this->get_url_host()."/member_videos/add/upload_complete/$member_video_id");

				$this->set("video", $video);
	
				$this->action = 'add_upload';

			} catch (Exception $e) {
				$this->Session->setFlash("Cannot save information: " . $e->getMessage());
				$this->redirect("/member_videos/add");

			}
		} else { # Show video info/meta form.
			$has_active = $this->MemberVideo->hasAny("MemberVideo.member_id = '$member_id' AND is_active = 1");
			$this->set("has_active", $has_active);
		}
	}

	function test_getmyvideos()
	{
		header('Content-Type: text/plain');
		$videolist = $this->Youtube->getVideoList();
		$count = count($videolist);
		echo "LIST: ($count)\n";
		$videos = array();
		foreach ($videolist as $videoentry)
		{
			$videos[] = $videoentry;
			echo "FLASH: " . $videoentry->getFlashPlayerUrl()."\n";
		}
		echo "COUNT=".count($videos)."\n";
		exit(0);
	}

	function editlist($callback = '')
	{
		$member_id = $this->Session->read('Auth.Member.member_id');
		$this->set("member_id", $member_id);

		$video_enabled = $this->member_limits($member_id, 'video_enabled');
		if (!$video_enabled)
		{
			$this->require_upgrade("display your video", "/memer_videos/editlist");
		}


		# Do we get any session variables back to restore or link to?

		if (isset($_REQUEST['csid']))
		{
			$this->Youtube->restoreSession($_REQUEST['csid']);
		}

		$video = null;
		$session_token = $this->Session->read("youtube_session_token");

		$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id'");
		$this->set("video", $video);

		if (!$callback)
		{
			#
			if (!$session_token) { 
				$this->Youtube->storeSession(); # Save for loading back (know which user we are)
				$url = $this->get_url();
				$this->set("authenticationUrl", $this->Youtube->getAuthenticationUrl("$url/authenticated?csid=".session_id())); 
				$this->action = "editlist_authenticate";
				return;
			} # Else, already authenticated, so show upload page.

		} else if ($callback == 'authenticated') { # Processing, then show upload page.
			$session_token = $this->Youtube->getSessionToken();
			$this->Session->write("youtube_session_token", $session_token); 

			$this->Youtube->setSessionToken($session_token);
			$this->Youtube->_initialize(); # called internally by whatever.
		}

		$this->_syncVideos();

		if (!empty($this->data))
		{
			# Doing some operations....
			if (isset($this->data["is_active"]))
			{
				$video_id = $this->data["is_active"];
				$this->MemberVideo->updateAll(array('is_active'=>'0'), "MemberVideo.member_id = '$member_id'"); # Disable the rest.
				$this->MemberVideo->id = $video_id;
				$this->MemberVideo->saveField("is_active", true);
			}
		}

		$this->set("membervideos", $this->MemberVideo->findAll("MemberVideo.member_id = '$member_id'"));
	}

	function _syncVideos()
	{
		$member_id = $this->Session->read('Auth.Member.member_id');
		$this->set("member_id", $member_id);

		$youtube_video_list = $this->Youtube->getVideoList();
		if ($youtube_video_list === false)
		{
			$this->Session->setFlash("Cannot connect to YouTube: " . $this->Youtube->getError());
			$this->redirect("/members/view");
		}
		$all_videos = $this->MemberVideo->findAll("MemberVideo.member_id = '$member_id'");
		$extra_youtube_videos = array();
		$extra_local_videos = array();

		# Get list of youtube video id's.
		# loop through db entries, if not in youtube list, remove.
		# if found, remove from youtube list.
		# whatever's left over needs to be added.

		$youtube_video_ids = array();
		$youtube_video_object = array();
		foreach($youtube_video_list as $youtube_video)
		{
			$video_id = $youtube_video->getVideoId();
			$youtube_video_ids[] = $video_id;
			$youtube_video_object[$video_id] = $youtube_video;
		}

		$db_video_ids = array();
		$db_video_id_keys = array();

		$active_video_id = null;

		foreach ($all_videos as $each_video)
		{
			$video_id = $each_video['MemberVideo']['youtube_video_id'];
			$db_video_ids[] = $video_id;
			$db_video_id_keys[$video_id] = $each_video['MemberVideo']['member_video_id'];
			if ($each_video['MemberVideo']['is_active']) { $active_video_id = $each_video['MemberVideo']['youtube_video_id']; }
			# We use the youtube_video_id so we can properly compare lists.
		}

		$new_youtubes = array_diff($youtube_video_ids, $db_video_ids);
		$old_db_videos = array_diff($db_video_ids, $youtube_video_ids);

		# Remove old videos. (esp ones without youtube id's set yet)
		foreach($old_db_videos as $video_id)
		{
			$key = $db_video_id_keys[$video_id];
			if ($active_video_id == $video_id) { $active_video_id = null; }
			$this->MemberVideo->del($key);
		}


		# Add new videos.
		foreach ($new_youtubes as $new_youtube_video_id)
		{
			# If no existing 'is_active', set this one as one! (so at least have something load!)

			$is_active = false;
			if (!$active_video_id)
			{
				$is_active = true;
				$active_video_id = $new_youtube_video_id;
			}

			$video_entry = array(
				'member_id'=>$member_id,
				'is_active'=>$is_active,
				'youtube_video_id'=>$new_youtube_video_id,
				'title'=>$youtube_video_object[$new_youtube_video_id]->getVideoTitle(),
				'description'=>$youtube_video_object[$new_youtube_video_id]->getVideoDescription(),
			);

			$this->MemberVideo->create();
			$this->MemberVideo->save(array('MemberVideo'=>$video_entry));

		}

		# Set default if none left.
		if (!$active_video_id)
		{
			$video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id'");
			#print_r($video);
			$video['MemberVideo']['is_active'] = 1;
			$this->MemberVideo->save($video);
		}

		# If more than one set active, clear all the rest too.
		$active_video = $this->MemberVideo->find("MemberVideo.member_id = '$member_id' AND is_active = 1");
		$active_video_exclude = null;

		if ($active_video)
		{
			$active_member_video_id = $active_video['MemberVideo']['member_video_id'];
			$active_video_exclude = " AND member_video_id != '$active_member_video_id'";
		}

		$this->MemberVideo->updateAll(array('is_active'=>'0'), "MemberVideo.member_id = '$member_id' $active_video_exclude");

	}

	function flag($id = '')
	{
		$current_is_admin = $this->Session->read("Auth.Member.is_admin");

		if ($id == '')
		{
			$this->Session->setFlash("Invalid video.");
			$this->redirect("/members/view"); 
		}
		$video = $this->MemberVideo->read(null, $id);
		$member_id = $video['MemberVideo']['member_id'];
		$member = $video['Member'];

		$flag_count = $video['MemberVideo']['flag_count'];
		$flag_count++;

		if ($current_is_admin || $flag_count >= $this->appconfig["max_flag_count"])
		{
			# Delete and notify.
			# Since we cannot delete remotely off of youtube, we can only disable the video.
			$this->MemberVideo->set("flag_count", $flag_count);
			$this->MemberVideo->set("disabled", "1");
			$this->MemberVideo->set("is_active", "0"); # Not one to show up....
			$this->MemberVideo->save();

			$this->sendEmail($member, "FirstFashionSite.com Inappropriate Video Content", "video_removed", array("video"=>$video));
		} else { # Just save flag status.
			$this->MemberVideo->set("flag_count", $flag_count);
			$this->MemberVideo->save();
		}

		$this->set("member_id", $member_id);
	}


}
?>
