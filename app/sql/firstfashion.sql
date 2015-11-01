DROP TABLE IF EXISTS ff_members;
CREATE TABLE IF NOT EXISTS ff_members
(

	member_id	INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	email		VARCHAR(60) NOT NULL,
	username	VARCHAR(32) NOT NULL,
	password	VARCHAR(128), # Since md5/sha1 encrypted!

	firstname	VARCHAR(32),
	lastname	VARCHAR(32),
	city		VARCHAR(32),
	state		VARCHAR(2),

	birthdate	DATE, # For calculating age.

	registration_date	TIMESTAMP,
	active		BOOL DEFAULT TRUE, # for whatever reason, disabled -- default until email link click....
	
	member_type	ENUM('model','stylist','photographer','agent'),

	INDEX		(email),
	INDEX		(username),
	INDEX		(firstname, lastname),
	FULLTEXT	(username,firstname,lastname)
);

ALTER TABLE ff_members ADD is_admin BOOL NOT NULL DEFAULT FALSE;

ALTER TABLE ff_members ADD flag_count INTEGER UNSIGNED NOT NULL DEFAULT '0';

# tracking session_id so can lock out when banned, etc
ALTER TABLE ff_members ADD session_id VARCHAR(64);

ALTER TABLE ff_members CHANGE member_type member_type ENUM('model','stylist','photographer','agent','designer');

ALTER TABLE ff_members CHANGE member_type member_type ENUM('model','stylist','photographer','agency','designer');

ALTER TABLE ff_members CHANGE member_type member_type ENUM('model','hair/makeup','photographer','agency','designer');

ALTER TABLE ff_members CHANGE member_type member_type ENUM('model','hair/makeup','photographer','agency','designer') NOT NULL DEFAULT 'model';


ALTER TABLE ff_members CHANGE registration_date registration_date DATETIME; # So not changing every update!

ALTER TABLE ff_members ADD membership_level ENUM('basic','premium') NOT NULL DEFAULT 'basic', ADD membership_upgrade_date DATE, ADD membership_level_transaction_id VARCHAR(255) NOT NULL; 
# membership level meanings WILL vary between member types (ie premium model different than premium photographer!)

ALTER TABLE ff_members ADD views INTEGER UNSIGNED NOT NULL DEFAULT '0'; # How many times a member has been looked at...
# Incremented every time /members/view/X is loaded (NOT WHEN VIEW SELF!!!!)





DROP TABLE IF EXISTS ff_member_videos;
CREATE TABLE IF NOT EXISTS ff_member_videos
(
#ALTER TABLE ff_members ADD youtube_token VARCHAR(255), ADD youtube_token_expire DATE; 
# To ensure logged on to youtube before providing upload/management link...
	member_video_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id	INTEGER UNSIGNED NOT NULL,
	title		VARCHAR(255),
	description	TEXT,
	youtube_video_id	VARCHAR(255), # Unique id for specific video...
	is_active	BOOL DEFAULT FALSE # only one can be active at a time...

);

ALTER TABLE ff_member_videos ADD flag_count INTEGER UNSIGNED NOT NULL DEFAULT '0', ADD disabled BOOL NOT NULL DEFAULT FALSE;

# Sample data.... (for latest videos section)
# WARNING: once you go to the members edit video page, it will sync with youtube and erase these dummy entries!!!!
INSERT INTO ff_member_videos SET member_video_id = 20, member_id = 19, title = 'Member Video Sample', is_active = 1, youtube_video_id = 'dw8wT7Pb7vE';
INSERT INTO ff_member_videos SET member_video_id = 21, member_id = 18, title = 'Member Video Sample 2', is_active = 1, youtube_video_id = 'N_cc77xek7U';
INSERT INTO ff_member_videos SET member_video_id = 22, member_id = 9, title = 'Member Video Sample 3', is_active = 1, youtube_video_id = 'x7N0bcolv54';
INSERT INTO ff_member_videos SET member_video_id = 23, member_id = 3, title = 'Member Video Sample 4', is_active = 1, youtube_video_id = 'KGr4XJ7zLng';
INSERT INTO ff_member_videos SET member_video_id = 24, member_id = 6, title = 'Member Video Sample 5', is_active = 1, youtube_video_id = '-AcH55MPJUE';
INSERT INTO ff_member_videos SET member_video_id = 25, member_id = 21, title = 'Member Video Sample 6', is_active = 1, youtube_video_id = 'ktG0ELCK7eo';
INSERT INTO ff_member_videos SET member_video_id = 26, member_id = 22, title = 'Member Video Sample 7', is_active = 1, youtube_video_id = 'N_cc77xek7U';
INSERT INTO ff_member_videos SET member_video_id = 27, member_id = 13, title = 'Member Video Sample 8', is_active = 1, youtube_video_id = 'KGr4XJ7zLng';


