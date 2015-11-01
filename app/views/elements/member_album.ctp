<div id="member_album" class="member_album_viewer">
	<div id="member_album_top_left"></div>
	<div id="member_album_top"></div>
	<div id="member_album_top_right"></div>

	<div id="member_album_content">
	<? 
	if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
		echo "<div class='editlink'>[<a href='/member_photos/editlist'>Edit Photos</a>]</div>";
	}
	?>
	<h2>My Gallery</h2>
	   <?
	   echo $ajax->Javascript->event('window','load',
	        $ajax->remoteFunction(
	                array('url'=>"/memberPhotos/viewlist/$member_id", 'update'=>"photo_list")
	        )
	   );
	
	   ?>
   	   <div id="photo_list"></div>
	</div>

	<div id="member_album_bottom_left"></div>
	<div id="member_album_bottom"></div>
	<div id="member_album_bottom_right"></div>

</div>
