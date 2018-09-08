<?php

/**
 * WBGym
 * 
 * Copyright (C) 2017 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package 	WGBym
 * @author 		Johannes Cram <johannes@jonesmedia.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_member_group
 */

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = str_replace('{disable_legend}','{wbgym_legend},wb_group_function;{disable_legend}',$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['wb_group_function'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member_group']['wb_group_function'],
	'inputType'				=> 'select',
	'filter'				=> true,
    'options'               => array('students','teachers','parents','moderators'),
    'reference'             => &$GLOBALS['TL_LANG']['tl_member_group']['wb_group_function_ref'],
	'eval'					=> array('tl_class' => 'w50', 'unique' => true, 'includeBlankOption' => true),
	'sql'					=> ['type' => 'string', 'length' => '64', 'default' => '', 'notnull' => true],
);