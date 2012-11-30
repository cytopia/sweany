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
 * @package		sweany.core.validator
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Sweany;
class Validate03Language extends aBootTemplate
{
	// TODO: also validate tables

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		// Only validate if enabled
		if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
		{
			if ( !self::_checkVariableExistance() )
			{
				echo '<h1>Validation Error: Variable missing in Config.php</h2>';
				echo self::$error;
				return false;
			}

			if ( !self::_checkVariableValue() )
			{
				echo '<h1>Validation Error: Variable with wrong value in Config.php</h2>';
				echo self::$error;
				return false;
			}

			if ( !self::_checkPluginLanguageFiles() )
			{
				echo '<h1>Validation Error: Plugins Files missing</h2>';
				echo self::$error;
				return false;
			}

			if ( !self::_checkXMLSyntax() )
			{
				echo '<h1>Validation Error: Language</h2>';
				echo self::$error;
				return false;
			}
		}
		return true;
	}



	/* ******************************************** VALIDATORS ********************************************/


	private static function _checkVariableExistance()
	{
		if ( !isset($GLOBALS['LANGUAGE_DEFAULT_SHORT']) )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_SHORT</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LANGUAGE_DEFAULT_LONG']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_LONG</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LANGUAGE_AVAILABLE']) )
		{
			self::$error = '<b>$LANGUAGE_AVAILABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LANGUAGE_IMG_PATH']) )
		{
			self::$error = '<b>$LANGUAGE_IMG_PATH</b> not defined in <b>config.php</b>';
			return false;
		}
		return true;
	}


	private static function _checkVariableValue()
	{
		if ( !in_array($GLOBALS['LANGUAGE_DEFAULT_SHORT'], self::_get_iso639_1_languageCode()) )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_SHORT: '.$GLOBALS['LANGUAGE_DEFAULT_SHORT'].'</b> is not a valid code by <b>ISO-639-1</b>';
			return false;
		}

		$lang = explode('-', $GLOBALS['LANGUAGE_DEFAULT_LONG']);
		if ( sizeof($lang) != 2 )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_LONG: '.$GLOBALS['LANGUAGE_DEFAULT_LONG'].'</b> has a wrong format. (<ISO639-1 Langcode>-<ISO3166 CountryCode>';
			return false;
		}
		if (!in_array($lang[0], self::_get_iso639_1_languageCode()))
		{
			self::$error = 'Wrong Language Code in <b>$LANGUAGE_DEFAULT_LONG: '.$lang[0].'</b>';
			return false;
		}
		if (!in_array($lang[1], self::_get_iso3166_countryCode()))
		{
			self::$error = 'Wrong Country Code in <b>$LANGUAGE_DEFAULT_LONG: '.$lang[1].'</b>';
			return false;
		}

		if ( !file_exists(ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH']) )
		{
			self::$error = '$LANGUAGE_IMG_PATH: <b>'.$GLOBALS['LANGUAGE_IMG_PATH'].'</b> does not exist in: '.ROOT.DS.'www'.DS;
			return false;
		}

		foreach ( $GLOBALS['LANGUAGE_AVAILABLE'] as $key => $name)
		{
			if (!in_array($key, self::_get_iso639_1_languageCode()))
			{
				self::$error = '<b>$LANGUAGE_AVAILABLE</b> key <b>'.$key.'</b> is not valid by ISO 639-1.';
				return false;
			}
			if ( !strlen($name) )
			{
				self::$error = '<b>$LANGUAGE_AVAILABLE</b> value <b>'.$name.'</b> of key <b>'.$key.'</b> is not set';
				return false;
			}

			// Check Language image flags
			if ( !file_exists(ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH'].DS.$key.'.png') )
			{
				self::$error = '<b>Missing Language Flag Image:</b> '.ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH'].DS.$key.'.png';
				return false;
			}

			// Check Language xml files
			if ( !file_exists(USR_LANGUAGES_PATH.DS.$key.'.xml') )
			{
				self::$error = '<b>Missing Language XML file:</b> '.USR_LANGUAGES_PATH.DS.$key.'.xml';
				return false;
			}
		}
		return true;
	}


	private static function _checkPluginLanguageFiles()
	{
		if ( $handle = opendir(USR_PLUGINS_PATH) )
		{
			while ( false !== ($file = readdir($handle)) )
			{
				if ( $file != '.' && $file != '..' && is_dir(USR_PLUGINS_PATH.DS.$file) )
				{
					$languageDir	= USR_PLUGINS_PATH.DS.$file.DS.'languages';

					foreach ( $GLOBALS['LANGUAGE_AVAILABLE'] as $key => $name)
					{
						// Check Language xml files
						if ( !file_exists($languageDir.DS.$key.'.xml') )
						{
							self::$error = '<b>'.$file.'</b>-plugin is missing <b>'.$key.'.xml in '.$languageDir;
							return false;
						}
					}
				}
			}
		}
		return true;
	}


	private static function _checkXMLSyntax()
	{
		foreach ( $GLOBALS['LANGUAGE_AVAILABLE'] as $key => $name)
		{
			// Check xml syntax
			$langObj[$key] = simplexml_load_file(USR_LANGUAGES_PATH.DS.$key.'.xml');
			if (!$langObj[$key])
			{
				self::$error = 'Loading of '.USR_LANGUAGES_PATH.DS.$key.'.xml'.' has failed<br/>';
				foreach(libxml_get_errors() as $error)
				{
					self::$error.= $error->message.'<br/>';
				}
				return false;
			}

			// Check basic xml elements
			if ( $langObj[$key]->xpath('/root') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root</b>';
				return false;
			}
			if ( $langObj[$key]->xpath('/root/core') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core</b>';
				return false;
			}
			if ( $langObj[$key]->xpath('/root/core/settings') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings</b>';
				return false;
			}

			// /root/core/settings
			$langTmp = $langObj[$key]->xpath('/root/core/settings');
			if ( !isset($langTmp[0]->lang_name) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_name</b>';
				return false;
			}
			if ( !isset($langTmp[0]->lang_short) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_short</b>';
				return false;
			}
			if ( !isset($langTmp[0]->lang_long) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_long</b>';
				return false;
			}


			if ( $langObj[$key]->xpath('/root/core/default') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="notFound"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="notFound"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="notFound"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="notFound"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="notFound"]/text</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="redirect"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="redirect"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="redirect"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="redirect"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="redirect"]/text</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="changeLanguage"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="changeLanguage"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="changeLanguage"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="changeLanguage"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="changeLanguage"]/text</b>';
				return false;
			}
		}
		// TODO: need to compare all language xml files, to check if one has some sections missing
		return true;
	}




	/* ******************************************** PRIVATES ********************************************/

	private static function _get_iso3166_countryCode()
	{
		$arr   = array();

		$arr[] = 'AF';
		$arr[] = 'AX';
		$arr[] = 'AL';
		$arr[] = 'DZ';
		$arr[] = 'AS';
		$arr[] = 'AD';
		$arr[] = 'AO';
		$arr[] = 'AI';
		$arr[] = 'AQ';
		$arr[] = 'AG';
		$arr[] = 'AR';
		$arr[] = 'AM';
		$arr[] = 'AW';
		$arr[] = 'AU';
		$arr[] = 'AT';
		$arr[] = 'AZ';
		$arr[] = 'BS';
		$arr[] = 'BH';
		$arr[] = 'BD';
		$arr[] = 'BB';
		$arr[] = 'BY';
		$arr[] = 'BE';
		$arr[] = 'BZ';
		$arr[] = 'BJ';
		$arr[] = 'BM';
		$arr[] = 'BT';
		$arr[] = 'BO';
		$arr[] = 'BA';
		$arr[] = 'BW';
		$arr[] = 'BV';
		$arr[] = 'BR';
		$arr[] = 'IO';
		$arr[] = 'BN';
		$arr[] = 'BG';
		$arr[] = 'BF';
		$arr[] = 'BI';
		$arr[] = 'KH';
		$arr[] = 'CM';
		$arr[] = 'CA';
		$arr[] = 'CV';
		$arr[] = 'KY';
		$arr[] = 'CF';
		$arr[] = 'TD';
		$arr[] = 'CL';
		$arr[] = 'CN';
		$arr[] = 'CX';
		$arr[] = 'CC';
		$arr[] = 'CO';
		$arr[] = 'KM';
		$arr[] = 'CG';
		$arr[] = 'CD';
		$arr[] = 'CK';
		$arr[] = 'CR';
		$arr[] = 'CI';
		$arr[] = 'HR';
		$arr[] = 'CU';
		$arr[] = 'CY';
		$arr[] = 'CZ';
		$arr[] = 'DK';
		$arr[] = 'DJ';
		$arr[] = 'DM';
		$arr[] = 'DO';
		$arr[] = 'EC';
		$arr[] = 'EG';
		$arr[] = 'SV';
		$arr[] = 'GQ';
		$arr[] = 'ER';
		$arr[] = 'EE';
		$arr[] = 'ET';
		$arr[] = 'FK';
		$arr[] = 'FO';
		$arr[] = 'FJ';
		$arr[] = 'FI';
		$arr[] = 'FR';
		$arr[] = 'GF';
		$arr[] = 'PF';
		$arr[] = 'TF';
		$arr[] = 'GA';
		$arr[] = 'GM';
		$arr[] = 'GE';
		$arr[] = 'DE';
		$arr[] = 'GH';
		$arr[] = 'GI';
		$arr[] = 'GR';
		$arr[] = 'GL';
		$arr[] = 'GD';
		$arr[] = 'GP';
		$arr[] = 'GU';
		$arr[] = 'GT';
		$arr[] = 'GG';
		$arr[] = 'GN';
		$arr[] = 'GW';
		$arr[] = 'GY';
		$arr[] = 'HT';
		$arr[] = 'HM';
		$arr[] = 'VA';
		$arr[] = 'HN';
		$arr[] = 'HK';
		$arr[] = 'HU';
		$arr[] = 'IS';
		$arr[] = 'IN';
		$arr[] = 'ID';
		$arr[] = 'IR';
		$arr[] = 'IQ';
		$arr[] = 'IE';
		$arr[] = 'IM';
		$arr[] = 'IL';
		$arr[] = 'IT';
		$arr[] = 'JM';
		$arr[] = 'JP';
		$arr[] = 'JE';
		$arr[] = 'JO';
		$arr[] = 'KZ';
		$arr[] = 'KE';
		$arr[] = 'KI';
		$arr[] = 'KP';
		$arr[] = 'KR';
		$arr[] = 'KW';
		$arr[] = 'KG';
		$arr[] = 'LA';
		$arr[] = 'LV';
		$arr[] = 'LB';
		$arr[] = 'LS';
		$arr[] = 'LR';
		$arr[] = 'LY';
		$arr[] = 'LI';
		$arr[] = 'LT';
		$arr[] = 'LU';
		$arr[] = 'MO';
		$arr[] = 'MK';
		$arr[] = 'MG';
		$arr[] = 'MW';
		$arr[] = 'MY';
		$arr[] = 'MV';
		$arr[] = 'ML';
		$arr[] = 'MT';
		$arr[] = 'MH';
		$arr[] = 'MQ';
		$arr[] = 'MR';
		$arr[] = 'MU';
		$arr[] = 'YT';
		$arr[] = 'MX';
		$arr[] = 'FM';
		$arr[] = 'MD';
		$arr[] = 'MC';
		$arr[] = 'MN';
		$arr[] = 'ME';
		$arr[] = 'MS';
		$arr[] = 'MA';
		$arr[] = 'MZ';
		$arr[] = 'MM';
		$arr[] = 'NA';
		$arr[] = 'NR';
		$arr[] = 'NP';
		$arr[] = 'NL';
		$arr[] = 'AN';
		$arr[] = 'NC';
		$arr[] = 'NZ';
		$arr[] = 'NI';
		$arr[] = 'NE';
		$arr[] = 'NG';
		$arr[] = 'NU';
		$arr[] = 'NF';
		$arr[] = 'MP';
		$arr[] = 'NO';
		$arr[] = 'OM';
		$arr[] = 'PK';
		$arr[] = 'PW';
		$arr[] = 'PS';
		$arr[] = 'PA';
		$arr[] = 'PG';
		$arr[] = 'PY';
		$arr[] = 'PE';
		$arr[] = 'PH';
		$arr[] = 'PN';
		$arr[] = 'PL';
		$arr[] = 'PT';
		$arr[] = 'PR';
		$arr[] = 'QA';
		$arr[] = 'RE';
		$arr[] = 'RO';
		$arr[] = 'RU';
		$arr[] = 'RW';
		$arr[] = 'SH';
		$arr[] = 'KN';
		$arr[] = 'LC';
		$arr[] = 'PM';
		$arr[] = 'VC';
		$arr[] = 'WS';
		$arr[] = 'SM';
		$arr[] = 'ST';
		$arr[] = 'SA';
		$arr[] = 'SN';
		$arr[] = 'RS';
		$arr[] = 'SC';
		$arr[] = 'SL';
		$arr[] = 'SG';
		$arr[] = 'SK';
		$arr[] = 'SI';
		$arr[] = 'SB';
		$arr[] = 'SO';
		$arr[] = 'ZA';
		$arr[] = 'GS';
		$arr[] = 'ES';
		$arr[] = 'LK';
		$arr[] = 'SD';
		$arr[] = 'SR';
		$arr[] = 'SJ';
		$arr[] = 'SZ';
		$arr[] = 'SE';
		$arr[] = 'CH';
		$arr[] = 'SY';
		$arr[] = 'TW';
		$arr[] = 'TJ';
		$arr[] = 'TZ';
		$arr[] = 'TH';
		$arr[] = 'TL';
		$arr[] = 'TG';
		$arr[] = 'TK';
		$arr[] = 'TO';
		$arr[] = 'TT';
		$arr[] = 'TN';
		$arr[] = 'TR';
		$arr[] = 'TM';
		$arr[] = 'TC';
		$arr[] = 'TV';
		$arr[] = 'UG';
		$arr[] = 'UA';
		$arr[] = 'AE';
		$arr[] = 'GB';
		$arr[] = 'US';
		$arr[] = 'UM';
		$arr[] = 'UY';
		$arr[] = 'UZ';
		$arr[] = 'VU';
		$arr[] = 'VA';
		$arr[] = 'VE';
		$arr[] = 'VN';
		$arr[] = 'VG';
		$arr[] = 'VI';
		$arr[] = 'WF';
		$arr[] = 'EH';
		$arr[] = 'YE';
		$arr[] = 'CD';
		$arr[] = 'ZM';
		$arr[] = 'ZW';
		return $arr;
	}

	private static function _get_iso639_1_languageCode()
	{
		$arr   = array();
		$arr[] = 'aa';
		$arr[] = 'ab';
		$arr[] = 'af';
		$arr[] = 'ak';
		$arr[] = 'sq';
		$arr[] = 'am';
		$arr[] = 'ar';
		$arr[] = 'an';
		$arr[] = 'hy';
		$arr[] = 'as';
		$arr[] = 'av';
		$arr[] = 'ae';
		$arr[] = 'ay';
		$arr[] = 'az';
		$arr[] = 'ba';
		$arr[] = 'bm';
		$arr[] = 'eu';
		$arr[] = 'be';
		$arr[] = 'bn';
		$arr[] = 'bh';
		$arr[] = 'bi';
		$arr[] = 'bo';
		$arr[] = 'bs';
		$arr[] = 'br';
		$arr[] = 'bg';
		$arr[] = 'my';
		$arr[] = 'ca';
		$arr[] = 'cs';
		$arr[] = 'ch';
		$arr[] = 'ce';
		$arr[] = 'zh';
		$arr[] = 'cu';
		$arr[] = 'cv';
		$arr[] = 'kw';
		$arr[] = 'co';
		$arr[] = 'cr';
		$arr[] = 'cy';
		$arr[] = 'cs';
		$arr[] = 'da';
		$arr[] = 'de';
		$arr[] = 'dv';
		$arr[] = 'nl';
		$arr[] = 'dz';
		$arr[] = 'el';
		$arr[] = 'en';
		$arr[] = 'eo';
		$arr[] = 'et';
		$arr[] = 'eu';
		$arr[] = 'ee';
		$arr[] = 'fo';
		$arr[] = 'fa';
		$arr[] = 'fj';
		$arr[] = 'fi';
		$arr[] = 'fr';
		$arr[] = 'fy';
		$arr[] = 'ff';
		$arr[] = 'ka';
		$arr[] = 'de';
		$arr[] = 'gd';
		$arr[] = 'ga';
		$arr[] = 'gl';
		$arr[] = 'gv';
		$arr[] = 'el';
		$arr[] = 'gn';
		$arr[] = 'gu';
		$arr[] = 'ht';
		$arr[] = 'ha';
		$arr[] = 'he';
		$arr[] = 'hz';
		$arr[] = 'hi';
		$arr[] = 'ho';
		$arr[] = 'hr';
		$arr[] = 'hu';
		$arr[] = 'hy';
		$arr[] = 'ig';
		$arr[] = 'is';
		$arr[] = 'io';
		$arr[] = 'ii';
		$arr[] = 'iu';
		$arr[] = 'ie';
		$arr[] = 'ia';
		$arr[] = 'id';
		$arr[] = 'ik';
		$arr[] = 'is';
		$arr[] = 'it';
		$arr[] = 'jv';
		$arr[] = 'ja';
		$arr[] = 'kl';
		$arr[] = 'kn';
		$arr[] = 'ks';
		$arr[] = 'ka';
		$arr[] = 'kr';
		$arr[] = 'kk';
		$arr[] = 'km';
		$arr[] = 'ki';
		$arr[] = 'rw';
		$arr[] = 'ky';
		$arr[] = 'kv';
		$arr[] = 'kg';
		$arr[] = 'ko';
		$arr[] = 'kj';
		$arr[] = 'ku';
		$arr[] = 'lo';
		$arr[] = 'la';
		$arr[] = 'lv';
		$arr[] = 'li';
		$arr[] = 'ln';
		$arr[] = 'lt';
		$arr[] = 'lb';
		$arr[] = 'lu';
		$arr[] = 'lg';
		$arr[] = 'mk';
		$arr[] = 'mh';
		$arr[] = 'ml';
		$arr[] = 'mi';
		$arr[] = 'mr';
		$arr[] = 'ms';
		$arr[] = 'mk';
		$arr[] = 'mg';
		$arr[] = 'mt';
		$arr[] = 'mo';
		$arr[] = 'mn';
		$arr[] = 'mi';
		$arr[] = 'ms';
		$arr[] = 'my';
		$arr[] = 'na';
		$arr[] = 'nv';
		$arr[] = 'nr';
		$arr[] = 'nd';
		$arr[] = 'ng';
		$arr[] = 'ne';
		$arr[] = 'nl';
		$arr[] = 'nn';
		$arr[] = 'nb';
		$arr[] = 'no';
		$arr[] = 'ny';
		$arr[] = 'oc';
		$arr[] = 'oj';
		$arr[] = 'or';
		$arr[] = 'om';
		$arr[] = 'os';
		$arr[] = 'pa';
		$arr[] = 'fa';
		$arr[] = 'pi';
		$arr[] = 'pl';
		$arr[] = 'pt';
		$arr[] = 'ps';
		$arr[] = 'qu';
		$arr[] = 'rm';
		$arr[] = 'ro';
		$arr[] = 'ro';
		$arr[] = 'rn';
		$arr[] = 'ru';
		$arr[] = 'sg';
		$arr[] = 'sa';
		$arr[] = 'sr';
		$arr[] = 'hr';
		$arr[] = 'si';
		$arr[] = 'sk';
		$arr[] = 'sk';
		$arr[] = 'sl';
		$arr[] = 'se';
		$arr[] = 'sm';
		$arr[] = 'sn';
		$arr[] = 'sd';
		$arr[] = 'so';
		$arr[] = 'st';
		$arr[] = 'es';
		$arr[] = 'sq';
		$arr[] = 'sc';
		$arr[] = 'sr';
		$arr[] = 'ss';
		$arr[] = 'su';
		$arr[] = 'sw';
		$arr[] = 'sv';
		$arr[] = 'ty';
		$arr[] = 'ta';
		$arr[] = 'tt';
		$arr[] = 'te';
		$arr[] = 'tg';
		$arr[] = 'tl';
		$arr[] = 'th';
		$arr[] = 'bo';
		$arr[] = 'ti';
		$arr[] = 'to';
		$arr[] = 'tn';
		$arr[] = 'ts';
		$arr[] = 'tk';
		$arr[] = 'tr';
		$arr[] = 'tw';
		$arr[] = 'ug';
		$arr[] = 'uk';
		$arr[] = 'ur';
		$arr[] = 'uz';
		$arr[] = 've';
		$arr[] = 'vi';
		$arr[] = 'vo';
		$arr[] = 'cy';
		$arr[] = 'wa';
		$arr[] = 'wo';
		$arr[] = 'xh';
		$arr[] = 'yi';
		$arr[] = 'yo';
		$arr[] = 'za';
		$arr[] = 'zh';
		$arr[] = 'zu';
		return $arr;
	}
}
