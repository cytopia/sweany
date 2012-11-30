<?php

// TODO: Implement those defines
// TODO: write validator against those defines


/* ***********************************************************************************
 *
 *	G U E S T B O O K   O P T I O N S
 *
 * ***********************************************************************************/

//Config::set('allowUnregisteredUsers', true, 'guestbook');
//Config::set('unregisteredEntriesNeedReview', true', guestbook');
//Config::set('registeredEntriesNeedReview', false', guestbook');



/* ***********************************************************************************
 *
 *	P R O F I L E   P A G E   D E F I N E S
 *
 * ***********************************************************************************/

/*
 * Controller/Methods for profiles page.
 *
 * This is needed to decide whether or not we display a link
 * pointing to the users profile page.
 * Set to false, if you do not have a profile handling controller
 */
//Config::set('userProfileLinkEnable', true, 'guestbook');
//Config::set('userProfileCtl', 'User', 'guestbook');
//Config::set('userProfileMethod', 'show', 'guestbook');


/* ***********************************************************************************
 *
 *	L A Y O U T   D E F I N E S
 *
 * ***********************************************************************************/

/*
 * Specify an alternative layout
 * other than the default
 *
 * values:
 * array('LayoutControllerName', 'LayoutMethodName', [optional]paramsArray);
 */
//Config::set('layout', array('Layouts', 'FrontPage'), 'guestbook');

// Use internal CSS File
//Config::set('guestbookCssEnable', true, 'guestbook');
//Config::set('guestbookCssName', 'guestbook.css', 'guestbook');

// Do you want to use your own CSS File?
// This CSS File will be in www/css of sweany root, not the plugin folder
//Config::set('customCssEnable', false, 'guestbook');
//Config::set('customCssName', 'test.css', 'guestbook');

