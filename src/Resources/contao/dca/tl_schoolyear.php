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
 * Table tl_schoolyear
 */
$GLOBALS['TL_DCA']['tl_schoolyear'] = array(
	// Config
	'config' => array(
		'dataContainer'		=> 'Table',
		'ctable'			=> 'tl_schoolyear_holiday',
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
			'flag'		=> 9,
			'fields'	=> array('end'),
			'panelLayout' => 'filter,sort;search,limit',
	),
	'label' => array(
		'fields'		=> array('name','start','end'),
		'showColumns'	=> true,
		'label_callback' => array('tl_schoolyear', 'generateListLabels')
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
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['edit'],
				'href'		=> 'table=tl_schoolyear_holiday',
				'icon'		=> 'edit.gif'
			),
			'editheader' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['editmeta'],
				'href'		=> 'act=edit',
				'icon'		=> 'header.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array(
		'__selector__' => array(''),
		'default' => '{name_header},name;{period_header},start,end'
	),

	// Fields
	'fields' => array(
		'id' => array(
			'sql'		=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql'		=> "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['name'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "varchar(255) NOT NULL default ''",
			'search'	=> true
		),
		'start' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['start'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'default'	=> time(),
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50','rgxp'=>'date','datepicker'=>true),
			'sql'		=> "int(10) NOT NULL default '0'",
			'search'	=> true
		),
		'end' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_schoolyear']['end'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'default'	=> time(),
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50','rgxp'=>'date','datepicker'=>true),
			'sql'		=> "int(10) NOT NULL default '0'",
			'search'	=> true
		)
	)
);

class tl_schoolyear extends Backend {
	public function generateListLabels($row, $label, DataContainer $dc, $args) {
		if($row['start'] < time() && $row['end'] > time()) {
			$args[0] = '<span style="color:green">[Laufendes Schuljahr]</span> ';
			$args[0] .= $row['name'];
		}
		
		$args[1] = date('d.m.Y',$row['start']);
		$args[2] = date('d.m.Y',$row['end']);
		return $args;
	}
}