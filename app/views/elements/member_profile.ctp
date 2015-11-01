<div id="profile" class="model">
<div id="profile_inner">

         <div id="profile_left">
            	<div id="member_img">
			<a rel="shadowbox;options={overlayOpacity: 0.95}" href="/member_photos/view_primary/large/<?= $member['Member']['member_id'] ?>.jpg">
			<img src="/member_photos/view_primary/medium/<?= $member['Member']['member_id'] ?>">
			</a>
			<!--	<img src="/img/8.jpg" /> -->
		</div> 
                <div id="member_gen">
			<?  if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
				echo "<div class='editlink'>[<a href='/members/edit'>Edit Account</a>]</div>";
			} ?>
                	<h3><a href="/members/view/<?= $member['Member']['member_id'] ?>"><? if(isset($member['Member']['firstname']) && isset($member['Member']['lastname'])) { echo $member['Member']['firstname'] . " " . $member['Member']['lastname']; } ?></a></h3>
                        <h4><? if (isset($member_years_old) && $member_years_old > 0) { echo $member_years_old . " yrs old"; } ?><br />
                        <? if (isset($member['Member']['city']) && isset($member['Member']['state'])) { echo $member['Member']['city'] . ", " . $member['Member']['state']; } ?></h4> 
                </div>  
		<div id="member_type">
			<h3><? if (isset($member['Member']['member_type'])) { echo $member['Member']['member_type']; } ?></h3>
		</div>
		<div id="controls">
		<?
			$already_friend = false;
			$pending_friend = false;

			# I have a hunch this WONT update while still logged in.... !!!!
			#$current_member_friends = $session->read("Auth.MemberFriend");
			# (instead, it's passed from controller/view)

			#error_log("MYFRIENDS=".print_r($current_member_friends,true));

			$my_member_id = $session->read("Auth.Member.member_id");
			$their_member_id = $member['Member']['member_id'];
			$is_me = ($my_member_id == $their_member_id);
	    	if ($member['Member']['member_type'] != 'agency') {

			if (!$is_me && isset($member['MemberFriend']) && count($member['MemberFriend']))
			{
				foreach($member['MemberFriend'] as $their_friend)
				{
					if ($their_friend['friend_member_id'] == $current_member_id
						&& !$their_friend['approved'])
					{
						$pending_friend = true;
						break;
					}

				}

			}

			if (!$is_me && isset($current_member_friends) && count($current_member_friends))
			{
				foreach($current_member_friends as $my_friend)
				{
					if ($my_friend['MemberFriend']['friend_member_id'] == $their_member_id)
					{
						$already_friend = true;
						break;
					}
				}
			}

			?><a href="/members/flag/<?= $member['Member']['member_id'] ?>"><img src="/images/design/flag_member.jpg"></a><?

		}

			if (!$is_me) {
	    			if ($member['Member']['member_type'] != 'agency') {

				if (!$pending_friend) {

					if (!$already_friend) { 
						?> <a href="/memberFriends/add/<?= $member['Member']['member_id'] ?>"><img src="/images/design/friend_add.jpg"></a> <? 
					} else { 
				 		?> <a href="/memberFriends/delete/<?= $member['Member']['member_id'] ?>"><img src="/images/design/friend_remove.jpg"></a> <? 
					}
				} 
				else # ($pending_friend)
				{
				?>
					<a href="/memberFriends/approve/<?= $member['Member']['member_id'] ?>"><img src="/images/design/friend_approve.jpg"></a>
					<a href="/memberFriends/reject/<?= $member['Member']['member_id'] ?>"><img src="/images/design/friend_reject.jpg"></a>
				<?
				}

				}
				?>

				<!--<a rel="shadowbox;width=500;height=350;player=iframe" href="/member_messages/send/<?= $member['Member']['member_id'] ?>"><img src="/images/design/message.jpg"></a>-->
				<a href="/member_messages/send/<?= $member['Member']['member_id'] ?>"><img src="/images/design/message.jpg"></a>
			<? } ?>
		</div>

            </div>


            <div id="profile_right">
	    	<? 
		if ($member['Member']['member_type'] == 'model') { 
	    		echo $this->element("member_profile_model", $this->viewVars);
	    	} else if ($member['Member']['member_type'] == 'photographer') { 
	    		echo $this->element("member_profile_photographer", $this->viewVars);
	    	} else if ($member['Member']['member_type'] == 'hair/makeup') { 
	    		echo $this->element("member_profile_hair_makeup", $this->viewVars);
	    	} else if ($member['Member']['member_type'] == 'designer') { 
	    		echo $this->element("member_profile_designer", $this->viewVars);
	    	} else if ($member['Member']['member_type'] == 'agency') { 
	    		echo $this->element("member_profile_agency", $this->viewVars);
	    	} 
		
		?>

            </div>
</div>
</div>
