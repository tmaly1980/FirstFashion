<div id="latest_talk">
   <div id="latest_talk_content">
   <h2>Latest Talk</h2>
   <?
   echo $ajax->Javascript->event('window','load',
   	$ajax->remoteFunction(
		array('url'=>"/members/latest_talk", 'update'=>"latest_talk_list")
	)
   );

   ?>
   <div id="latest_talk_list"></div>
   </div>
</div>
