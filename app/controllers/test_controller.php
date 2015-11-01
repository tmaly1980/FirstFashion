<?php

class TestController extends AppController
{
	var $uses = array();
	var $helpers = array('Javascript','Ajax');

	function beforeFilter()
	{
		$this->Auth->allow('*');
	}

	function showvars()
	{
		header('Content-Type: text/plain');
		echo "APP=". APP ."\n";
		echo "APP_PATH=". APP_PATH ."\n";
		echo "WWW_ROOT=". WWW_ROOT ."\n";
		echo "CONFIG=". CONFIGS ."\n";
		echo "BASE=". $this->base."\n";
		exit(0);
	}

	function test()
	{
		$this->layout = 'tester';
		$this->set("cantent", "Ding dong the witch is dead");
	}

	function viewImage()
	{
		$this->layout = 'image';
		$this->set('src', "005.png");
	}

	function url()
	{
		header('Content-type: text/plain');
		echo print_r($this->params,true);
		exit(0);
	}

	function ajax()
	{
		$this->layout = 'popup';
	}

	function ajax_query()
	{
		$this->layout = 'xml';
		# Return values....
		header('Content-Type: text/xml');
		$time = time();
		echo "<div>Hello $time</div>";
		exit(0);
	}

}

?>
