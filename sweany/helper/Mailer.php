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
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.8 2012-08-13 13:25
 *
 *
 * Mail Helper
 */
class Mailer
{
	/***************************************   V A R I A B L E S   ***************************************/

	//
	// Charset Encoding
	//
	// default is to use utf-8
	// others: ISO-8859-1
	//
	private static $charset = 'utf-8';

	//
	// Line breaks
	//
	// \r\n windows line break
	// \n   unix line break
	//
	private static $br		= "\r\n";



	/**
	 * Database table to store send emails,
	 * if specified in config.php
	 *
	 * @var string
	 */
	private static $table	= 'emails';



	/***************************************   F U N C T I O N S   ***************************************/

	/**
	 * Send Plain Text Email
	 *
	 * @param	string	$to
	 * @param	string	$subject
	 * @param	string	$message
	 * @param	mixed[]	$options
	 * 		$option = array(
	 * 			'charset'	=> 'UTF-8'		# Change charset 'ISO-8859-1' or 'UTF-8'	default: ''UTF-8'
	 * 			'br'		=> "\r"			# Change line feeds	"\r" or "\r\n" or "\n"	default: "\r\n"
	 * 		)
	 *
	 * @return	boolean	success
	 */
	public static function sendText($to, $subject, $message, $options = array())
	{
		return self::_send($to, $subject, $message, 'text', $options);
	}

	/**
	 * Send Plain HTML Email
	 *
	 * @param	string	$to
	 * @param	string	$subject
	 * @param	string	$message
	 * @param	mixed[]	$options
	 * 		$option = array(
	 * 			'charset'	=> 'UTF-8'		# Change charset 'ISO-8859-1' or 'UTF-8'	default: ''UTF-8'
	 * 			'br'		=> "\r"			# Change line feeds	"\r" or "\r\n" or "\n"	default: "\r\n"
	 * 		)
	 *
	 * @return	boolean	success
	 */
	public static function sendHtml($to, $subject, $message, $options = array())
	{
		return self::_send($to, $subject, $message, 'html', $options);
	}



	/***************************************   P R I V A T E   H E L P E R S   ***************************************/

	/**
	 * Send wrapper
	 *
	 * @param	string	$to
	 * @param	string	$subject
	 * @param	string	$message
	 * @param	string	$type
	 * @return	boolean success
	 */
	private static function _send($to, $subject, $message, $type, $options)
	{
		$charset = isset($options['charset'])	? $options['charset']	: self::$charset;
		$br		 = isset($options['br'])		? $options['br']		: self::$br;

		$headers = self::__getHeaders($type, $charset, $to);
		$headers = implode($br, $headers);

		$message = \Sweany\Render::email($message, USR_MAIL_SKELETON_PATH.DS.'default.tpl.php');

		// if enabled, also insert the email into database
		if ( $GLOBALS['EMAIL_STORE_SEND_MESSAGES'] )
		{
			$email['recipient'] = $to;
			$email['headers']	= $headers;
			$email['subject']	= $subject;
			$email['message']	= $message;
			$email['created']	= date('Y-m-d H:i:s', time());

			$tblEmail = \Sweany\AutoLoader::loadCoreTable('Emails');
			$tblEmail->save($email);
		}

		// Make sure to only send email when desired
		if ( !$GLOBALS['EMAIL_DO_NOT_SEND'] )
		{
			return mail($to, self::__charset_encode($subject, $charset), $message, $headers);
		}
	}


	/**
	 *  Build email headers
	 *  (also takes care of encoding the from name
	 *  depending on the chosen charset encoding
	 */
	private static function __getHeaders($content_type, $charset, $recipient)
	{
		$type = ($content_type == 'text') ? 'text/plain' : 'text/html';

		$headers[] = 'Content-Type: '.$type.'; charset="'.$charset.'";';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Transfer-Encoding: 8bit';
		$headers[] = 'Message-ID:'.md5(time()).$recipient;
		$headers[] = 'From: '.self::__charset_encode($GLOBALS['EMAIL_SYSTEM_FROM_NAME'], $charset).' <'.$GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS'].'>';
		$headers[] = 'Reply-To: '.$GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS'];
		$headers[] = 'Return-Path: '.$GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL'];
		$headers[] = 'X-Sender-IP: '.$_SERVER['REMOTE_ADDR'];
		$headers[] = 'X-Mailer: PHP/'.phpversion();

		return $headers;
	}

	/**
	 *  If using UTF-8, then we need special
	 *  encoding plus pre- and postfixing some elements
	 */
	private static function __charset_encode($string, $charset)
	{
		return ($charset == 'utf-8') ? '=?UTF-8?B?'.base64_encode($string).'?=' : $string;
	}
}
