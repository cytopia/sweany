<?php

class Layouts extends LayoutController
{
	public function FrontPage()
	{
//		$this->attachBlock();
//		$this->set();
		$this->view('frontpage.tpl.php');
	}
}