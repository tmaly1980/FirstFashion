<?
// Set path to PEAR files

define('PEAR_PATH', dirname(__FILE__) . DS . 'PEAR');

// Add PEAR path to library path

set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());
?>
