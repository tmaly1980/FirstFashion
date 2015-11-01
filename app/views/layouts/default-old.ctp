<?php
/* SVN FILE: $Id: default.ctp 7118 2008-06-04 20:49:29Z gwoo $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 7118 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-04 13:49:29 -0700 (Wed, 04 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('First Fashion: '); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');

		echo $html->css('cake.generic');
		echo $html->css('firstfashion');
		#echo $html->css('lightbox');
		#echo $html->css('lightbox2/lightbox');
		echo $html->css('lightwindow/lightwindow');
		echo $javascript->link('firstfashion', true);

		echo $javascript->link('prototype.js', true);
		echo $javascript->link('scriptaculous.js?load=effects', true);

		#echo $javascript->link('builder.js', true);
		#echo $javascript->link('lightbox.js', true);
		#echo $javascript->link('lightbox.js', true);

		#echo $javascript->link('jquery.js', true);
		#echo $javascript->link('lightbox2/auto_image_handling.js', true);
		#echo $javascript->link('lightbox2/lightbox.js', true);
		#echo $javascript->link('lightbox2/lightbox.js', true);

		#echo $javascript->link('lightwindow/prototype.js', true);
		#echo $javascript->link('lightwindow/scriptaculous.js?load=effects', true);
		echo $javascript->link('lightwindow/lightwindow.js', true);

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<table border="0" cellpadding="0" cellspacing="0" id="header">
		  <tr>
		    <td rowspan=2 id="logo">
		    	<a href="/"><img src="/images/ff_logo.png"></a>
		    </td>
		    <td id="usermenu">
		    	<?php echo $this->element("usermenu"); ?>
		    </td>
		  </tr>
		  <tr>
		    <td id="menu">
		    	<? echo $this->element("sitenav"); ?>
		    </td>
		  </tr>
		</table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td id="content">
				<?php
					if ($session->check('Message.flash')):
							$session->flash();
					endif;
				?>

				<?php echo $content_for_layout; ?>

			</td>
			<td class="ad_sidebar">
				<?php echo $this->element("ad_sidebar"); ?>
			</td>
		</tr>
		</table>
	</div>
</body>
</html>
