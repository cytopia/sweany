<?php/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweaby is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25 * * * Abstract parent for block controller */abstract Class BlockController extends BaseController{	/* ***************************************************** VARIABLES ***************************************************** */	/**	 *  This is an overriden variable from the	 *  BaseController and its only function is	 *  to tell the loadModel function, that it should	 *  directly load a block model instead of a normal model	 */	protected $block	= true;	private $blockName	= null;	//public $render = false;	/* ***************************************************** CONSTRUCTOR ***************************************************** */	function __construct()	{		parent::__construct();		$this->blockName	= get_called_class();		$this->language	= new \Core\Init\CoreLanguage($this->plugin, 'block', get_class($this));	}	function __destruct()	{		parent::__destruct();	}}