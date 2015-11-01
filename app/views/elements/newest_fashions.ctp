<div id="newest_fashions">
   <div id="newest_fashions_content">
   <h2>Newest Fashions</h2>
   <?
   echo $ajax->Javascript->event('window','load',
   	$ajax->remoteFunction(
		array('url'=>"/members/newest_fashions", 'update'=>"newest_fashions_list")
	)
   );

   ?>
   <div id="newest_fashions_list"></div>
   </div>
</div>
