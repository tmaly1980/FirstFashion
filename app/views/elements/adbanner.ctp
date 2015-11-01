<div id="<?= $section ?>_adbanner">
	<?
		if ($current_is_admin)
		{
			echo "<div class='clear'></div>";
			echo "<div class='right'>[<a href='/admin/adbanners/editlist/$section'>Edit Ads</a>]</div>";
		}
	?>

	<div id="<?= $section ?>_adbanner_content"></div>

	<?
	if (!isset($max_ads))
	{
		$max_ads = "";
	}
	echo $ajax->Javascript->event('window','load',
		$ajax->remoteFunction(
			array('url'=>"/adbanners/viewlist/$section/$max_ads", 'update'=>"{$section}_adbanner_content")
		)
	);

	?>

</div>
