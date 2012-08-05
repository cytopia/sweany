<?php
/**
 * URL Helper
 *
 * Sweany: MVC-like PHP Framework with blocks and tables (entities)
 * Copyright 2011-2012, Patu
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.helper
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */

/**
 *  TODO: coreUrl and this class still need to be seperated
 *        and rewritten!
 *
 */
class Url extends \Core\Init\CoreUrl
{
	public static function getController()
	{
		return parent::getController();
	}

	public static function getMethod()
	{
		return parent::getMethod();
	}

	public static function getParams()
	{
		return parent::getParams();
	}
	public static function getRequest()
	{
		return parent::getRequest();
	}

}
