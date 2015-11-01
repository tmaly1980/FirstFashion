<div class="navbar">
	<? echo $html->link("Home","/members"); ?> |
	<? echo $html->link("Browse","/members/browse"); ?> |
	<? echo $html->link("Search","/members/search"); ?> |

	<? # If logged in, provide "My Profile" and "Logout" instead... ?>

	<?
		#$user = $this->Auth->user();
		$user = $session->read("Auth.Member.username");
		#print_r($session->read());
		if ($user)
		{
			echo $html->link("My Portfolio","/members/view"); ?> | <?
			echo $html->link("Edit Portfolio","/members/edit"); ?> | <?
			echo $html->link("Logout","/members/logout"); 
		} else {
			echo $html->link("Login","/members/login"); 
		}
	?>
</div>
