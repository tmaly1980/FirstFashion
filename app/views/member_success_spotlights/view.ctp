   	<div>
	<?
	if (isset($success_spotlight['Member']))
	{
		?>
	   	<div class="user">
	       	<div class="photo">
			<a href="/members/view/<?= $success_spotlight['Member']['member_id'] ?>"><img src="/member_photos/view_primary/smallmedium/<?= $success_spotlight['Member']['member_id'] ?>" /></a>
		</div>

		<div class="name">
	        <h3>
			<a href="/members/view/<?= $success_spotlight['Member']['member_id'] ?>"><?= $success_spotlight['Member']['firstname'] . " " . $success_spotlight['Member']['lastname'] ?></a>
		</h3>
		<div>
			member since <?= $member_since ?>
		</div>
		</div>

		<p class="spotlight_content">
			<? 
				$content = $success_spotlight['MemberSuccessSpotlight']['content']; 
				$content = preg_replace("/[\r\f\n]/", "<br/>\n", $content); 
				# Translate line breaks into html breaks.
				echo $content;
			?>
		</p>
	        <p class="spotlight_link">
			<a href="/members/view/<?= $success_spotlight['Member']['member_id'] ?>">view my portfolio »</a>
		</p>
	       </div>
	       <?
       } else { # No success_spotlight.
		echo "<br/><br/><p align=center>No spotlight members at this time.</p>";
       }

       if ($current_is_admin)
       {
       }
       ?>

       

       </div>
