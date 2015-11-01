<?

/*
 * PHPBB_Login allows you to integrate your own login system
 * with phpBB. Meaning that you can have one login valid across
 * both your website and phpBB.
 *
 * To take full advantage of this PHPBB_Login class you just
 * need to modify your own login system to include a call
 * to the relevant methods in here.
 *
 * This system is reliant on the website username being exactly
 * the same as the phpBB username. To insure this, I recommend
 * disabling the ability to change usernames from within the 
 * phpBB admin control panel.
 *
 * Distributed under the LGPL license:
 * http://www.gnu.org/licenses/lgpl.html
 *
 * Duncan Gough
 * 3rdSense.com
 *
 * Home  http://www.suttree.com
 * Work  http://www.3rdsense.com
 * Play! http://www.playaholics.com
 */

class PHPBB_Login {

    function PHPBB_Login() {
    }

    function login( $phpbb_user_id, $username, $password ) {
        global $db, $board_config;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( 'phpBB3/config.php' );
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpasswd = $dbpasswd;
		$this->table_prefix = $table_prefix;
		$this->check_user($phpbb_user_id, $username, $password);
		
        define('IN_PHPBB',true);

        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "phpBB3/";
        
	# XXX extension.inc doesnt exist! not needed?
        #require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        return session_begin( $phpbb_user_id, $user_ip, PAGE_INDEX, FALSE, TRUE );
    
    }

	function check_user($phpbb_user_id, $username, $password){
        
		$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpasswd); 
		mysql_select_db($this->dbname, $link); 
		$result = mysql_query("SELECT * FROM {$this->table_prefix}users WHERE user_id=$phpbb_user_id",$link); 
		if(mysql_num_rows($result) == 0){ 
			$result = mysql_query("INSERT INTO {$this->table_prefix}users (user_id, username, user_password) VALUES ($phpbb_user_id, '$username', '$password')");
		}
	}

    function logout( $session_id, $phpbb_user_id ) {
        global $db, $lang, $board_config;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( 'phpBB2/config.php' );
    
        define('IN_PHPBB',true);
        
        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "phpBB2/";

        require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        session_end( $session_id, $phpbb_user_id );
    	
        // session_end doesn't seem to get rid of these cookies,
        // so we'll do it here just in to make certain.
        setcookie( $board_config[ "cookie_name" ] . "_sid", "", time() - 3600, " " );
        setcookie( $board_config[ "cookie_name" ] . "_mysql", "", time() - 3600, " " );

    }

}

?>
