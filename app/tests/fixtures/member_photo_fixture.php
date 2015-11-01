<?php 
/* SVN FILE: $Id$ */
/* MemberPhoto Fixture generated on: 2008-08-07 12:08:52 : 1218127972*/

class MemberPhotoFixture extends CakeTestFixture {
	var $name = 'MemberPhoto';
	var $table = 'member_photos';
	var $fields = array(
			'photo_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'member_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'ext' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 5),
			'album_id' => array('type'=>'integer', 'null' => true, 'default' => '0', 'length' => 10),
			'album_order' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'is_primary' => array('type'=>'boolean', 'null' => true, 'default' => '0'),
			'title' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 32),
			'comment' => array('type'=>'text', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'photo_id', 'unique' => 1))
			);
	var $records = array(array(
			'photo_id'  => 1,
			'member_id'  => 1,
			'ext'  => 'Lor',
			'album_id'  => 1,
			'album_order'  => 1,
			'is_primary'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'comment'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,
									phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,
									vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,
									feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.
									Orci aliquet, in lorem et velit maecenas luctus, wisi nulla at, mauris nam ut a, lorem et et elit eu.
									Sed dui facilisi, adipiscing mollis lacus congue integer, faucibus consectetuer eros amet sit sit,
									magna dolor posuere. Placeat et, ac occaecat rutrum ante ut fusce. Sit velit sit porttitor non enim purus,
									id semper consectetuer justo enim, nulla etiam quis justo condimentum vel, malesuada ligula arcu. Nisl neque,
									ligula cras suscipit nunc eget, et tellus in varius urna odio est. Fuga urna dis metus euismod laoreet orci,
									litora luctus suspendisse sed id luctus ut. Pede volutpat quam vitae, ut ornare wisi. Velit dis tincidunt,
									pede vel eleifend nec curabitur dui pellentesque, volutpat taciti aliquet vivamus viverra, eget tellus ut
									feugiat lacinia mauris sed, lacinia et felis.'
			));
}
?>