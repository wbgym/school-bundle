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
 * Table tl_workshop
 */
$GLOBALS['TL_DCA']['tl_workshop'] = array(
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
			'fields'	=> array('name'),
			'flag'		=> 11
	),
	'label' => array(
		'fields'		=> array('name', 'minGrade', 'maxGrade', 'leader', 'weekday_all','room'),
		'showColumns'	=> true,
		'label_callback'=> array('tl_workshop','generateLabels')
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
				'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array(
		'__selector__' => array('isAll','isA','isB'),
		'default' => '{general_header},name,minGrade,maxGrade,room;{leader_header},leader,secondLeader;{all_header},isAll;{a_header},isA;{b_header},isB;{page_header},page'
	),
	'subpalettes' => array(
		'isAll' => 'weekday_all,time_all',
		'isA' => 'weekday_a,time_a',
		'isB' => 'weekday_b,time_b'
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
			'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['name'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'search'	=> true,
			'filter'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => true),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'minGrade' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['minGrade'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'	=> array(5, 6, 7, 8, 9, 10, 11, 12),
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "int(10) NOT NULL default '5'"
		),
		'maxGrade' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_workshop']['maxGrade'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'	=> array(5, 6, 7, 8, 9, 10, 11, 12),
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "int(10) NOT NULL default '12'"
		),
		'leader' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['leader'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'mandatory' => true, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''",
			'search'			=> true
		),
		'secondLeader' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['secondLeader'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''",
			'search'			=> true
		),
		'room' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['room'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'isAll' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['isAll'],
			'exclude'			=> false,
			'inputType'			=> 'checkbox',
			'eval'				=> array('submitOnChange' => true),
			'sql'				=> "char(1) NOT NULL default ''"
		),
		'weekday_all' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['weekday_all'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'options'			=> &$GLOBALS['TL_LANG']['wbgym']['weekdayOptions'],
			'reference'			=> &$GLOBALS['TL_LANG']['wbgym']['weekday'],
			'eval'				=> array('includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql'				=> "varchar(10) NOT NULL default ''"
		),
		'time_all' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['time_all'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'isA' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['isA'],
			'exclude'			=> false,
			'inputType'			=> 'checkbox',
			'eval'				=> array('submitOnChange' => true),
			'sql'				=> "char(1) NOT NULL default ''"
		),
		'weekday_a' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['weekday_a'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'options'			=> &$GLOBALS['TL_LANG']['wbgym']['weekdayOptions'],
			'reference'			=> &$GLOBALS['TL_LANG']['wbgym']['weekday'],
			'eval'				=> array('includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql'				=> "varchar(10) NOT NULL default ''"
		),
		'time_a' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['time_a'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'isB' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['isB'],
			'exclude'			=> false,
			'inputType'			=> 'checkbox',
			'eval'				=> array('submitOnChange' => true),
			'sql'				=> "char(1) NOT NULL default ''"
		),
		'weekday_b' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['weekday_b'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'options'			=> &$GLOBALS['TL_LANG']['wbgym']['weekdayOptions'],
			'reference'			=> &$GLOBALS['TL_LANG']['wbgym']['weekday'],
			'eval'				=> array('includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql'				=> "varchar(10) NOT NULL default ''"
		),
		'time_b' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['time_b'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'page' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_workshop']['page'],
			'exclude'			=> false,
			'inputType'			=> 'pageTree',
			'foreignKey'		=> 'tl_page.title',
			'eval'				=> array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
			'sql'				=> "int(10) unsigned NOT NULL default '0'"
		)

	)
);

class tl_workshop extends Backend {

	public function generateLabels($row, $label, DataContainer $dc, $args) {
		$args[4] = WBGym\WBGym::workshopTimeString($row);
		return $args;
	}

}

?>