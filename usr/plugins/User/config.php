<?php

/* ***********************************************************************************
 *
 *	R E G I S T R A T I O N   D E F I N E S
 *
 * ***********************************************************************************/

Config::set('userNameMinLen',5, 'user');
Config::set('userNameMaxLen',12, 'user');
Config::set('passwordMinLen',6, 'user');
Config::set('passwordMaxLen',40, 'user');

// You can also disallow registration by setting the following to '1'
Config::set('disableRegistration', 0, 'user');

/*
 * For registering, do you want the user to be
 * forced to also accept the terms and data use policy?
 * If yes, there will be an additional checkbox to check
 * Values: 0 | 1
 */
Config::set('acceptTermsOnRegister', 1, 'user');

/*
 * relative URLs to
 *  + terms of use
 *  + data use policy
 *
 * This is needed if the above config setting is set to 1
 */
Config::set('termsUrl', '/Terms#terms', 'user');
Config::set('policyUrl', '/Terms#datapolicy', 'user');




/* ***********************************************************************************
 *
 *	U S E R   P R O F I L E   P A G E    D E F I N E S
 *
 * ***********************************************************************************/

/*
 * Controller/Methods for write message page.
 *
 * This is needed to decide whether or not we display a link
 * to write a message to the user on his/her profile page.
 * Set to false, if you do not have a message handling controller
 */
Config::set('userWriteMessageLinkEnable', true, 'user');
Config::set('userWriteMessageIconEnable', true, 'user');
Config::set('userWriteMessageIconPath', '/plugins/Message/img/action_reply.png', 'user');
Config::set('userWriteMessageCtl', 'Message', 'user');
Config::set('userWriteMessageMethod', 'write', 'user');



/* ***********************************************************************************
 *
 *	L A Y O U T    D E F I N E S
 *
 * ***********************************************************************************/
/*
 * Specify an alternative layout
 * other than the default
 *
 * values:
 * array('LayoutControllerName', 'LayoutMethodName', [optional]paramsArray);
 */
//Config::set('layout', array('Layouts', 'FrontPage'), 'user');
//Config::set('editFormLayout', array('Layouts', 'ProfilePages', array('password')), 'user');

// Use internal CSS File
Config::set('userCssEnable', true, 'user');
Config::set('userCssName', 'user.css', 'user');

// Do you want to use your own CSS File?
// This CSS File will be in www/css of sweany root, not the plugin folder
Config::set('customCssEnable', false, 'user');
Config::set('customCssName', 'test.css', 'user');

