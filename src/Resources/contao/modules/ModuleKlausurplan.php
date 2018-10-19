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
 * Namespace
 */
namespace WBGym;

class ModuleKlausurplan extends \Module
{

	/**
	 * Template file
	 * @var string
	 */
	protected $strTemplate = 'wb_klausurplan';
		
    protected function compile()
    {
		$xml = simplexml_load_file(TL_ROOT . '/files/klausurplan/klausurplan.xml');
		$this->Template->klausurplan = $xml;
		$this->Template->last_modified = mktime(0, 0, 0, (string) $xml['monat'], (string) $xml['tag'], (string) $xml['jahr']);
    }
}

?>