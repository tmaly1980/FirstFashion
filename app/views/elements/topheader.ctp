	<div id="top">
		<div id="top_left">
			<div id="logo"><a href="/"><img src="/images/design/ff_logo.gif" /></a></div>
		</div>
		<div id="top_right">
			<div id="member_message">
				<? if ($logged_in && isset($current_member)) { ?>
				Welcome back, <?= $current_member['firstname'] ?> | <a href="/members/view/<?= $current_member['member_id'] ?>">My Profile</a> |
				<!-- You have 5 new views. -->
				<a href="/members/logout">Logout</a>
				<? } else { ?>
				Not logged in. <a href="/members/login">Login Now</a>
				<? } ?>
			</div>
			<div id="topmenu">
			        <a href="/">home</a>
			        <a href="/members/browse/model">models</a>
			        <a href="/members/browse/photographer">photographers</a>
			        <a href="/members/browse/designer">designers</a>
			        <a href="/members/browse/hair_makeup">hair/makeup</a>
			        <a href="/members/browse/agency">agencies</a>
			        <a href="/pages/about">about ff</a>
			</div>
		</div>
		<div class="clear"></div>

    		<? if (!$logged_in && (!preg_match("/members\/signup/", $page) && $page != 'members/model_register')) { echo $this->element('header', array()); } ?>
	</div>