##############
DROP TABLE IF EXISTS ff_member_resumes;
CREATE TABLE IF NOT EXISTS ff_member_resumes
(
	resume_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id	INTEGER UNSIGNED UNIQUE KEY,
	comments	TEXT,
	mimetype	VARCHAR(255), # for specifying header type (so know what to do with)...
	ext		VARCHAR(5), # pdf, doc, etc... for recreating filename (should be sensible FirstFashion-First-Last-Resume.EXT
	data		MEDIUMBLOB # Max length = 16mb
);

DROP TABLE IF EXISTS ff_member_model_profiles;
CREATE TABLE IF NOT EXISTS ff_member_model_profiles
(
	member_id	INTEGER UNSIGNED PRIMARY KEY,
	gender		ENUM('Male','Female'),
	height		INTEGER(3), # Stored in inches, so can convert to cm.
	weight		INTEGER(3), # lbs
	eye_color	ENUM('Blue','Brown','Green','Grey','Hazel','Other'),
	hair_color	ENUM('Black','Blonde','Dark Blonde','Light Brown','Brown','Dark Brown','Red','Grey/White','Other'),
	measurements	VARCHAR(8), # BB-WW-HH (bust-waist-hips)
	ethnicity	ENUM('African-American','Asian','Caucasian','Hispanic','Native American','Mixed','Other'),
	skintone	ENUM('Fair','Pale','Bronze','Freckled'),

	website		VARCHAR(64),
	about_me	TEXT,
	since_experience	DATE, # For calculating years experience...

	availability	VARCHAR(64)
);

DROP TABLE IF EXISTS ff_member_photographer_profiles;
CREATE TABLE IF NOT EXISTS ff_member_photographer_profiles
(
	member_id	INTEGER UNSIGNED PRIMARY KEY,
	studio_city	VARCHAR(32),
	studio_state	VARCHAR(2),
	website		VARCHAR(64),
	about_me	TEXT,
	since_experience	DATE # For calculating years experience...
);


DROP TABLE IF EXISTS ff_member_agency_profiles;
CREATE TABLE IF NOT EXISTS ff_member_agency_profiles
(
	member_id	INTEGER UNSIGNED PRIMARY KEY,
	phone	VARCHAR(32),
	website		VARCHAR(64),
	about_me	TEXT,
	since_experience	DATE # For calculating years experience...
);

ALTER TABLE ff_member_agency_profiles ADD address VARCHAR(255);


DROP TABLE IF EXISTS ff_member_hair_makeup_profiles;
CREATE TABLE IF NOT EXISTS ff_member_hair_makeup_profiles
(
	member_id	INTEGER UNSIGNED PRIMARY KEY,
	employer_name	VARCHAR(64),
	website		VARCHAR(64),
	about_me	TEXT,
	since_experience	DATE # For calculating years experience...
);

DROP TABLE IF EXISTS ff_member_designer_profiles;
CREATE TABLE IF NOT EXISTS ff_member_designer_profiles
(
	member_id	INTEGER UNSIGNED PRIMARY KEY,
	employer_name	VARCHAR(64),
	website		VARCHAR(64),
	about_me	TEXT,
	since_experience	DATE # For calculating years experience...
);

ALTER TABLE ff_member_photographer_profiles ADD gender ENUM('Male','Female') DEFAULT 'Male';
ALTER TABLE ff_member_agency_profiles ADD gender ENUM('Male','Female') DEFAULT 'Male';
ALTER TABLE ff_member_hair_makeup_profiles ADD gender ENUM('Male','Female') DEFAULT 'Male';
ALTER TABLE ff_member_designer_profiles ADD gender ENUM('Male','Female') DEFAULT 'Male';



DROP TABLE IF EXISTS ff_member_photos;
CREATE TABLE IF NOT EXISTS ff_member_photos
(
	photo_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id	INTEGER UNSIGNED,
	ext		VARCHAR(5),
	album_id	INTEGER UNSIGNED DEFAULT '0',
	album_order	INTEGER UNSIGNED, # What order to put it in when showing album....
	is_primary	BOOL DEFAULT FALSE, # Whether show up on profile page in big...
	title		VARCHAR(32),
	comment		TEXT

	# Stored on disk as:
	# /images/members/[member_id]/[album_id]/large/[photo_id].[ext] # (The originals uploaded)
	# /images/members/[member_id]/[album_id]/medium/[photo_id].[ext]
	# /images/members/[member_id]/[album_id]/small/[photo_id].[ext]

);

