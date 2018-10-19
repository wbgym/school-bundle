<?php

/**
 * WBGym
 * 
 * Copyright (C) 2016 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Johannes Cram <j-cram@gmx.de>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_schoolyear_holiday
 */
$GLOBALS['TL_DCA']['tl_schoolyear_holiday'] = array(
	// Config
	'config' => array(
		'dataContainer'		=> 'Table',
		'ptable'			=> 'tl_schoolyear',
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
			'mode'		=> 4,
			'fields'	=> array('type','start'),
			'headerFields' => array('name','start','end'),
			'panelLayout' => 'filter;search,limit',
			'child_record_callback' => array('tl_schoolyear_holiday','listEntry'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array(
			'all' => array(
				'label'		=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'		=> 'act=select',
				'class'		=> 'header_edit_all',
				'attributes'=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array(
			'edit' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array(
		'__selector__' 		=> array('type'),
		'default' 			=> '{general_header},type,name;{period_header},start,end',
		'holidays' 			=> '{general_header},type,name;{period_header},start,end',
		'nat_holiday' 		=> '{general_header},type,name,date',
		'variable_holiday' 	=> '{general_header},type,name;{period_header},date'
	),

	// Fields
	'fields' => array(
		'id' => array(
			'sql'		=> "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'=> 'tl_schoolyear.name',
			'sql'		=> "int(10) unsigned NOT NULL default '0'",
			'relation'	=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array(
			'sql'		=> "int(10) unsigned NOT NULL default '0'"
		),
		'type' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['type'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'search'	=> true,
			'sorting'	=> true,
			'options'	=> array('holidays','nat_holiday','project','variable_holiday'),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['type'],
			'eval'		=> array('submitOnChange' => true,'mandatory' => true, 'tl_class' =>'w50'),
			'sql'		=> "varchar(20) NOT NULL default ''"
		),
		'name' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['name'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'search'	=> true,
			'eval'		=> array('mandatory' => true, 'tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'date' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['date'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'default'	=> null,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50','rgxp'=>'date','datepicker'=>true),
			'save_callback' => array(
				array('tl_schoolyear_holiday','dateInRange'),
			),
			'sql'		=> "int(10) unsigned NULL",
			'search'	=> true
		),
		'start' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['start'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'default'	=> null,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50','rgxp'=>'date','datepicker'=>true),
			'save_callback'	=> array(
				array('tl_schoolyear_holiday','validateDatePeriod'),
				array('tl_schoolyear_holiday','dateInRange')
			),
			'sql'		=> "int(10) unsigned NULL",
			'search'	=> true
		),
		'end' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['end'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'default'	=> null,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50','rgxp'=>'date','datepicker'=>true),
			'save_callback'	=> array(
				array('tl_schoolyear_holiday','validateDatePeriod'),
				array('tl_schoolyear_holiday','dateInRange')
			),
			'sql'		=> "int(10) unsigned NULL",
			'search'	=> true
		)
	)
);

class tl_schoolyear_holiday extends Backend {
	/*
	 * List a Holiday entry
	 */
	public function listEntry($row)
	{
		if($row['date'] != null) $date = date('d.m.Y',$row['date']);
		else $date = date('d.m.Y',$row['start']) . ' - ' . date('d.m.Y',$row['end']);
		return '<div style="float:left">'. $row['name'] . '<span style="color:#b3b3b3;padding-left:3px">[' . $date . ']</span>' . "</div>\n";
	}
	/*
	* Check if start date is before end date 
	*/
	public function validateDatePeriod($val, DataContainer $dc) {
		if(strtotime(\Input::post('start')) > strtotime(\Input::post('end'))) {
			throw new \Exception('Start date should be before end date');
		}
		else return $val;
	}
	/*
	* Check if date is in school year
	*/
	public function dateInRange($val, DataContainer $dc) {
		$year = $this->Database->prepare("SELECT start,end FROM tl_schoolyear WHERE id = ? LIMIT 1")
		->execute($dc->activeRecord->pid)
		->fetchAssoc();
		
		if($val < $year['start'] || $val > $year['end']) {
			throw new \Exception('Date must be in range of the school year');
		}
		else return $val;
	}
}