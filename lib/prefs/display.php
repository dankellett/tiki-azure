<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: display.php 44444 2013-01-05 21:24:24Z changi67 $

function prefs_display_list()
{
	return array(
		'display_field_order' => array(
			'name' => tra('Fields display order'),
            'description' => tra(''),
			'type' => 'list',
			'options' => array(
				'DMY' => tra('Day') . ' ' . tra('Month') . ' ' . tra('Year'),
				'DYM' => tra('Day') . ' ' . tra('Year') . ' ' . tra('Month'),
				'MDY' => tra('Month') . ' ' . tra('Day') . ' ' . tra('Year'),
				'MYD' => tra('Month') . ' ' . tra('Year') . ' ' . tra('Day'),
				'YDM' => tra('Year')  . ' ' . tra('Day') . ' ' . tra('Month'),
				'YMD' => tra('Year')  . ' ' . tra('Month') . ' ' . tra('Day'),
			),
			'default' => 'MDY',
			'tags' => array('basic'),
		),
	);
}
