<div id="friends">
   <div id="friends_top"></div>
   <div id="friends_content">
   <h2>My Friends</h2>
	<?  if (false && $firstfashion->isOwnProfile()) { # Own, add edit link. 
		echo "<div class='clear editlink'>[<a href='/member_friends/edit'>Edit Friends</a>]</div>";
		echo "<div class='clear'></div>";
	} ?>
   	<div class="user">
       	<a href="#"><img src="/img/4.jpg" /></a>
           <h3><a href="#">Sally Sample</a></h3>
           <h4>18 yrs old<br />
           Charlotte, NC</h4>
           <p><a href="#">view my profile »</a></p>
       </div>
       <div class="user">
       	<a href="#"><img src="/img/5.jpg" /></a>
           <h3><a href="#">Christie Sample</a></h3>
           <h4>22 yrs old<br />
           Los Angeles, CA</h4>
           <p><a href="#">view my profile »</a></p>
     </div>
       <div class="user">
       	<a href="#"><img src="/img/6.jpg" /></a>
           <h3><a href="#">Mark Sample</a></h3>
           <h4>25 yrs old<br />
           New York, NY</h4>
           <p><a href="#">view my profile »</a></p>
       </div>

	<div class="pager">
	       	<a class="left" href="#">&laquo;</a>
	       	<a class="right" href="#">&raquo;</a>
	</div>
   </div>
   <div id="friends_bottom"></div>
</div>
