<?php

class Terms extends PageController
{
	protected $have_model = false;
	
	public function index()
	{
		$this->view('index');
	}
}