ALTER TABLE ff_member_photos ADD flag_count INTEGER UNSIGNED NOT NULL DEFAULT '0';


DROP TABLE IF EXISTS ff_adbanners;
CREATE TABLE IF NOT EXISTS ff_adbanners
(
	adbanner_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	section		VARCHAR(32),
	ext		VARCHAR(5), # png, jpg, gif, etc.
	adbanner_order	INTEGER UNSIGNED,
	link_url	VARCHAR(255),
	disabled	BOOL NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS ff_member_friends;
CREATE TABLE IF NOT EXISTS ff_member_friends
(
	friend_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	owner_member_id	INTEGER UNSIGNED,
	friend_member_id	INTEGER UNSIGNED,
	authorized	BOOL DEFAULT TRUE, # IN case someday implement required authorization....

	INDEX (owner_member_id),
	INDEX (friend_member_id)
);

ALTER TABLE ff_member_friends ADD approved BOOL DEFAULT FALSE;

DROP TABLE IF EXISTS ff_member_featured_models;
CREATE TABLE IF NOT EXISTS ff_member_featured_models
(
	featured_model_id	INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id		INTEGER UNSIGNED NOT NULL,
	start_date		DATETIME NOT NULL,
	end_date		DATETIME NOT NULL
);

ALTER TABLE ff_member_featured_models ADD transaction_id VARCHAR(255); # For referencing refunds....


# Sample data...
INSERT INTO ff_member_featured_models SET featured_model_id = 1, member_id = 6, start_date = '2008-09-10', end_date = '2008-10-10';
INSERT INTO ff_member_featured_models SET featured_model_id = 2, member_id = 19, start_date = '2008-09-13', end_date = '2008-10-10';
INSERT INTO ff_member_featured_models SET featured_model_id = 3, member_id = 21, start_date = '2008-09-20', end_date = '2008-10-10';
INSERT INTO ff_member_featured_models SET featured_model_id = 4, member_id = 33, start_date = '2008-09-10', end_date = '2008-10-10';
INSERT INTO ff_member_featured_models SET featured_model_id = 5, member_id = 3, start_date = '2008-09-08', end_date = '2008-10-10';
INSERT INTO ff_member_featured_models SET featured_model_id = 6, member_id = 12, start_date = '2008-09-09', end_date = '2008-10-10';

DROP TABLE IF EXISTS ff_member_success_spotlights;
CREATE TABLE IF NOT EXISTS ff_member_success_spotlights
(
	spotlight_id		INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id		INTEGER UNSIGNED NOT NULL,
	start_date		DATE,
	end_date		DATE,
	content			TEXT
);

INSERT INTO ff_member_success_spotlights SET spotlight_id = 1, member_id = 20, start_date = '2008-09-10', end_date = '2009-10-10', content = 'Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est.\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.';

DROP TABLE IF EXISTS ff_member_messages;
CREATE TABLE IF NOT EXISTS ff_member_messages
(
	msg_id		INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id_from	INTEGER UNSIGNED NOT NULL,
	member_id_to	INTEGER UNSIGNED NOT NULL,
	re_msg_id	INTEGER UNSIGNED,
	subject		VARCHAR(255),
	is_read		BOOL NOT NULL DEFAULT FALSE,
	sent_time	DATETIME,
	content		TEXT
);

DROP TABLE IF EXISTS ff_member_sent_messages;
CREATE TABLE IF NOT EXISTS ff_member_sent_messages
(
	msg_id		INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	member_id_from	INTEGER UNSIGNED NOT NULL,
	member_id_to	INTEGER UNSIGNED NOT NULL,
	re_msg_id	INTEGER UNSIGNED,
	subject		VARCHAR(255),
	is_read		BOOL NOT NULL DEFAULT FALSE,
	sent_time	DATETIME,
	content		TEXT
);


DROP TABLE IF EXISTS ff_banned_emails;
CREATE TABLE IF NOT EXISTS ff_banned_emails
(
	banned_email_id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	email		VARCHAR(255) NOT NULL,
	INDEX	(email)
);

DROP TABLE IF EXISTS ff_member_sessions;
CREATE TABLE ff_member_sessions (
  id varchar(255) NOT NULL default '',
  data text,
  expires int(11) default NULL,
  PRIMARY KEY  (id)
);
