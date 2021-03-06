<?php
/* SVN FILE: $Id: overloadable.test.php 7348 2008-07-21 02:40:58Z mark_story $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.cases.libs
 * @since			CakePHP(tm) v 1.2.0.5432
 * @version			$Revision: 7348 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-07-20 21:40:58 -0500 (Sun, 20 Jul 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Overloadable');
/**
 * Short description for class.
 *
 * @package    cake.tests
 * @subpackage cake.tests.cases.libs
 */
class OverloadableTest extends CakeTestCase {
/**
 * skip method
 * 
 * @access public
 * @return void
 */
	function skip() {
		$this->skipif (true, 'OverloadableTest not implemented');
	}
}
?>