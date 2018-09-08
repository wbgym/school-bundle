<?php

/**
 * WBGym
 * 
 * Copyright (C) 2015 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Johannes Cram <j-cram@gmx.de>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_courses
 */
$GLOBALS['TL_DCA']['tl_courses'] = array(
	// Config
	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'sql' 				=> array(
			'keys' => array(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array(
		'sorting' => array(
			'mode'		=> 1,
			'flag'		=>2,
			'fields'	=> array('grade','formSelector','graduation'),
			'panelLayout' => 'filter,sort;search,limit',
		),
		'label' => array(
			'fields'		=> array('title','grade','formSelector','leader','graduation'),
			'showColumns'	=> true,
			'label_callback' => array('tl_courses','generateLabels'),
		),
		'global_operations' => array(
				'all' => array(
					'label'		=> &$GLOBALS['TL_LANG']['MSC']['all'],
					'href'		=> 'act=select',
					'class'		=> 'header_edit_all',
					'attributes'=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
				),
				'updateCourses' => array(
					'label'     => &$GLOBALS['TL_LANG']['update_courses']['title'],
					'href'		=> 'key=updatecourses',
					'icon'      => 'system/modules/wbuser/assets/updatecourses.png',
					'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['update_courses']['confirm'] . '\')) return false; Backend.getScrollOffset();"'
				)
			),
		'operations' => array(
			'edit' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array(
		'__selector__' => array(''),
		'default' => '{name_header},title;{class_header},grade,formSelector,leader,graduation,following;{speaker_header},classsp1,classsp2,parentsp1,parentsp2'
	),

	// Fields
	'fields' => array(
		'id' => array(
			'sql'		=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql'		=> "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['title'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'	=> array('Klasse','Tutorium','Jahrgang'),
			'search'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => true, 'tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'grade' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['grade'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'		=> array(5,6,7,8,9,10,1112),
			'sorting'		=> true,
			'search'		=> true,
			'filter'		=> true,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "int(11) NOT NULL default '0'"
		),
		'formSelector' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['formSelector'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'		=> array(1,2,3,4,5),
			'filter'			=> true,
			'eval'		=> array('includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql'		=> "int(11) NOT NULL default '0'"
		),
		'graduation' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['graduation'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options_callback'	=> array('tl_courses','getGraduationYears'),
			'filter'	=> true,
			'eval'		=> array('includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql'		=> "int(11) NOT NULL default '0'"
		),
		'leader' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['leader'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_member.username',
			'options_callback' => array('WBGym\WBGym','teacherList'),
			'search'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => false,'chosen'=>true,'tl_class' => 'w50','includeBlankOption'=>true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'following' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['following'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_courses.title',
			'options_callback' => array('WBGym\WBGym','courseList'),
			'eval'		=> array('chosen'=>true,'tl_class' => 'w50','includeBlankOption' => true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'classsp1' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['classsp1'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_member.username',
			'options_callback' => array('tl_courses','getClassMembers'),
			'eval'		=> array('mandatory' => false,'tl_class'=>'w50','chosen'=>true,'includeBlankOption'=>true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'classsp2' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['classsp2'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_member.username',
			'options_callback' => array('tl_courses','getClassMembers'),
			'eval'		=> array('mandatory' => false,'tl_class'=>'w50','chosen'=>true,'includeBlankOption'=>true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'parentsp1' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['parentsp1'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_committees_parent.lastname',
			'options_callback' => array('WBGym\WBGym','cParentList'),
			'eval'		=> array('tl_class'=>'w50','chosen' => true,'includeBlankOption'=>true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'parentsp2' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_courses']['parentsp2'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_committees_parent.lastname',
			'options_callback' => array('WBGym\WBGym','cParentList'),
			'eval'		=> array('includeBlankOption'=> true,'tl_class'=>'w50','chosen' => true),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
	)
);

class tl_courses extends Backend{
	public function getClassMembers(DataContainer $dc) {
		$arrAll = WBGym\WBGym::studentList();
		$db = \Database::getInstance()->prepare("SELECT id,course FROM tl_member")->execute();
		while($elem = $db->fetchAssoc()) {
			if($dc->id != $elem['course']) {
				unset($arrAll[$elem['id']]);
			}
		}
		return $arrAll;
	}
	public function getGraduationYears() {
		$thisYear = date('Y');
		$schoolYearEnds = WBGym\WBGym::schoolYear();
		if($schoolYearEnds == $thisYear) {
			$arrYears = array($thisYear,$thisYear+1);
		}
		else {
			$arrYears = array($thisYear + 1, $thisYear + 2);
		}
		return $arrYears;
	}
	
	public function generateLabels($row, $label, DataContainer $dc, $args) {
		$args[3] = WBGym\WBGym::teacher($row['leader']);
		$schoolYearEnds = WBGym\WBGym::schoolYear();
		if($row['grade'] == '1112') {
			if($row['graduation'] == $schoolYearEnds)
				$args[1] = 'aktuell 12';
			elseif($row['graduation'] - 1 == $schoolYearEnds) 
				$args[1] = 'aktuell 11';
			elseif($row['graduation'] < $schoolYearEnds)
				$args[1] = 'veraltet';
		}
		
		return $args;
	}
}


?>