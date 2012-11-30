<?php


/*
 * Specify an alternative layout
 * other than the default
 *
 * values:
 * array('LayoutControllerName', 'LayoutMethodName', [optional]paramsArray);
 */
//Config::set('layout', array('Layouts', 'FrontPage'), 'forum');


/*
 * Controller/Methods for login/register page.
 *
 * This is needed for redirects and links, telling the user
 * to login, in order to reply|start thread.
 */
Config::set('loginCtl', 'User', 'forum');
Config::set('loginMethod', 'login', 'forum');

Config::set('registerCtl', 'User', 'forum');
Config::set('registerMethod', 'login', 'forum');


/*
 * Do you want to enable the 'view user profile' feature?
 * This will convert usernames to username-links, linking
 * to their corresponding profile page.
 *
 * If yes, you will have to have a controller call
 * in the following format:
 * controller->method($user_id)
 * that will show the profile of user <user_id>.
 */
Config::set('userProfileLinkEnable', true, 'forum');
Config::set('userProfileCtl', 'User', 'forum');
Config::set('userProfileMethod', 'show', 'forum');


/*
 * Do you want to enable the 'write pm to user' feature?
 * This will then show up in the thread view as an icon,
 * linking to the write message-to-user.
 *
 * If yes, you will have to have a controller call
 * in the following format:
 * controller->method($user_id)
 * that will provide an interface to write a message to <user_id>.
 */
Config::set('writeMessageLinkEnable', true, 'forum');
Config::set('writeMessageCtl', 'Message', 'forum');
Config::set('writeMessageMethod', 'write', 'forum');
