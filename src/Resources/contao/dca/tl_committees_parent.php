<?php

/**
 * WBGym
 * 
 * Copyright (C) 2016 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Johannes Cram <craj.me@gmail.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_committees
 */
$GLOBALS['TL_DCA']['tl_committees_parent'] = array(
	
	// Config
	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'sql' 				=> array(
			'keys' => array(
				'id' => 'primary'
			)
		),
		'backlink' => 'do=committees'
	),
	// List
	'list' => array(
		'sorting' => array(
			'mode'	=> 1,
			'flag'		=> 1,
			'fields'	=> array('lastname'),
			//'panelLayout' => 'filter;search,limit',
		),
		'label' => array(
			'fields'			=> array('lastname','firstname'),
			'showColumns'		=> false,
			'label_callback'	=> array('tl_committees_parent', 'generateListLabels')
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
				'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),
	
	// Palettes
	'palettes' => array(
		'__selector__' 	=> array(''),
		'default' 		=> '{general_header},salutation,title,firstname,lastname,course;{student_accounts},st1,st2,st3;{parent_accounts},pa1,pa2,pa3',
	),
	
	//Fields
	'fields' => array(
		'id' => array(
			'sql'		=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql'		=> "int(10) unsigned NOT NULL default '0'",
			'sorting'	=> true,
		),
		'salutation' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['salutation'],
			'inputType'	=> 'select',
			'options'	=> array('male' => 'Herr','female' => 'Frau'),
			'eval'		=> array('tl_class' =>'w50','mandatory' => true),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'title' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['title'],
			'inputType'	=> 'text',
			'eval'		=> array('tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'firstname' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['firstname'],
			'inputType'	=> 'text',
			'eval'		=> array('tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'lastname' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['lastname'],
			'inputType'	=> 'text',
			'eval'		=> array('tl_class' =>'w50','mandatory' => true),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'course' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['course'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym', 'courseList'),
			'eval'		=> array('tl_class' =>'w50','chosen' => true, 'includeBlankOption' => true),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'st1' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['st_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','studentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'st2' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['st_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','studentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'st3' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['st_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','studentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'pa1' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['pa_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','parentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'pa2' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['pa_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','parentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'pa3' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees_parent']['pa_account'],
			'inputType'	=> 'select',
			'options_callback' => array('WBGym\WBGym','parentList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
	)
);

class tl_committees_parent extends Backend {
	
	public function generateListLabels($row, $label, DataContainer $dc, $args) {
		$args = WBGym\WBGym::cParent($row['id']);
		if($row['st1'] == 0 && $row['st2'] == 0 && $row['st3'] == 0 && $row['course'] != 0) {
			$args .= ' - <span style="color:red">manuell aktualisieren!</span>';
		}
		return $args;
	}
}
?>