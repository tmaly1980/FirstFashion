   	<div>
	<?
	if (isset($featured_models) && count($featured_models))
	{
		foreach($featured_models as $model) 
		{
		?>
	   	<div class="user">
	       	<div class="photo"><a href="/members/view/<?= $model['MemberFeaturedModel']['member_id'] ?>"><img src="/member_photos/view_primary/small/<?= $model['MemberFeaturedModel']['member_id'] ?>" /></a></div>
	           <div class="name"><a href="/members/view/<?= $model['MemberFeaturedModel']['member_id'] ?>"><?= $model['Member']['firstname'] . " " . $model['Member']['lastname'] ?></a></div>
		   <? if ($model['Member']['calculated_age'] > 0) { ?>
	           <div class="info"><?= $model['Member']['calculated_age'] ?> yrs old<br />
		   <? } ?>
		   <? 
		   	$city = $model['Member']['city']; 
			$state = $model['Member']['state'];
			if ($city) { echo $city; }
			if ($city && $state) { echo ", "; }
			if ($state) { echo $state; }
	           ?></div>
	           <div class="link"><a href="/members/view/<?= $model['Member']['member_id'] ?>">view my portfolio »</a></div>
	       </div>
	       <div class="clear"></div>
	       <?
	       }
       } else { # No featured_models.
		echo "<p align=center>No featured models at this time.</p>";
       }
       $paginator->options(array('update' => 'featured_models_list','url'=>$this->passedArgs));
       ?>

	<div class="pager">
	<?
		# not removing the old paginator....
		# not showing 4th person!
		if (count($featured_models) > 0)
		{
			echo $paginator->prev('« ', null, null, array('class' => 'disabled'));
			echo $paginator->counter();
			echo $paginator->next(' »', null, null, array('class' => 'disabled'));
		}
	?>
	</div>
       </div>
