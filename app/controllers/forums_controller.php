<?php


class ForumsController extends AppController
# IFRAME wrapper for phpBB3
{
	var $uses = array(); # DONT load a 'Forums' model!

	function beforeFilter()
	{
		parent::beforeFilter();
	}

	function index()
	{
	}

}

