    	  <div id="profile" class="model">
	  	<div id="profile_top_right_corner"></div>
	  	<div id="profile_bottom_left_corner"></div>
	  	<div id="profile_bottom_right_corner"></div>
	  	<div id="profile_right_border"></div>
	  <div id="profile_inner">

          	<div id="profile_left">
            	<div id="member_img"><img src="/img/8.jpg" /></div> 
                <div id="member_gen">

                	<h3><a href="/members/view/2">Amanda Sample</a></h3>
                        <h4>18 yrs old<br />
                        Philadelphia, PA</h4> 
                </div>  
		<div id="member_type">
			<h3>model</h3>
		</div>
		<div id="controls">
		CONTROLS TODO
		</div>

            </div>
            <div id="profile_right">
			<? 
			if ($firstfashion->isOwnProfile()) { # Own, add edit link. 
				echo "<div class='clear editlink'>[<a href='/member_model_profiles/edit'>Edit Stats</a>]</div>";
			}
			?>
            <p>
            	<strong>Height:</strong> 5'2" (157 cm)<br />

                <strong> Measurements:</strong> 32-29-31<br />
                <strong>Weight:</strong> 10 lbs (5 kg)<br />
                <strong>                Hair Color:</strong> Dark Brown<br />
                <strong>Eye Color:</strong> Brown<br />
                <strong>Website:</strong> www.somesite.com<br />
                <strong>Member ID:</strong> AmandaBillingy<br /><br /><br />


               <strong>About Me:               </strong> <br /><br /><br />   <br /><br />          <strong>Experience:               </strong>  <br /><br /><br /><br />            <strong>Availability:</strong> </p>
            </div>
          </div>
	  </div>
