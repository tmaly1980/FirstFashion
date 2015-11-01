<?
/*
general stuff, like site-wide thresholds, etc...

*/

return array(
	# thresholds
	#'admin_email'=>'admin@firstfashionsite.com', # Send emails from, to.
	#'admin_email'=>'tomas@localhost', # Send emails from, to.
	#'admin_email'=>'admin',#localhost', # Send emails from, to.
	'admin_email'=>'admin@'.$_SERVER['HTTP_HOST'],#localhost', # Send emails from, to.

	'max_flag_count'=>5, # How many times something (photo, video, account) needs to be flagged before automatic removal

	'max_featured'=>12, # will sort randomly, so everyone gets a fair shot at the front page.
		# 3 per page, 4 pages.
	'featured_days'=>14, # listing is active for two weeks.
	'featured_price'=>'29.99',

);
?>
