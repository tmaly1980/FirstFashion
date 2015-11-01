<?php 
/* SVN FILE: $Id: ad_fixture.php 7588 2008-09-09 18:51:28Z phpnut $ */
/**
 * Short description for ad_fixture.php
 * 
 * Long description for ad_fixture.php
 * 
 * PHP versions 4 and 5
 * 
 * CakePHP(tm) : Rapid Development Framework <http://www.cakephp.org/>
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @filesource
 * @copyright            CakePHP(tm) : Rapid Development Framework <http://www.cakephp.org/>
 * @link                 http://www.cakephp.org
 * @package              cake
 * @subpackage           cake.tests.fixtures
 * @since                1.2
 * @version              $Revision: 7588 $
 * @modifiedBy           $LastChangedBy: phpnut $
 * @lastModified         $Date: 2008-09-09 13:51:28 -0500 (Tue, 09 Sep 2008) $
 * @license              http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * AdFixture class
 * 
 * @package              cake
 * @subpackage           cake.tests.fixtures
 */
class AdFixture extends CakeTestFixture {
/**
 * name property
 * 
 * @var string 'Ad'
 * @access public
 */
	var $name = 'Ad';    
/**
 * fields property
 * 
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'campaign_id' => array('type' => 'integer'),
		'parent_id' => array('type' => 'integer'),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false)
	);
/**
 * records property
 * 
 * @var array
 * @access public
 */
	var $records = array(
		array('parent_id' => null, 'lft' => 1,  'rght' => 2,  'campaign_id' => 1, 'name' => 'Nordover'),
		array('parent_id' => null, 'lft' => 3,  'rght' => 4,  'campaign_id' => 1, 'name' => 'Statbergen'),
		array('parent_id' => null, 'lft' => 5,  'rght' => 6,  'campaign_id' => 1, 'name' => 'Feroy'),
		array('parent_id' => null, 'lft' => 7, 'rght' => 12,  'campaign_id' => 2, 'name' => 'Newcastle'),
		array('parent_id' => null, 'lft' => 8,  'rght' => 9,  'campaign_id' => 2, 'name' => 'Dublin'),
		array('parent_id' => null, 'lft' => 10, 'rght' => 11, 'campaign_id' => 2, 'name' => 'Alborg'),
		array('parent_id' => null, 'lft' => 13, 'rght' => 14, 'campaign_id' => 3, 'name' => 'New York')
	);
} 

?>