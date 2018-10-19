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

/*
 * Back end modules
 */

$GLOBALS['TL_CSS'][] = 'bundles/wbgymschool/style.css';

array_insert($GLOBALS['BE_MOD'], 2, array('wbgym' => array(
	'schoolyears' => array(
		'tables'	=> array('tl_schoolyear','tl_schoolyear_holiday'),
	),
	'subjects' => array(
		'tables'	=> array('tl_subject'),
	),
	'workshops' => array(
		'tables'	=> array('tl_workshop'),
	),
	'courses' => array(
		'tables'	=> array('tl_courses'),
	),
	'committees' => array(
		'tables'	=> array('tl_committees','tl_committees_parent'),
	)
)));

$GLOBALS['BE_MOD']['accounts']['member']['replaceumlauts'] = array('WBGym\ReplaceUmlauts', 'showInterface');
$GLOBALS['BE_MOD']['wbgym']['courses']['updatecourses'] = array('WBGym\UpdateCourses', 'updateCourses');

/*
 * Front end modules
 */
$GLOBALS['FE_MOD']['wbgym']['wb_klausurplan']		= 'WBGym\ModuleKlausurplan';
$GLOBALS['FE_MOD']['wbgym']['wb_vertretungsplan']	= 'WBGym\ModuleVertretungsplan';
$GLOBALS['FE_MOD']['wbgym']['wb_workshop']  = 'WBGym\ModuleWorkshop';
$GLOBALS['FE_MOD']['wbgym']['wb_committees']  = 'WBGym\ModuleCommittees';

/*
 * Content elements
 */
$GLOBALS['TL_CTE']['wbgym']['wb_faculty_subjects']	= 'WBGym\ContentFacultySubjects';
$GLOBALS['TL_CTE']['wbgym']['wb_memberlist']		= 'WBGym\ContentMemberlist';
$GLOBALS['TL_CTE']['wbgym']['wb_subject_details']	= 'WBGym\ContentSubjectDetails';
$GLOBALS['TL_CTE']['wbgym']['wb_workshop_details']	= 'WBGym\ContentWorkshopDetails';

?>
