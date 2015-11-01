<div id="header_wrapper">
<div id="header">
    	<div id="site_functions">
        	<span id="sign_up"><a href="/members/signup"><img src="/img/sign_up.jpg" /></a></span>
	    <form method="POST" action="/members/login">
            <div id="login">

	    	<label for="login_email">Email:</label>
	    	<input id="login_email" name="data[Member][email]" onClick="this.value='';" type="text" value=""/>

	    	<label for="login_password">Password:</label>
	    	<input id="login_password" name="data[Member][password]" type="password" value=""/>

	    	<input type=submit name="submit" id='submit' value="Login">
		
		<span id="forgot"><a href="/members/forgot">forgot password?</a></span>
            </div>
	    </form>
        </div>
        <div id="site_info">
		<? if (isset($content_file)) { 
			echo $this->element($content_file);
		} else { ?>
        	<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing.
Sed et orci. Maecenas in leo et dolor!</h3>
			<p>Aliquam sit amet ante eget pede dignissim malesuada. Class aptent 
taciti torquent per conubia nostra, per inceptos himenaeos. 
Praesent rutrum enim!  Sit amet ante eget pede dignissim malesuada. 

<br /><br />Class aptent taciti <a href="#">sociosqu</a> ad.</p>

		<? } ?>
        </div>
</div>
</div>
