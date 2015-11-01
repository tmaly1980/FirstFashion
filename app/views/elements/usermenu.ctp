<div class="topnavbar">
	<?
		$user = $session->read("Auth.Member.username");
		#print_r($session->read());
		if ($user)
		{
			echo "Welcome, $user | ";
			echo $html->link("My Portfolio","/members/view"); ?> | <?
			echo $html->link("Logout","/members/logout"); 
		} else {
			echo $html->link("Login","/members/login"); ?> | <? 
			echo $html->link("Signup", "/members/signup");
		}
	?>
</div>
