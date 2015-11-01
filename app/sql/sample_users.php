<?php

# Gives hash object of sample member information for member auto-generation....

return array(
	'Member'=>array(
		'email'=>null, # Should auto gen from 'username'@$_SERVER['HTTP_HOST']
		'username'=>null, # Should auto gen from 'FirstLastInit'
		'password'=>null, # Should be set to 'firstfashion1', encrypted
		'firstname'=>array(
			'Kevin',
			'Sally',
			'Chris',
			'Michael',
			'Travis',
			'Derek',
			'Amanda',
			'Elizabeth',
			'Judith',
			'Rachel',
			'Karen',
		),
		'lastname'=>array(
			'Sample',
			'Model',
			'Member',
			'Example',
			'Person',
			'Schmoe'
		),
		'city'=>array(
			'Philadelphia',
			'New York',
			'Somewhereville',
			'Little City',
			'Toontown',
			'Hollywood',
			'Silver City',
			'Jacksonville',
		),
		'state'=>array(
			'PA',
			'CA',
			'AZ',
			'NY',
			'MD',
			'FL',
			'MA'
		),
		'birthdate'=>array(
			'1980-10-22',
			'1978-09-12',
			'1983-11-23',
			'1987-12-18',
			'1981-03-22',
			'1976-05-14',
			'1982-08-19',
			'1981-11-21',
			'1984-02-09',
			'1980-09-03',
			'1981-12-01',
		),
		'registration_date'=>array(
			'2006-01-02',
			'2006-03-12',
			'2004-09-21',
			'2005-11-09',
			'2008-10-02',
			'2007-07-13',
			'2008-09-22',
		),
		'active'=>1,
		'member_type'=>'model'
	),
	'MemberModelProfile'=>array(
		'gender'=>array('Male','Female'),
		'height'=>array(
			66,
			70,
			72,
			62,
			65,
			71,
			69
		),
		'weight'=>array(
			225,
			112,
			160,
			135,
			140,
			122
		),
		'eye_color'=>array(
			'Blue',
			'Brown',
			'Hazel',
			'Green',
			'Grey'
		),
		'hair_color'=>array(
			'Black',
			'Blonde',
			'Dark Blonde',
			'Light Brown',
			'Brown',
			'Dark Brown',
			'Red',
			'Grey/White',
			'Other'
		),
		'measurements'=>array(
			'36-22-34',
			'32-22-34',
			'36-32-30',
			'38-28-34',
			'36-26-32',
		),
		'ethnicity'=>array(
			'African-American',
			'Asian',
			'Caucasian',
			'Hispanic',
			'Native American',
			'Mixed',
			'Other'
		),
		'skintone'=>array(
			'Fair',
			'Pale',
			'Bronze',
			'Freckled'
		),
		'website'=>array(
			'www.myfirstsite.com',
			'www.site.com',
			'www.home.com',
			'www.domain.com',
			'www.google.com',
		),
		'about_me'=>array(
			'Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est',
			'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
			'Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet',
		),
		'since_experience'=>array(
			'2008-01-01',
			'2005-06-01',
			'2007-02-01',
			'2006-11-01',
			'2002-12-01',
			'2001-04-01',
			'1997-03-01',
		),
		'availability'=>array(
			'M-F 9-5',
			'Weekends Only',
			'3pm - 9pm',
			'12am - 9am',
			'M,W,F 2pm - 6pm'
		),
	),

	'MemberPhoto'=>array(
		'file'=>array(
			"1.jpg",
			"2.jpg",
			"3.jpg",
			"4.jpg",
			"5.jpg",
			"6.jpg",
			"7.jpg",
			"8.jpg",
		),
	),

);

?>
