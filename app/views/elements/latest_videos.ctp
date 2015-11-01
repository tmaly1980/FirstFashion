<div id="latest_videos" class="latest_video_viewer">
	<div id="latest_videos_top_left"></div>
	<div id="latest_videos_top"></div>
	<div id="latest_videos_top_right"></div>

	<div id="latest_videos_content">
	<h2>Latest Videos</h2>
	   <?
	   echo $ajax->Javascript->event('window','load',
	        $ajax->remoteFunction(
	                array('url'=>"/memberVideos/view_latest", 'update'=>"latest_videos_list")
	        )
	   );
	
	   ?>
   	   <div id="latest_videos_list"></div>
	</div>

	<div id="latest_videos_bottom_left"></div>
	<div id="latest_videos_bottom"></div>
	<div id="latest_videos_bottom_right"></div>

</div>
