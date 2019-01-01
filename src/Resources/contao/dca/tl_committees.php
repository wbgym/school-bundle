<?php

/**
 * WBGym
 * 
 * Copyright (C) 2015 - 2016 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Johannes Cram <j-cram@gmx.de>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_committees
 */
$GLOBALS['TL_DCA']['tl_committees'] = array(
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
			'mode'	=> 1,
			'flag'		=> 12,
			'fields'	=> array('type'),
			'panelLayout' => 'filter,sort;search,limit',
	),
	'label' => array(
		'fields'		=> array('type'),
		'showColumns'	=> false,
		'label_callback'		=> array('tl_committees', 'generateListLabels')
	),
	'global_operations' => array(
			'all' => array(
				'label'		=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'		=> 'act=select',
				'class'		=> 'header_edit_all',
				'attributes'=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
			'cparents' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_committees']['cparents'],
				'href'				  => 'table=tl_committees_parent',
				'icon'                => 'bundles/wbgymschool/cparents.png',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="c"'
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
		'__selector__' => array('type'),
		'default' 	=> '{type_header},type;{member_header},m_str',
		
		'SKvs'		=> '{type_header},type;{member_header},m_str',
		'SKvsST'	=> '{type_header},type;{member_header},m_str',
		'SKst'		=> '{type_header},type;{member_header},m_id',
		'SKstST'	=> '{type_header},type;{member_header},m_id',
		'SKte'		=> '{type_header},type;{member_header},m_id',
		'SKteST'	=> '{type_header},type;{member_header},m_id',
		'SKpa'		=> '{type_header},type;{member_header},p_id',
		'SKpaST'	=> '{type_header},type;{member_header},p_id',
		
		'VTte'		=> '{type_header},type,commission;{member_header},m_id',
		'VTpa'		=> '{type_header},type,commission;{member_header},p_id',
		'VTst'		=> '{type_header},type,commission;{member_header},m_id'
	),

	// Fields
	'fields' => array(
		'id' => array(
			'sql'		=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql'		=> "int(10) unsigned NOT NULL default '0'",
			'sorting'	=> true,
		),
		'type' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['type'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'	=> array(
				'SK'	=> array('SKvs','SKvsST','SKst','SKstST','SKte','SKteST','SKpa','SKpaST'),
				'VT'	=> array('VTte','VTpa','VTst')
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_committees']['typeOptions'],
			'search'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => true, 'submitOnChange' => true, 'tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'm_id' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['m_id'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_member.username',
			'options_callback' => array('tl_committees', 'memberList'),
			'eval'		=> array('chosen' => true, 'includeBlankOption' => true, 'tl_class' =>'w50'),
			'sql'		=> "int(10) NOT NULL default '0'"
		),
		'm_str' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['m_str'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'eval'		=> array('tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'p_id' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['p_id'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'foreignKey'=> 'tl_committees_parent.id',
			'options_callback' => array('WBGym\WBGym','cParentList'),
			'eval'		=> array('tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'commission' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_committees']['commission'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options_callback'	=> array('tl_committees','getCommissionOptions'),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_committees']['commission_options'],
			'eval'		=> array('mandatory' => true, 'tl_class' =>'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		)
	)
);

class tl_committees extends Backend
{
	public function generateListLabels($row, $label, DataContainer $dc, $args) 
	{	
		switch($row['type']){
			case 'SKst' :
				$args = WBGym\WBGym::student($row['m_id'],false);
				break;
			case 'SKstST' :
				$args = WBGym\WBGym::student($row['m_id'],false);
				break;
			case 'SKpa' :
				$args = WBGym\WBGym::cParent($row['p_id']);
				break;
			case 'SKpaST' :
				$args = WBGym\WBGym::cParent($row['p_id']);
				break;
			case 'SKvs' :
				$args = $row['m_str'];
				break;
			case 'SKvsST' :
				$args = $row['m_str'];
				break;
			case 'SKte' :
				$args = WBGym\WBGym::teacher($row['m_id']);
				break;
			case 'SKteST' :
				$args = WBGym\WBGym::teacher($row['m_id']);
				break;
			case 'VTpa' :
				$args = $GLOBALS['TL_LANG']['tl_committees']['commission_options'][$row['commission']] . ' | ' . WBGym\WBGym::cParent($row['p_id']);
				break;
			case 'VTst' :
				$args = $GLOBALS['TL_LANG']['tl_committees']['commission_options'][$row['commission']] . ' | ' . WBGym\WBGym::student($row['m_id'],false);
				break;
			case 'VTte' : 
				$args = $GLOBALS['TL_LANG']['tl_committees']['commission_options'][$row['commission']] . ' | ' .WBGym\WBGym::teacher($row['m_id']);
				break;
		}
		return $args;
	}

	public function getCommissionOptions(DataContainer $dc) 
	{
		if ($dc->activeRecord->type == 'VTte')
		{
			return ['BEK','BSK','MdKl'];
		}
		elseif ($dc->activeRecord->type == 'VTst')
		{
			return ['Ssp','SspST','BLK','BEK','MdKs'];
		}
		elseif ($dc->activeRecord->type == 'VTpa')
		{
			return ['Esp','EspST','BLK','BSK','MdKl'];
		}
		return ['Ssp','SspST','BLK','BEK','BSK','MdKs','MdKl','MdKe','Esp','EspST'];
	}
	
	public function memberList(DataContainer $dc) 
	{
		$type = $dc->activeRecord->type;

		if ($type == 'VTst' || $type == 'SKst' || $type == 'SKstST') 
		{
			return WBGym\WBGym::studentList();
		}
		elseif ($type == 'VTte' || $type == 'SKte' || $type == 'SKteST') 
		{
			return WBGym\WBGym::teacherList();
		}

		return WBGym\WBGym::studentList() + WBGym\WBGym::teacherList();
	}
	
}


?>