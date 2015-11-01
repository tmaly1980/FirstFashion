<?
/*
	For consolidating payment processing, etc all into one location.

*/

define ('CAKE_COMPONENT_YOUTUBE_SESSION_SAVE_PATH', ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'sessions'); // No trailing slash!

class YoutubeComponent extends Object
{
	var $name = 'Youtube';
	var $yt = null;
	var $controller;
	var $sessionToken = null;
	var $httpClient = null;
	var $applicationId = null;
	var $clientId = null;
	var $developerKey = null;
	var $components = array('Session');
	var $error = null;


	function startup(&$controller)
	{
		$this->controller = $controller;
		App::import('Vendor','google',array('file'=>'google.inc.php'));
		# Adds Google/ to include_path so can 'require' like normal....
		require_once('Zend/Loader.php');
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');

		$application_id = 'First Fashion'; # Not sure if really cross referenced or just arbitrary.
		$client_id = 'ytapi-CypressInternet-FirstFashion-1t9821tt-0';
		$developer_key = 'AI39si6DoBvVYB92nM1mGrLL1pZ66Jpq50W2U5zQtZCuPoRN88f-JvW4eIpW8-uA64icTPVzoVOnkwXLJEwdVRewYvPe5N5NcA';

		# Check to see if current user has session_token...
		$session_token = $this->Session->read("youtube_session_token");
		# It's a good thing we have access to the session! its unreasonable to not have any connection w/controller

		if($session_token)
		{
			$this->setSessionToken($session_token);
		}

		$this->setApplicationId($application_id);
		$this->setClientId($client_id);
		$this->setDeveloperKey($developer_key);

		$this->_initialize(); # $this->yt initialization

	}

	function setSessionToken($session_token)
	{
		$this->sessionToken = $session_token;
		$this->httpClient = Zend_Gdata_AuthSub::getHttpClient($this->sessionToken);
	}

	function setApplicationId($application_id)
	{
		$this->applicationId = $application_id;
	}

	function setClientId($client_id)
	{
		$this->clientId = $client_id;
	}

	function setDeveloperKey($developer_key)
	{
		$this->developerKey = $developer_key;
	}

	function _initialize()
	{
		$this->yt = new Zend_Gdata_YouTube($this->httpClient, $this->applicationId, $this->clientId, $this->developerKey);
	}

	function getThumbnailUrl($video_id) # 128x96
	{
		return "http://img.youtube.com/vi/$video_id/1.jpg";
	}

	function getPreviewUrl($video_id) # 320x240
	{
		return "http://img.youtube.com/vi/$video_id/0.jpg";
	}

	function getFullPageVideoUrl($video_id) # Full page
	{
		return "http://gdata.youtube.com/feeds/api/videos/$video_id";
	}

	function getFlashPlayerVideoUrl($video_id) # Embeddable flash player.  # 425x350 !
	{
		return "http://www.youtube.com/v/$video_id&f=gdata_videos";
	}

	function getVideoContentType($video_id)
	{
	}

	function getVideoEntry($video_id) # meta information....
	{
		$this->_initialize();
		return $this->yt->getVideoEntry($video_id);
	}

	function getVideoFeed() # Can only delete via this specific url
	{
		return $this->yt->getVideoFeed("http://gdata.youtube.com/feeds/api/users/default/uploads");
	}

	function getAuthenticationUrl($next = '')
	{
		if (!$next) { $next = $this->tokenUrl(); } # Unfortunately, misses session info.
	    	$scope = 'http://gdata.youtube.com';
	        $secure = false;
		$session = true;
		return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
	}

	function deleteVideo($video_id)
	{
		#error_log("VIDEO_ID=$video_id\n");
		#$videoEntry = $this->getVideoEntry($video_id); # Could get nothing back...
		#error_log($videoEntry->getVideoTitle());
		#error_log("EDIT AT=".$videoEntry->getEditLink());
		#error_log("VIDEO_ENTRY=".print_r($videoEntry,true));
		#error_log("ABOUT TO DELETE...");

		$videoFeed = $this->getVideoFeed();
		foreach($videoFeed as $videoEntry) { 
			if ($videoEntry->getVideoId() == $video_id) { $this->yt->delete($videoEntry); }
		}
		error_log("DID DELETE...");
	}

	function getVideoList()
	# at http://gdata.youtube.com/feeds/api/users/default/uploads (default === current user)
	{
		$this->_initialize();
		try {
			return $this->yt->getUserUploads('default');
		} catch (Zend_Gdata_App_HttpException $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}

	function getUploadTokenAndUrl($video_meta)
	{
		$entry = new Zend_Gdata_YouTube_VideoEntry();
		$entry->setVideoTitle($video_meta['title']);
		$entry->setVideoDescription($video_meta['description']);
		$entry->setVideoCategory($video_meta['category']);
		$entry->setVideoTags($video_meta['tags']);

		$tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
		$tokenArray = $this->yt->getFormUploadToken($entry, $tokenHandlerUrl);
		$tokenValue = $tokenArray['token'];
		$postUrl = $tokenArray['url'];

		return array($tokenValue, $postUrl);
	}

	function setError($message)
	{
		$this->error = $message;
	}

	function getError($message)
	{
		return $this->error;
	}

	function getSessionToken() # Upon redirect BACK from youtube after log in.
	{
		if (!$_GET['token']) { return; }
		$this->sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
		return $this->sessionToken;
	}


	function tokenUrl()
	{
		$serverName = $_SERVER['SERVER_NAME'];
                $serverPort = $_SERVER['SERVER_PORT'];

                $pathParts = pathinfo($_SERVER['SCRIPT_NAME']);
                $pathInfo = $pathParts['dirname'];

                $tokenUrl = 'http://';

                if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0)
                {
                    $tokenUrl = 'https://';
                }

                $tokenUrl .= $serverName . ($serverPort != 80 ? ':' . $serverPort : '');
                $tokenUrl .= $pathInfo;
                $tokenUrl .= '/' . $_SERVER['SCRIPT_NAME'];

                if (!empty($_SERVER['QUERY_STRING']))
                {
                    $tokenUrl .= '?' . $_SERVER['QUERY_STRING'];
                }

		return $tokenUrl;
	}

    function storeSession()
    {
        $sessionFileHandle = fopen(CAKE_COMPONENT_YOUTUBE_SESSION_SAVE_PATH . DS . session_id() . '.ser.tmp', 'w');
                        
        if ($sessionFileHandle !== false)
        {
            fwrite($sessionFileHandle, serialize($_SESSION));
            fclose($sessionFileHandle);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Restores the specified session.
     * 
     * @param string    Session ID
     * 
     * @return bool    true if able to restore, false otherwise
     * 
     * @access public
     * @since 1.0
     */
    function restoreSession($session_id)
    {
        if (preg_match('/^[A-Za-z0-9]*$/', $session_id))
        {
            $sessionFile = CAKE_COMPONENT_YOUTUBE_SESSION_SAVE_PATH . DS . $session_id . '.ser.tmp';
            
            if (@file_exists($sessionFile) && @is_file($sessionFile) && @is_readable($sessionFile) && @filesize($sessionFile) > 0)
            {
                $contents = file_get_contents($sessionFile);
                $oldSession = @unserialize($contents);
                
                if (is_array($oldSession) && count($oldSession) > 0)
                {
                    foreach($oldSession as $id => $value)
                    {
                        $this->Session->write($id, $value);
                    }
                }
                
                @unlink($sessionFile);
                
                return true;
            }
        }
        
        return false;
    }

}

?>
