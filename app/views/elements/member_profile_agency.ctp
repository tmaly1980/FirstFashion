		<? 
			if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
				echo "<div class='editlink'>[<a href='/member_profiles/edit'>Edit Profile</a>]</div>";
				echo "<div class='clear'></div>";
			}
		?>
		<table width="100%" class="profile_data" cellpadding=0 cellspacing=0>
		<?
			$url = "";
			$email = $member['Member']['email'];
			$about_me = preg_replace("/(\w{32})/", "\\1 ", $member['MemberAgencyProfile']['about_me']);

			if ($url = $member['MemberAgencyProfile']['website']) {
				if (!preg_match("/^(\w+:\/\/)/", $url)) { $url = "http://$url"; }
			}

			echo $html->tableCells(array(
				array(
					"<label>Member ID:</label>".$member['Member']['username']."&nbsp;",
				),
				array(array(
					"<label>Address:</label>". $member['MemberAgencyProfile']['address'] . "&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>Phone:</label>". $member['MemberAgencyProfile']['phone'] . "&nbsp;",
					array('colspan'=>2)),
				),
				array(
					"<label>Views:</label>".$member['Member']['views']."&nbsp;",
					"<label>Since:</label>".$time->format('m/d/Y', $member['Member']['registration_date'])."&nbsp;",
				),
				array(array(
					"<label>Email:</label>" . ($email ? "<a href='mailto:$email'>".$email."</a>" : "")."&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>Website:</label>" . ($url ? "<a href='$url' target='_new'>".$url."</a>" : "")."&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>Years Experience:</label>". $member_years_experience."&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>About Me:</label>
						<div class='about_me'>".$about_me."</div>",
					array('colspan'=>2))),
			));
		?>
		</table>

