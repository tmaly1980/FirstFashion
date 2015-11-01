   	<div>
	<?
	if (isset($friends) && count($friends))
	{
		foreach($friends as $friend) 
		{
		?>
	   	<div class="user">
	       	<div class="photo"><a href="/members/view/<?= $friend['MemberFriend']['friend_member_id'] ?>"><img src="/member_photos/view_primary/small/<?= $friend['MemberFriend']['friend_member_id'] ?>" /></a></div>
	           <div class="name"><a href="/members/view/<?= $friend['MemberFriend']['friend_member_id'] ?>"><?= $friend['Friend']['firstname'] . " " . $friend['Friend']['lastname'] ?></a></div>
		   <? if ($friend['Friend']['calculated_age'] > 0) { ?>
	           <div class="info"><?= $friend['Friend']['calculated_age'] ?> yrs old<br />
		   <? } ?>
		   <? 
		   	$city = $friend['Friend']['city']; 
			$state = $friend['Friend']['state'];
			if ($city) { echo $city; }
			if ($city && $state) { echo ", "; }
			if ($state) { echo $state; }
	           ?></div>
	           <div class="link"><a href="/members/view/<?= $friend['MemberFriend']['friend_member_id'] ?>">view my portfolio »</a></div>
	       </div>
	       <?
	       }
       } else { # No friends.
       	if (isset($member) && $current_member_id != "" && $current_member_id == $member['Member']['member_id'])
	{
		echo "<p align=center>To add friends, go to the desired member's page and click on 'Add to Friends'.";

	} else { # Not me, show other message
		echo "<p align=center>This member has no friends.";
	}
       }
       $paginator->options(array('update' => 'friends_list','url'=>$this->passedArgs));
       ?>

	<div class="pager">
	<?
		# not removing the old paginator....
		# not showing 4th person!
		if (count($friends) > 0)
		{
			echo $paginator->prev('« ', null, null, array('class' => 'disabled'));
			echo $paginator->counter();
			echo $paginator->next(' »', null, null, array('class' => 'disabled'));
		}
	?>
	</div>
       </div>
