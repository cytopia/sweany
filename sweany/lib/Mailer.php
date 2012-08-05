<?php
/**
 * Mail Helper
 *
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



	/***************************************   F U N C T I O N S   ***************************************/

	/**
	 *  Send Plain Text Email
	 *
	 *  returns true on success and false on failure
	 */
	public static function sendText($to, $subject, $message)
	{
		$headers = self::__getHeaders('text', $to);
		return mail($to, self::__charset_encode($subject), $message, implode(self::$br, $headers));
	}

	/**
	 *  Send Plain HTML Email
	 *
	 *  returns true on success and false on failure
	 */
	public static function sendHtml($to, $subject, $message)
	{
		$headers = self::__getHeaders('html', $to);
		return mail($to, self::__charset_encode($subject), $message, implode(self::$br, $headers));
	}



	/***************************************   P R I V A T E   H E L P E R S   ***************************************/


	/**
	 *  Build email headers
	 * (also takes care of encoding the from name
	 * depending on the chosen charset encoding
	 */
	private static function __getHeaders($content_type = 'text', $to_email)
	{
		$type = ($content_type == 'text') ? 'text/plain' : 'text/html';

		$headers[] = 'Content-Type: '.$type.'; charset="'.self::$charset.'";';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Transfer-Encoding: 8bit';
		$headers[] = 'Message-ID:'.md5(time()).$to_email;
		$headers[] = 'From: '.self::__charset_encode($GLOBALS['EMAIL_SYSTEM_FROM_NAME']).' <'.$GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS'].'>';
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
	private static function __charset_encode($string)
	{
		return (self::$charset == 'utf-8') ? '=?UTF-8?B?'.base64_encode($string).'?=' : $string;
	}
}

?>