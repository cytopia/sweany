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
				// Show 404 not found error
				$this->error('404');
			}
		}
	}

	public function info_message()
	{
		if ( !\Sweany\Session::exists(\Sweany\Settings::sessSweany, \Sweany\Settings::sessInfo) )
		{
			$this->redirectHome();
			return;
		}

		$info	= \Sweany\Session::get(\Sweany\Settings::sessSweany, \Sweany\Settings::sessInfo);
		$title	= $info['title'];
		$body	= $info['body'];
		$url	= $info['url'];
		$delay	= $info['delay'];

		// TODO: what if language is disabled??
		$this->core->language->setCore('redirect');
		$this->set('language', $this->core->language);
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

		\Sweany\Session::del(\Sweany\Settings::sessSweany, \Sweany\Settings::sessInfo);
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
					\Sweany\SysLog::w('built-in', $GLOBALS['DEFAULT_SETTINGS_URL'], 'Language Module is not activated, cannot change Language to: '.$value);
					$this->redirectBack();
				}

				if ( !in_array($value, array_keys($GLOBALS['LANGUAGE_AVAILABLE'])) )
				{
					\Sweany\SysLog::w('built-in', $GLOBALS['DEFAULT_SETTINGS_URL'], 'Language: <b>'.$value.'</b> does not exist.');
					$this->redirectBack();
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
				$this->core->language->setCore('changeLanguage');

				// get title and text from language xml
				$title	= $this->core->language->title;
				$body	= $this->core->language->text;

				// On access of this function, redirect to the page, the user came from
				// Delay 5 seconds and display headline and text
				$this->redirectDelayedBack($title, $body, 5);

				break;

			case 'blocks' :
				if ( $this->core->user->isAdmin() )
				{
					$admin = \Sweany\Session::get(\Sweany\Settings::sessSweany, \Sweany\Settings::sessAdmin);
					if ( isset($admin['blocks']) && $admin['blocks'] == 'highlight' )
					{
						\Sweany\Session::del(\Sweany\Settings::sessSweany, \Sweany\Settings::sessAdmin, 'blocks');
					}
					else
					{
						$admin['blocks'] = 'highlight';
						\Sweany\Session::set(array(\Sweany\Settings::sessSweany => \Sweany\Settings::sessAdmin), $admin);
					}
					$this->redirectBack();
					break;
				}
				else
				{
					$this->redirectHome();
					break;
				}

			case 'wrapper' :
				if ( $this->core->user->isAdmin() )
				{
					$admin = \Sweany\Session::get(\Sweany\Settings::sessSweany, \Sweany\Settings::sessAdmin);
					if ( isset($admin['wrapper']) && $admin['wrapper'] == 'highlight' )
					{
						\Sweany\Session::del(\Sweany\Settings::sessSweany, \Sweany\Settings::sessAdmin, 'wrapper');
					}
					else
					{
						$admin['wrapper'] = 'highlight';
						\Sweany\Session::set(array(\Sweany\Settings::sessSweany => \Sweany\Settings::sessAdmin), $admin);
					}
					$this->redirectBack();
					break;
				}
				else
				{
					$this->redirectHome();
					break;
				}
				
				
			default:
				// There is nothing to see here.
				// Go away
				$this->redirectHome();
				exit();
				break;
		}
	}

	public function admin($section = null)
	{
		global $DEFAULT_ADMIN_URL;

		if ( !$this->core->user->isAdmin() )
		{
			$this->url_not_found();
			return;
		}
		else
		{
			switch ($section)
			{
				case $DEFAULT_ADMIN_URL.'/users':
					$this->set('users', $this->core->user->getAllUsers());
					$this->view('admin_users');
					break;

				case $DEFAULT_ADMIN_URL.'/visitors':
					$Visitors = \Sweany\AutoLoader::loadCoreTable('Visitors');
					$this->set('visitors', $Visitors->find('all'));
					$this->view('admin_visitors');
					break;

				case $DEFAULT_ADMIN_URL.'/translations':
					
					if ( Form::isSubmitted('translations') )
					{
						$groups		= Form::getValue('group');
						$Language	=\Sweany\AutoLoader::loadCoreTable('Language');
						
						$Language->saveTranslations($groups);
						$this->refresh();
					}
				
					// Get all available translation
					$def	= $GLOBALS['LANGUAGE_DEFAULT_SHORT'];
					$def_name=$GLOBALS['LANGUAGE_AVAILABLE'][$def];
					$trans	= $GLOBALS['LANGUAGE_AVAILABLE'];
					unset($trans[$def]);	// unset the default language
//					$trans	= array_keys($trans);

					// Load Languages and appropriate translations
					$Language =\Sweany\AutoLoader::loadCoreTable('Language');
					$this->set('langs', $Language->loadforAminPanel());

					// Set everything for the view
					$this->set('default', $def_name);
					
					$this->set('trans', $trans);
					$this->view('admin_translations');
					break;

				case $DEFAULT_ADMIN_URL.'/emails':
					$Email =\Sweany\AutoLoader::loadCoreTable('Emails');
					$this->set('emails', $Email->find('all'));
					$this->view('admin_emails');
					break;
				
				default:
					$this->url_not_found();
					return;
			}
		}
	}

}
