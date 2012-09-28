<?php

class Terms extends PageController
{
	protected $hasModel = false;
	
	public function index()
	{
		$this->view('index');
	}
}