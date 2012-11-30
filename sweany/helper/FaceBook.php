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
 * @package		sweany.helper
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.1 2012-11-14 13:25
 *
 *
 * Add Facebook Elements
 */
class FaceBook
{

	/**
	 *	Add FaceBook Element to website
	 *
	 *
	 *	@param	string[]	$args 	Required Arguments
	 *
	 *		$args['FBAppNamespace'];
	 *		$args['FBAppId'];
	 *		$args['FBAppType'];
	 *		$args['FBAdmins'];
	 *
	 *		$args['ogType'];
	 *		$args['ogUrl'];
	 *		$args['ogTitle'];
	 *		$args['ogDescription'];
	 *		$args['ogSiteName'];
	 *		$args['ogLocale'];
	 *		$args['ogImageUrl'];
	 *		$args['ogImageType'];
	 *		$args['ogImageWidth'];
	 *		$args['ogImageHeight'];
	 *
	 */
	 
	 /**
	  *	TODO: Please do not use yet. Still unstable and needs some redesign
	  */
	public static function createElement($args)
	{
		HtmlTemplate::addXmlNameSpace('fb', 'http://www.facebook.com/2008/fbml');
		HtmlTemplate::addXmlNameSpace('og', 'http://opengraphprotocol.org/schema/');
		HtmlTemplate::addHeadPrefix('prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# '.$args['FBAppNamespace'].': http://ogp.me/ns/fb/'.$args['FBAppNamespace'].'#"');
		// required
		HtmlTemplate::addMetaTag('<meta property="fb:app_id" content="'.$args['FBAppId'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:type" content="'.$args['FBAppNamespace'].':'.$args['FBAppType'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:type" content="'.$args['ogType'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:url" content="'.$args['ogUrl'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:title" content="'.$args['ogTitle'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:image" content="'.$args['ogImageUrl'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:image:type" content="'.$args['ogImageType'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:image:width" content="'.$args['ogImageWidth'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:image:height" content="'.$args['ogImageHeight'].'" />');
		// optional
		HtmlTemplate::addMetaTag('<meta property="og:description" content="'.$args['ogDescription'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:site_name" content="'.$args['ogSiteName'].'" />');
		HtmlTemplate::addMetaTag('<meta property="fb:admins" content="'.$args['FBAdmins'].'" />');
		HtmlTemplate::addMetaTag('<meta property="og:locale" content="'.$args['ogLocale'].'" />');

		$element = '<div id="fb-root"></div>';
		$element.= '<fb:like send="no" href="'.$args['ogUrl'].'" layout="button_count" show_faces="false"  action="like"></fb:like>';

		// TODO: parameterize like/unlike callbacks
		$js = '<!--
				window.fbAsyncInit = function()
				{
					FB.init(
					{
						appId: \''.$args['FBAppId'].'\',
						status: true,
						cookie: true,
						xfbml: true
					});
					FB.Event.subscribe(\'edge.create\', function(href, widget)
					{
						MakePOSTRequest("/Ajax/ajax_add_fb_like/please_confirm", "liked_moment_id="+moment_id, function(response){});
					});
					FB.Event.subscribe(\'edge.remove\', function(href, widget)
					{
						MakePOSTRequest("/Ajax/ajax_remove_fb_like/please_confirm", "liked_moment_id="+moment_id, function(response){});
					});
				};
				(function(d)
				{
					var js, id = \'facebook-jssdk\';
					if (d.getElementById(id)) {return;}
					js = d.createElement(\'script\'); js.id = id; js.async = true;
					js.src = "//connect.facebook.net/de_DE/all.js";
					d.getElementsByTagName(\'head\')[0].appendChild(js);
				}(document));
			//-->';
		
		Javascript::addToBottom($js);

		return $element;
	}
}