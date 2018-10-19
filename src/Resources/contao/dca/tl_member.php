<?php

/**
 * WBGym
 * 
 * Copyright (C) 2008-2013 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package 	WGBym
 * @author 		Marvin Ritter <marvin.ritter@gmail.com>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_member
 */
$GLOBALS['TL_DCA']['tl_member']['palettes']['__selector__'][] = 'teacher';
$GLOBALS['TL_DCA']['tl_member']['palettes']['__selector__'][] = 'student';

$GLOBALS['TL_DCA']['tl_member']['subpalettes']['teacher'] = 'acronym,referendar,subjects';
$GLOBALS['TL_DCA']['tl_member']['subpalettes']['student'] = 'grade,formSelector,course,graduationYear,parentEMail,workshops';

$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('dateOfBirth,gender', 'dateOfBirth,gender;{teacher_legend},teacher;{student_legend},student', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{login_legend},login', '{login_legend},login,mailLogin', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_member']['config']['ondelete_callback'][] = array('WBGym\WBGym','deleteMemberWishEntries');
$GLOBALS['TL_DCA']['tl_member']['config']['sql']['key']['mailLogin'] = 'unique';

$GLOBALS['TL_DCA']['tl_member']['fields']['nameprefix'] = array(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['nameprefix'],
	'exclude'				=> false,
	'inputType'				=> 'text',
	'eval'					=> array('tl_class' => 'w50'),
	'sql'					=> "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['subjects'] = array(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['subjects'],
	'exclude'				=> false,
	'inputType'				=> 'checkbox',
	'foreignKey'			=> 'tl_subject.name',
	'eval'					=> array('multiple'=>true, 'tl_class' => 'clr'),
	'sql'					=> "blob NULL"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['teacher'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['teacher'],
	'exclude'				=> true,
	'filter'				=> true,
	'inputType'				=> 'checkbox',
	'sql'					=> "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['referendar'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['referendar'],
	'exclude'				=> true,
	'filter'				=> true,
	'inputType'				=> 'checkbox',
	'eval'					=> array('submitOnChange' => true),
	'sql'					=> "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['acronym'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['acronym'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('feEditable' => false, 'feViewable' => false, 'tl_class' => 'clr', 'maxlength' => 16),
	'sql'					=> "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['student'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['student'],
	'exclude'				=> true,
	'filter'				=> true,
	'inputType'				=> 'checkbox',
	'eval'					=> array('submitOnChange' => true),
	'sql'					=> "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['grade'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['grade'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('rgxp' => 'natural', 'feEditable' => false, 'feViewable' => false, 'tl_class' =>'w50'),
	'sql'					=> "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['formSelector'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['formSelector'],
	'inputType'				=> 'text',
	'eval'					=> array('rgxp' => 'natural', 'feEditable' => false, 'feViewable' => false, 'tl_class' => 'w50'),
	'sql'					=> "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['course'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['course'],
	'inputType'				=> 'select',
	'filter'				=> true,
	'foreignKey'			=> 'tl_courses.title',
	'options_callback'		=> array('WBGym\WBGym','courseList'),
	'eval'					=> array('maxlength' => 5, 'feViewable' => false, 'tl_class' => 'w50','includeBlankOption' => true),
	'sql'					=> "smallint(5) unsigned NOT NULL default '0'",
	'relation'              => array('type'=>'hasOne', 'load'=>'lazy')
);
$GLOBALS['TL_DCA']['tl_member']['fields']['graduationYear'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['graduationYear'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('rgxp' => 'natural', 'feViewable' => false, 'tl_class' => 'w50'),
	'sql'					=> "smallint(5) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['parentEMail'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['parentEMail'],
	'inputType'				=> 'text',
	'eval'					=> array('maxlength' => 255, 'rgxp' => 'email', 'unique' => true, 'decodeEntities' => true, 'tl_class' => 'clr'),
	'sql'					=> "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['mailLogin'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['mailLogin'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('feEditable' => false, 'feViewable' => false, 'maxlength' => 16, 'tl_class' => 'clr'),
	'sql'					=> "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['about'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['about'],
	'inputType'				=> 'textarea',
	'eval'					=> array('feEditable' => true, 'feViewable' => true, 'tl_class' => 'clr','style'=>'height: 70px;', 'maxlength' => 255),
	'sql'					=> "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['signature'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['signature'],
	'inputType'				=> 'text',
	'eval'					=> array('feEditable' => true, 'feViewable' => true, 'maxlength' => 255),
	'sql'					=> "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['privacy'] = array
(
	'sql'					=> "blob NULL"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['workshops'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_member']['workshops'],
	'inputType'				=> 'checkboxWizard',
	'foreignKey'			=> 'tl_workshop.name',
	'sql'					=> "blob NULL"
);

array_insert($GLOBALS['TL_DCA']['tl_member']['list']['global_operations'], 1, array
(
    'replaceUmlauts' => array
    (
        'label'               => &$GLOBALS['TL_LANG']['replace_umlauts']['title'],
		'href'				  => 'key=replaceumlauts',
        'icon'                => 'bundles/wbgymschool/replaceumlauts.png',
        'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="u"'
    )
));


?>