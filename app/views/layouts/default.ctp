<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>First Fashion<? if (isset($title_for_layout)) { echo ": $title_for_layout"; } ?></title>

<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="/css/ie6_style.css" />
<![endif]-->

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="/css/ie_style.css" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="/css/thickbox.css" />
<link rel="stylesheet" type="text/css" href="/css/autoload.php" />
<?
	echo $javascript->link('querystring.js', true);
	echo $javascript->link('firstfashion.js', true);

	echo $javascript->link('scriptaculous/prototype.js', true);
	echo $javascript->link('scriptaculous/scriptaculous.js', true);

	#echo $javascript->link('thickbox/jquery.js', true);
	#echo $javascript->codeBlock('jQuery.noConflict();');
	#echo $javascript->link('thickbox/thickbox.js', true);

	echo $javascript->link('shadowbox/build/adapter/shadowbox-base.js', true);
	echo $javascript->link('shadowbox/build/adapter/shadowbox-prototype.js', true);
	echo $javascript->link('shadowbox/build/shadowbox.js', true);

	echo $javascript->codeBlock('Shadowbox.loadSkin("classic", "/js/shadowbox/src/skin");');
	echo $javascript->codeBlock('Shadowbox.loadLanguage("en", "/js/shadowbox/build/lang");');
	echo $javascript->codeBlock('Shadowbox.loadPlayer(["img","html","iframe"], "/js/shadowbox/build/player");');

	#echo $javascript->codeBlock('Shadowbox.init();');
	# Line above breaks IE since body not loaded yet!

	echo $scripts_for_layout;
?>

</head>

<body onLoad=" checkForJSAction(); Shadowbox.init(); ">
	<?= $this->element("topheader", $this->viewVars); ?>
	
    	<div id="page" class="<?= $controller . '_' . $action . '_page'; ?>">
		<div id="content_row">
		<div id="content_row_inner">
		<? if ($controller != 'forums' && $page != 'members/login' && !preg_match('/members\/signup/', $page) && $page != 'members/model_register') { ?>
	            <div id="right_column">
	               	  <div id="search">
			  <form method="POST" action="/members/search">
	                    <label>Member Search</label>
	                    <input type="text" name="data[Member][keyword]" id="criteria" maxlength="23">
			    <a id="advanced_link" href="/members/search_advanced">Advanced Search</a>
			  </form>
	               	  </div>
			  <?= $this->element("adbanner", array('section'=>'sidebar')); ?>
			  <?= $this->element("adbanner", array('section'=>'rotating_sidebar','max_ads'=>4)); ?>

	            </div>
		  <? } ?>
    		<div id="content" class="<?= $controller . '_' . $action . '_content'; ?>">
			<?= $this->element("trail_nav",$this->viewVars); ?>

			<? if (isset($header_for_layout)) { echo "<h2>$header_for_layout</h2>"; } ?>
			<?php
				if ($session->check('Message.flash')):
						$session->flash();
				endif;
			?>
			<?= $content_for_layout ?>
			<div class="clear"></div>
		</div>
		</div>
		</div>

	</div>
	
	<div class="clear"></div>
	<?= $this->element("footer", $this->viewVars); ?>

</body>
</html>
