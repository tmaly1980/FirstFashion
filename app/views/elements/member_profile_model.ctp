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
			$about_me = preg_replace("/(\w{32})/", "\\1 ", $member['MemberModelProfile']['about_me']);

			if ($url = $member['MemberModelProfile']['website']) {
				if (!preg_match("/^(\w+:\/\/)/", $url)) { $url = "http://$url"; }
			}

			echo $html->tableCells(array(
				array(
					"<label>Member ID:</label>".$member['Member']['username']."&nbsp;",
					"<label>Gender:</label>".$member['MemberModelProfile']['gender']."&nbsp;",
				),
				array(
					"<label>Gender:</label>".$member['MemberModelProfile']['gender']."&nbsp;",
					"<label>Measurements:</label>".$member['MemberModelProfile']['measurements']."&nbsp;",
				),
				array(
					"<label>Height:</label>".$member_height."&nbsp;",
					"<label>Weight:</label>".
						($member['MemberModelProfile']['weight'] ? $member['MemberModelProfile']['weight'] . " lbs." : "")."&nbsp;",
				),
				array(
					"<label>Hair Color:</label>".$member['MemberModelProfile']['hair_color']."&nbsp;",
					"<label>Eye Color:</label>".$member['MemberModelProfile']['eye_color']."&nbsp;",

				),
				array(
					"<label>Ethnicity:</label>".$member['MemberModelProfile']['ethnicity']."&nbsp;",
					"<label>Skin Tone:</label>".$member['MemberModelProfile']['skintone']."&nbsp;",
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
					"<label>Portfolio:</label> ". ($resumeExists ? "<a target='_new' href='/member_resumes/view/$member_id'>View Portfolio</a>" : "<i>No portfolio available</i>") . "&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>Availability:</label>". $member['MemberModelProfile']['availability'] ."&nbsp;",
					array('colspan'=>2))),
				array(array(
					"<label>About Me:</label>
						<div class='about_me'>".$about_me."</div>",
					array('colspan'=>2))),
			));
		?>
		</table>

