<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweany is distributed in the hope that it will be useful,
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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Abstract parent for layout controller
 */
abstract class LayoutController extends BaseController
{

	/* ***************************************************** VARIABLES ***************************************************** */

	/*
	 * Defines the type of the controller
	* page, layout or block.
	* This is used to tell the language class,
	* which section to use
	*/
	protected $ctrl_type = 'layout';


	/*
	 * @deprecated
	private $blocks	= array();	// pre-rendered blocks (if any)
	*/


	/* ***************************************************** CONSTRUCTOR ***************************************************** */

	public function __construct()
	{
		parent::__construct();


		// default Layout
		$this->view($GLOBALS['DEFAULT_LAYOUT']);
	}




	/* ***************************************************** SETTER ***************************************************** */

	/**
	 *
	 * Attach blocks to the layout and return the return value
	 * of the block function itself.
	 *
	 * @param string $varName
	 * @param string $blockPluginName
	 * @param string $blockControllerName
	 * @param string $blockMethodName
	 * @param array  $blockMethodParams
	 * @return mixed
	 */
	/*
	 * @deprecated
	protected function attachBlock($varName, $blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams = array())
	{
		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )
			$start = getmicrotime();

		$output = Render::block($blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams);

		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )
			SysLog::i('Attach Block', '(Done) | [to Layout] from: '.$blockPluginName.'\\'.$blockControllerName.'::'.($blockControllerName).'->'.$blockMethodName, null, $start);

		// 08) store block into array
		$this->blocks[$varName]	= $output['content'];

		return $output['return'];
	}
	*/


	/* ***************************************************** GETTER ***************************************************** */
	/*
	 * @deprecated
	public function getBlocks()
	{
		return $this->blocks;
	}
	*/

}
