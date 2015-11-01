<div id="success_spotlight">
   <div id="success_spotlight_content">
   <? if ($current_is_admin) { ?>
   	<div id="spotlight_admin_link" class="">
		[<a rel="shadowbox;width=450;height=450;player=iframe" href="/admin/member_success_spotlights">Manage List</a>]
	</div>
   <? } ?>
   <h2>Success Spotlight</h2>
   <div id="success_spotlight_list"></div>
   </div>
   <?
   echo $ajax->Javascript->event('window','load',
   	$ajax->remoteFunction(
		array('url'=>"/member_success_spotlights/view", 'update'=>"success_spotlight_list")
	)
   );

   ?>
</div>
