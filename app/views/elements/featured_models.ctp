<div id="featured_models">
   <div id="featured_models_content">
   <h2>Featured Models</h2>
   <?
   $seed = $firstfashion->randseed();
   echo $ajax->Javascript->event('window','load',
   	$ajax->remoteFunction(
		array('url'=>"/memberFeaturedModels/view/$seed", 'update'=>"featured_models_list")
	)
   );

   ?>
   <div id="featured_models_list"></div>
   </div>
</div>
