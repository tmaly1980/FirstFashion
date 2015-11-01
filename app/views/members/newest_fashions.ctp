   	<div>
	<?
	if (isset($newest_fashions) && count($newest_fashions))
	{
		foreach($newest_fashions as $model) 
		{
		?>
		<table class="user">
		<tr>
	       	<td class="photo">
			<a href="/members/view/<?= $model['NewestFashion']['member_id'] ?>"><img src="/member_photos/view_primary/small/<?= $model['NewestFashion']['member_id'] ?>" /></a>
		</td>

		<td class="member_info">
	           <a href="/members/view/<?= $model['NewestFashion']['member_id'] ?>"><?= $model['NewestFashion']['firstname'] . " " . $model['NewestFashion']['lastname'] ?></a><br/>
		   <? if ($model['NewestFashion']['calculated_age'] > 0) { ?>
	           <?= $model['NewestFashion']['calculated_age'] ?> yrs old<br />
		   <? } ?>
		   <? 
		   	$city = $model['NewestFashion']['city']; 
			$state = $model['NewestFashion']['state'];
			if ($city) { echo $city; }
			if ($city && $state) { echo ", "; }
			if ($state) { echo $state; }
	           ?><br/><br/>
		   	<?
				$about = $model['MemberModelProfile']['about_me'];
				$chars = 100;
				$short_about = (strlen($about) > $chars ? substr($about, 0, $chars) . "... " : $about);
				echo $short_about;
			?>
		   	&nbsp; <a href="/members/view/<?= $model['NewestFashion']['member_id'] ?>">view my portfolio »</a>
		</td>
			<td class="member_type">
		   	<a href="/members/view/<?= $model['NewestFashion']['member_id'] ?>">
				<? if (isset($model['NewestFashion']['member_type'])) { echo $model['NewestFashion']['member_type']; } ?>
				<img src="/images/design/camera_pics.gif">
			</a>
			</td>
		</tr>
	       </table>
	       <?
	       }
       } else { # No newest_fashions.
		echo "<p align=center>No new members at this time.</p>";
       }
       $paginator->options(array('update' => 'newest_fashions_list','url'=>$this->passedArgs));
       ?>

	<div class="pager">
	<?
		# not removing the old paginator....
		# not showing 4th person!
		if (count($newest_fashions) > 0)
		{
			echo $paginator->prev('« ', null, null, array('class' => 'disabled'));
			echo $paginator->counter();
			echo $paginator->next(' »', null, null, array('class' => 'disabled'));
		}
	?>
	</div>
       </div>
