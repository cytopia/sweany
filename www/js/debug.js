/**
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
 * @package		javascript
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-15 13:25
 *
 *
 * CSS Debugging Helper
 */

/*
 *  php like rand function
 */
function rand(from, to)
{
	return Math.floor(Math.random() * (to - from + 1) + from);
}

/*
 *  decimal to hex
 */
function d2h(d)
{
	return d.toString(16);
}

/*
 * colorize Divs
 */
function debugDiv()
{
	var divs = document.getElementsByTagName("div");

	for (var i = 0; i < divs.length; i++)
	{
		divs[i].style.backgroundColor = "#" + d2h(rand(0,16))+d2h(rand(0,16))+d2h(rand(0,16));
	}
}

