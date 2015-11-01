<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>First Fashion<? if (isset($title_for_layout)) { echo ": $title_for_layout"; } ?></title>

<link rel="stylesheet" type="text/css" href="/css/stylesheet.css" />

<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="/css/ie6_style.css" />
<![endif]-->

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="/css/ie_style.css" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="/css/autoload.php" />
<?

	echo $scripts_for_layout;
?>



<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>

<body onload="MM_preloadImages('/img/models_over.jpg','/img/actors_over.jpg','/img/photographers_over.jpg','/img/agencies_over.jpg','/img/forums_over.jpg','/img/about_over.jpg')">
<div id="wrapper">
	<div id="top_nav">
		<div id="logo"><a href="index.html"><img src="/img/logo.jpg" /></a></div>
		<div id="member_message">
		<? if ($logged_in && isset($member)) { ?>
		Welcome back, <a href="/members/view/<?= $member['member_id'] ?>"><?= $member['firstname'] ?></a>.  You have 5 new views.
		<? } ?>
		</div>
		<div id="menu">
		<?= $this->element('menu', array()); ?>
		</div>
	</div>
    <? if (!$logged_in && $page != 'members/signup' && $page != 'members/login') { echo $this->element('login_header', array()); } ?>

    <div id="page" class="<?= $controller . '_' . $action . '_page'; ?>">
    	<div id="content" class="<?= $controller . '_' . $action . '_content'; ?>">
	<? if (isset($header_for_layout)) { echo "<h2>$header_for_layout</h2>"; } ?>
	<?php
		if ($session->check('Message.flash')):
				$session->flash();
		endif;
	?>
	<?= $content_for_layout ?>


      </div>
	<? if ($page != 'members/login' && $page != 'members/signup') { echo $this->element('right_section', array()); } ?>
    	</div>
  </div>
</div>
<div id="footer"><div id="footer_con"><div id="footer_left">This site and its contents are copyright © 2008 First Fashion Site. All rights reserved.  <a href="#">Privacy Statement</a>.  <a href="#">Sitemap</a><br />
Reproduction in whole or in part in any form or medium without express written permission of First Fashion® is prohibited.</div><div id="footer_right"><a href="#"><img src="/img/footer_logo.jpg" /></a></div></div></div>
</body>
</html>
