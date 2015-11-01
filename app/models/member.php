<?php
class Member extends AppModel {

	var $name = 'Member';
	var $primaryKey = 'member_id';
	var $limits;

	var $hasOne = array(
			'MemberModelProfile' => array('className' => 'MemberModelProfile',
								'foreignKey' => 'member_id',
								'dependent' => false,
								'conditions' => 'member_type = "model"',
								'fields' => '',
								'order' => ''
			),
			'MemberAgencyProfile' => array('className' => 'MemberAgencyProfile',
								'foreignKey' => 'member_id',
								'dependent' => false,
								'conditions' => 'member_type = "agency"',
								'fields' => '',
								'order' => ''
			),
			'MemberPhotographerProfile' => array('className' => 'MemberPhotographerProfile',
								'foreignKey' => 'member_id',
								'dependent' => false,
								'conditions' => 'member_type = "photographer"',
								'fields' => '',
								'order' => ''
			),
			'MemberHairMakeupProfile' => array('className' => 'MemberHairMakeupProfile',
								'foreignKey' => 'member_id',
								'dependent' => false,
								'conditions' => 'member_type = "hair/makeup"',
								'fields' => '',
								'order' => ''
			),
			'MemberDesignerProfile' => array('className' => 'MemberDesignerProfile',
								'foreignKey' => 'member_id',
								'dependent' => false,
								'conditions' => 'member_type = "designer"',
								'fields' => '',
								'order' => ''
			),
			# is there a way to create a generic 'Profile' key for either type?
	);
	var $hasMany = array(
			'MemberPhoto' => array('className' => 'MemberPhoto',
						'foreignKey' => 'member_id',
						'dependent' => false,
						'conditions' => '',
						'fields' => '',
						'order' => ''
			),
			'MemberFriend' => array('className' => 'MemberFriend',
						'foreignKey' => 'owner_member_id',
						'dependent' => false,
						'conditions' => '',
						'fields' => '',
						'order' => ''
			)

	);

	function get_height_name($i)
	{
		if ($i == 0 || $i == '') { return ''; }
		$ft = intval($i / 12);
		$in = intval($i % 12);
		$cm = intval($i * 2.54);
		return "$ft'$in\" ($cm cm)";
	}

	function get_height()
	{
		$heightlist = array();
		$start_ft = 4;
		$end_ft = 8;
		$ftin = 12;
		for($i = $start_ft*$ftin; $i < $end_ft*$ftin; $i++)
		{
			$heightlist[$i] = $this->get_height_name($i);
		}
		return $heightlist;
	}

	function get_years_experience($date)
	{
		error_log("DATE=$date");
		if ($date == '' || $date == 0) { return ''; } # Dont let it be empty.
		# Format in terms of years, months (so nicer!)
		$secs_since = time() - strtotime($date);
		$months_since = intval($secs_since / 60 / 60 / 24 / 7 / 52 * 12);

		error_log("DATE=$date, MONTHS=$months_since");

		$output_string = "";

		if ($months_since > 12)
		{
			$years_since = intval($months_since / 12);
			$months_since = intval($months_since - $years_since * 12);
			$output_string .= "$years_since years";
		}
		if ($months_since > 0)
		{
			if ($output_string != "") { $output_string .= ", "; }
			$output_string .= "$months_since months";
		}
		error_log("YM=$output_string");

		return $output_string;
	}

	function get_age($date)
	{
		if ($date == 0 || $date == '') { return ''; } # Epoch doesnt count!
		# Format in terms of years, months (so nicer!)
		$secs_old = time() - strtotime($date);
		$years_old = intval($secs_old / 60 / 60 / 24 / 7 / 52);
		return $years_old;
	}

	function generate_password() # A new random one.
	{
		return $this->generate_code(8);
	}

	function generate_code($length = 8) # Random codes, whether password, registration code, etc...
	{
		$chars = array();
		for ($i = ord('a'); $i < ord('z'); $i++)
		{
			$chars[] = chr($i);
		}
		for ($i = ord('A'); $i < ord('Z'); $i++)
		{
			$chars[] = chr($i);
		}
		for ($i = ord('0'); $i < ord('9'); $i++)
		{
			$chars[] = chr($i);
		}

		shuffle($chars); # randomize.

		$code = "";
		for ($ix = 0; $ix < $length; $ix++)
		{
			$code .= $chars[ rand(0, count($chars)-1) ];
		}

		return $code;
	}

	# Checks member profile for ability (boolean) and limits (numerical, etc)
	# Returns hash for member provided....
	function member_limits($member)
	{
		#print_r($member);
		if (empty($member) || empty($member['Member'])) { return array(); }

		if (!$this->limits)
		{
			$this->limits = include_once(dirname(__FILE__) . "/../config/member_limits.conf.php");
		}
		# Searches for their membership level, (depends on member_type too!)
		$membership_type = $member['Member']['member_type'];
		$membership_level = $member['Member']['membership_level'];

		# Need to merge ALL values! type-level, type-sublevels, etc...

		$membership_levels = $this->getEnumValues("membership_level");
		if (!$membership_level) { $membership_level = $membership_levels[0]; }

		$own_limits = array();

		foreach ($membership_levels as $mlevel)
		{
			$mlevel = strtolower($mlevel);
			if (isset($this->limits['default'][$mlevel]))
			{
				$default_level_limits = $this->limits['default'][$mlevel];
				foreach ($default_level_limits as $var => $value)
				{
					$own_limits[$var] = $value;
				}
			}

			if(isset($this->limits[$membership_type][$mlevel]))
			{
				$type_level_limits = $this->limits[$membership_type][$mlevel];
				foreach ($type_level_limits as $var => $value)
				{
					$own_limits[$var] = $value;
				}
			}

			# Compile limits up until current level. Higher level settings take precedence, but 
			# lower level settings STILL do apply (if not overriden in higher level)

			if ($mlevel == $membership_level)
			{
				break;
			}
		}

		return $own_limits;
	}

	function get_member_type_class($member_id)
	{
		$member = $this->find("Member.member_id = '$member_id'");
		$type = $member['Member']['member_type'];
		$modelclass = 'MemberModelProfile'; # Default...
		if ($type == 'model')
		{
			$modelclass = 'MemberModelProfile';
		} else if ($type == 'photographer') {
			$modelclass = 'MemberPhotographerProfile';
		} else if ($type == 'hair/makeup') {
			$modelclass = 'MemberHairMakeupProfile';
		} else if ($type == 'agency') {
			$modelclass = 'MemberAgencyProfile';
		}
		return $modelclass;
	}
}
?>
