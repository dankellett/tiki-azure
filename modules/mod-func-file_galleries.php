<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: mod-func-file_galleries.php 44444 2013-01-05 21:24:24Z changi67 $

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

/**
 * @return array
 */
function module_file_galleries_info()
{
	return array(
		'name' => tra('File Galleries'),
		'description' => tra('Displays links to file galleries.'),
		'prefs' => array('feature_file_galleries'),
		'params' => array(),
	);
}

/**
 * @param $mod_reference
 * @param $module_params
 */
function module_file_galleries($mod_reference, $module_params)
{
	global $filegallib; include_once ('lib/filegals/filegallib.php');
	global $smarty;
	
	$smarty->assign('tree', $filegallib->getTreeHTML());
}
