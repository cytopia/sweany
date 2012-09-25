<?php


/*
 * Specify an alternative layout
 * other than the default
 *
 * values:
 * array('LayoutControllerName', 'LayoutMethodName', [optional]paramsArray);
 */
//Config::set('layout', array('Layouts', 'FrontPage'), 'user');


/*
 * For registering, do you want the user to be
 * forced to also accept the terms and data use policy?
 *
 * If yes, there will be an additional checkbox to check
 *
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
