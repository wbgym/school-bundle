<?php

/**
 * WBGym
 * 
 * Copyright (C) 2008-2013 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Marvin Ritter <marvin.ritter@gmail.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_subject
 */
$GLOBALS['TL_DCA']['tl_subject'] = array(
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
			'fields'	=> array('faculty'),
			'flag'		=> 11
		),
		'label' => array(
			'fields'		=> array('name', 'abbreviation', 'headTeacher'),
			'showColumns'	=> true,
			'label_callback'=> array('tl_subject', 'nameHeadTeacher')
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
				'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'copy' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['copy'],
				'href'		=> 'act=copy',
				'icon'		=> 'copy.gif'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_subject']['visible'],
				'icon'                => 'invisible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_subject', 'toggleIcon')
			),
			'delete' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array(
		'__selector__' 	=> array('isSubject','isConference'),
		'default' 		=> '{general_header},name,faculty,rooms;{subject_header},isSubject;{conference_header},isConference;{visible_header},visible'
	),
	
	//Subpalettes
	'subpalettes' => array(
		'isSubject' 	=> 'page,abbreviation',
		'isConference' 	=> 'conference_parent,conference_student,headTeacher'
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
			'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['name'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'search'	=> true,
			'filter'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'abbreviation' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['abbreviation'],
			'exclude'	=> false,
			'inputType'	=> 'text',
			'search'	=> true,
			'filter'	=> true,
			'sorting'	=> true,
			'eval'		=> array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'		=> "varchar(32) NOT NULL default ''"
		),
		'isSubject' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['isSubject'],
			'exclude'	=> false,
			'inputType'	=> 'checkbox',
			'filter'	=> true,
			'sorting'	=> true,
			'eval'		=> array('submitOnChange' => true),
			'sql'		=> "char(1) NOT NULL default '1'"
		),
		'isConference' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['isConference'],
			'exclude'	=> false,
			'inputType'	=> 'checkbox',
			'filter'	=> true,
			'sorting'	=> true,
			'eval'		=> array('submitOnChange' => true),
			'sql'		=> "char(1) NOT NULL default '1'"
		),
		'faculty' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_subject']['faculty'],
			'exclude'	=> false,
			'inputType'	=> 'select',
			'options'	=> &$GLOBALS['TL_LANG']['wbgym']['facultyIds'],
			'reference'	=> &$GLOBALS['TL_LANG']['wbgym']['faculties'],
			'eval'		=> array('tl_class' => 'w50'),
			'sql'		=> "varchar(64) NOT NULL default ''"
		),
		'headTeacher' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['headTeacher'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'foreignKey'		=> 'tl_member.username',
			'options_callback'	=> array('WBGym\WBGym', 'teacherList'),
			'eval'				=> array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
			'sql'				=> "int(10) unsigned NOT NULL default '0'"
		),
		'rooms' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['rooms'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'eval'				=> array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'page' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['page'],
			'exclude'			=> false,
			'inputType'			=> 'pageTree',
			'foreignKey'		=> 'tl_page.title',
			'eval'				=> array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
			'sql'				=> "int(10) unsigned NOT NULL default '0'"
		),
		'visible' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['visible'],
			'exclude'			=> false,
			'filter'			=> true,
			'sorting'			=> true,
			'inputType'			=> 'checkbox',
			'eval'				=> array('tl_class' => 'w50'),
			'sql'				=> "char(1) NOT NULL default '1'",
			'search'			=> true
		),
		'conference_student' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['conference_student'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'foreignKey'		=> 'tl_member.username',
			'options_callback'	=> array('WBGym\WBGym','studentList'),
			'eval'				=> array('tl_class' => 'w50','includeBlankOption'=>true,'chosen'=>true),
			'sql'				=> "int(10) NOT NULL default '0'",
			'search'			=> true
		),
		'conference_parent' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_subject']['conference_parent'],
			'exclude'			=> false,
			'inputType'			=> 'select',
			'foreignKey'		=> 'tl_committees_parent.lastname',
			'options_callback'	=> array('WBGym\WBGym','cParentList'),
			'eval'				=> array('includeBlankOption'=>true,'tl_class' => 'w50','chosen' => true),
			'sql'				=> "int(10) NOT NULL default '0'",
			'search'			=> true
		)
	)
);

class tl_subject extends Backend {

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['visible'];

		if ($row['visible'])
		{
			$icon = 'visible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}
	

	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{


		$objVersions = new Versions('tl_subject', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_subject']['fields']['approved']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_subject']['fields']['approved']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		$time = time();

		// Update the database
		$this->Database->prepare("UPDATE tl_subject SET tstamp=$time, visible='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_subject.id='.$intId.'" has been created'.$this->getParentEntries('tl_subject', $intId), __METHOD__, TL_GENERAL);


	}

	public function nameHeadTeacher($row, $label, DataContainer $dc, $args) {
		$args[2] = WBGym\WBGym::teacher($row['headTeacher']);
		return $args;
	}

}

?>