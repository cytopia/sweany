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
 * @package		sweany.core.pages
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 * * Internal Controller holding 404, robots and note/inform actions */class FrameworkDefault extends PageController{	protected $have_model = false;	public function url_not_found($request = null)	{		switch ( $_SERVER['REQUEST_URI'] )		{			case '/robots.txt':			{				$this->render = false;				$handle = @fopen(ROOT.DS.'robots.txt', 'r');				$output	= '';				if ($handle)				{					while ( ($output .= fgets($handle, 4096)) !== false );				}				fclose($handle);				return $output;			}			default:			{				// VIEW VARIABLES				$this->language->setCore('notFound');				$this->set('language', $this->language);				$this->set('url', $request);				// VIEW OPTIONS				$this->view('url_not_found.tpl.php');			}		}	}	public function info_message()	{		if ( !Session::exists('info_message_data') )		{			$this->redirectHome();			return;		}		$info	= Session::get('info_message_data');		$title	= $info['title'];		$body	= $info['body'];		$url	= $info['url'];		$delay	= $info['delay'];		$this->language->setCore('redirect');		$this->set('language', $this->language);		$this->set('title', $title);		$this->set('body', $body);		$this->set('url', $url);		$this->set('delay', $delay);		$this->view('info_message.tpl.php');		if (\Core\Init\CoreSettings::$showFwErrors)		{			echo '<font color="red">Delayed Redirect Call: </font><a href="'.$url.'">'.$url.'</a> in '.$delay.' seconds [automatic redirect disabled during debug mode]';		}		else		{			HtmlTemplate::setRedirect($url, $delay);		}		Session::del('info_message_data');	}	public function change_settings($key = null, $value = null)	{	}}