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
 * @package		sweany.core.pages
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Internal Controller holding 404, robots and note/inform actions
 */
class FrameworkDefault extends PageController
{
	public $isCore 		= true;
	protected $hasModel	= false;

	public function url_not_found($request = null)
	{
		switch ( $_SERVER['REQUEST_URI'] )
		{
			case '/robots.txt':
			{
				$this->render = false;

				$handle = @fopen(ROOT.DS.'robots.txt', 'r');
				$output	= '';

				if ($handle)
				{
					while ( ($output .= fgets($handle, 4096)) !== false );
				}
				fclose($handle);
				return $output;
			}
			default:
			{
				// VIEW VARIABLES
				$this->language->setCore('notFound');
				$this->set('language', $this->language);
				$this->set('url', $request);

				// VIEW OPTIONS
				$this->view('url_not_found');
			}
		}
	}

	public function info_message()
	{
		if ( !Session::exists('info_message_data') )
		{
			$this->redirectHome();
			return;
		}

		$info	= Session::get('info_message_data');
		$title	= $info['title'];
		$body	= $info['body'];
		$url	= $info['url'];
		$delay	= $info['delay'];

		$this->language->setCore('redirect');
		$this->set('language', $this->language);
		$this->set('title', $title);
		$this->set('body', $body);
		$this->set('url', $url);
		$this->set('delay', $delay);
		$this->view('info_message');

		if (\Sweany\Settings::$showFwErrors)
		{
			echo '<font color="red">Delayed Redirect Call: </font><a href="'.$url.'">'.$url.'</a> in '.$delay.' seconds [automatic redirect disabled during debug mode]';
		}
		else
		{
			HtmlTemplate::setRedirect($url, $delay);
		}

		Session::del('info_message_data');
	}


	public function change_settings($key = null, $value = null)
	{
		// Do not render this function (no view, no layout)
		$this->render = false;

		switch ($key)
		{
			case 'lang':

				if ( !$GLOBALS['LANGUAGE_ENABLE'] )
				{
					\Sweany\SysLog::e('built-in', $GLOBALS['DEFAULT_SETTINGS_URL'], 'Language Module is not activated, cannot change Language to: '.$value);
					$this->redirectBack();
					exit;
				}

				if ( !in_array($value, array_keys($GLOBALS['LANGUAGE_AVAILABLE'])) )
				{
					\SysLog::e('built-in', $GLOBALS['DEFAULT_SETTINGS_URL'], 'Language: <b>'.$value.'</b> does not exist.');
					$this->redirectBack();
					exit;
				}

				\Sweany\SysLog::i('built-in', $GLOBALS['DEFAULT_SETTINGS_URL'], 'Changing Language to: '.$value);

				/*
				 * Set language to the core part of the xml file
				 * This is only needed for the language switcher
				 * as the define is in core/
				 */
				\Sweany\Language::changeLanguage($value);


				/*
				 * Note: language is available in every function and ready to
				 * use without specifying a section. As every function has its
				 * own block in the xml file.
				 *
				 * The language switcher function (this one here) will be moved to
				 * framework core anyway, so there is no need to wonder here
				 */
				$this->language->setCore('changeLanguage');

				// get title and text from language xml
				$title	= $this->language->title;
				$body	= $this->language->text;

				// On access of this function, redirect to the page, the user came from
				// Delay 5 seconds and display headline and text
				$this->redirectDelayedBack($title, $body, 5);

				break;

			default:
				// There is nothing to see here.
				// Go away
				$this->redirectHome();
				exit();
				break;
		}
	}
}
