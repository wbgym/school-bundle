<?php

/**
 * WBGym
 * 
 * Copyright (C) 2008-2017 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package 	WGBym
 * @author 		Marvin Ritter <marvin.ritter@gmail.com>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_content 
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['wb_subject_details']	= '{type_legend},type,wb_subject,headline;{protected_legend:hide},protected;{expert_legend:hide},guests,invisible,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wb_faculty_subjects']	= '{type_legend},type,wb_faculty;{protected_legend:hide},protected;{expert_legend:hide},guests,invisible,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wb_workshop_details']	= '{type_legend},type,wb_workshop,headline;{protected_legend:hide},protected;{expert_legend:hide},guests,invisible,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wb_memberlist'] 		= '{type_legend},type,headline;wb_ml_groups;{protected_legend:hide},protected;{template_legend},wb_ml_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['fields']['wb_subject'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['wb_subject'],
	'exclude'				=> false,
	'inputType'				=> 'select',
	'foreignKey'			=> 'tl_subject.name',
	'eval'					=> array('multiple' => false, 'tl_class' => 'w50','chosen'=>true,'mandatory'=>true),
	'sql'					=> "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wb_workshop'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['wb_workshop'],
	'exclude'				=> false,
	'inputType'				=> 'select',
	'foreignKey'			=> 'tl_workshop.name',
	'eval'					=> array('multiple' => false, 'tl_class' => 'w50','chosen' => true,'mandatory'=>true),
	'sql'					=> "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wb_faculty'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['wb_faculty'],
	'exclude'				=> false,
	'inputType'				=> 'select',
	'options'				=> &$GLOBALS['TL_LANG']['wbgym']['facultyIds'],
	'reference'				=> &$GLOBALS['TL_LANG']['wbgym']['faculties'],
	'eval'					=> array('tl_class' => 'w50','mandatory'=>true),
	'sql'					=> "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wb_ml_groups'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['wb_ml_groups'],
	'exclude'				=> true,
	'inputType'				=> 'checkbox',
	'foreignKey'			=> 'tl_member_group.name',
	'eval'					=> array('multiple' => true),
	'sql'					=> 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wb_ml_template'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['wb_ml_template'],
	'default'				=> 'ce_memberlist_webteam',
	'exclude'				=> true,
	'inputType'				=> 'select',
	'options'				=> $this->getTemplateGroup('wb_ml_'),
	'sql'					=> "varchar(63) NOT NULL default ''"
);

?>