<?php
/*
Plugin Name: CG Cookie - User Badges
Plugin URI: http://pippinsplugins.com/
Description: A badge system for users
Version: 1.0
Author: Pippin Williamson
Contributors: mordauk
Author URI: http://pippinspages.com
*/

if(!defined('CGC_UB_PLUGIN_DIR')) {
	define('CGC_UB_PLUGIN_DIR', dirname(__FILE__));
}

if(!defined('CGC_UB_PLUGIN_URL')) {
	define('CGC_UB_PLUGIN_URL', plugin_dir_url(__FILE__));
}

/***************************************
* Includes
***************************************/

include_once(CGC_UB_PLUGIN_DIR . '/includes/admin-pages.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/users-page.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/scripts.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/badge-functions.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/actions.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/ajax.php');
include_once(CGC_UB_PLUGIN_DIR . '/includes/bbpress.php');