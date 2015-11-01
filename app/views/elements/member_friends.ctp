<div id="friends">
   <div id="friends_content">
   <h2>My Friends</h2>
   <?
   echo $ajax->Javascript->event('window','load',
   	$ajax->remoteFunction(
		array('url'=>"/memberFriends/view/$member_id", 'update'=>"friends_list")
	)
   );

   ?>
   <div id="friends_list"></div>
   </div>
</div>
