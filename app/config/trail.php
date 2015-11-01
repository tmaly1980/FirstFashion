<?

# Specifies a list of urls to help generate trails...

# Key is regex compatible, so if want to match multiple urls, need to use stuff like (\d+), etc...

return array(
	'/'=>array(
		"title"=>"Home",
	),
	'/members/view'=>array(
		"title"=>"Profile",
		"parent"=>"/",
	),
	'/member_photos/editlist'=>array(
		"title"=>"Gallery",
		"parent"=>"/members/view",
	),
	'/member_photos/edit/(\d+)'=>array(
		"title"=>"Edit Photo",
		"parent"=>"/member_photos/editlist",
	),
	'/member_photos/add'=>array(
		"title"=>"Add Photo",
		"parent"=>"/member_photos/editlist",
	),

);

?>
