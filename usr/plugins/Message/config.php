<?php


/*
 * Specify an alternative layout
 * other than the default
 *
 * values:
 * array('LayoutControllerName', 'LayoutMethodName', [optional]paramsArray);
 */
//Config::set('layout', array('Layouts', 'ProfilePages', array('messages')), 'message');

// Use internal CSS File
Config::set('messageCssEnable', true, 'message');
Config::set('messageCssName', 'message.css', 'message');

// Do you want to use your own CSS File?
// This CSS File will be in www/css of sweany root, not the plugin folder
Config::set('customCssEnable', false, 'message');
Config::set('customCssName', 'test.css', 'message');


Config::set('systemMessageUserDisplayName', 'Sysyem', 'message');

/*
 * Controller/Methods for profiles page.
 *
 * This is needed to decide whether or not we display a link
 * pointing to the users profile page.
 * Set to false, if you do not have a profile handling controller
 */
Config::set('userProfileLinkEnable', true, 'message');
Config::set('userProfileCtl', 'User', 'message');
Config::set('userProfileMethod', 'show', 'message');

Config::set('loginCtl', 'User', 'message');
Config::set('loginMethod', 'login', 'message');

Config::set('registerCtl', 'User', 'message');
Config::set('registerMethod', 'login', 'message');