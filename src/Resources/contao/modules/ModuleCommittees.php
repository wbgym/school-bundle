<?php

/**
 * WBGym
 * 
 * Copyright (C) 2015 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package 	WGBym
 * @version 	0.3.0
 * @author 		Johannes Cram <craj.me@gmail.com>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Namespace
 */
namespace WBGym;

class ModuleCommittees extends \Module
{
	protected $strTemplate = 'wb_committees';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### WBGym Gremien ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		return parent::generate();
		
	}

	protected function compile()
	{
		$this->import('Database');
		$this->loadLanguageFile('tl_committees');
		
		//collect data from tl_committes table and sort it
		$data = $this->Database->prepare("SELECT * FROM tl_committees")->execute();
		while($arrData = $data->fetchAssoc()) 
		{
			$arrRawData[] = $arrData;
		}

		// Sort Vertreter so that "Sprecher" are first and "Sprecher - stellv." are second
		usort($arrRawData, ["WBGym\ModuleCommittees","sortByCommission"]);

		foreach($arrRawData as $arrData)
		{
			switch($arrData['type'])
			{
				case 'VTst' :
					$arrRows[$arrData['type']][$arrData['commission']][] = $arrData;
					break;
				case 'VTpa' :
					$arrRows[$arrData['type']][$arrData['commission']][] = $arrData;
					break;
				case 'VTte' : 
					$arrRows[$arrData['type']][$arrData['commission']][] = $arrData;
					break;
				default :
					$arrRows[$arrData['type']][] = $arrData;
					break;
			}
		}

		//add string names
		foreach($arrRows as $i => $arrEntry) {
			if($i == 'SKst' || $i == 'SKstST' || $i == 'SKte' || $i == 'SKteST' || $i == 'SKpa' || $i == 'SKpaST') {
				foreach($arrEntry as $i2 => $arrMember) {
					if($i == 'SKst' || $i == 'SKstST') $arrRows[$i][$i2]['m_str'] = WBGym::student($arrMember['m_id']);
					if($i == 'SKte' || $i == 'SKteST') $arrRows[$i][$i2]['m_str'] = WBGym::teacher($arrMember['m_id']);
					if($i == 'SKpa' || $i == 'SKpaST') $arrRows[$i][$i2]['m_str'] = WBGym::cParent($arrMember['p_id']);
				}
			}
			if($i == 'VTte' || $i == 'VTst' || $i == 'VTpa') {
				foreach($arrEntry as $i2 => $arrCommittee) {
					foreach($arrCommittee as $i3 => $arrMember) {
						if($i == 'VTte') $arrRows[$i][$i2][$i3]['m_str'] = WBGym::teacher($arrMember['m_id']);
						if($i == 'VTst') $arrRows[$i][$i2][$i3]['m_str'] = WBGym::student($arrMember['m_id']);
						if($i == 'VTpa') $arrRows[$i][$i2][$i3]['m_str'] = WBGym::cParent($arrMember['p_id']);
					}
				}
			}
		}
		
		//collect data from tl_subject table
		$dataFK = $this->Database->prepare("SELECT * FROM tl_subject ORDER BY name")->execute();
		while($arrData = $dataFK->fetchAssoc()) {
			if($arrData['visible'] && $arrData['isConference']) $arrFKs[$arrData['id']] = $arrData;
		}

		//add string names
		foreach($arrFKs as $id => $fk) {
			$arrFKs[$id]['t_str'] = WBGym::teacher($fk['headTeacher']);
			$arrFKs[$id]['p_str'] = WBGym::cParent($fk['conference_parent']);
			$arrFKs[$id]['s_str'] = WBGym::student($fk['conference_student']);
		}
		
		//collect data from tl_courses table
		$dataCourses = $this->Database->prepare("SELECT * FROM tl_courses ORDER BY grade,formSelector,graduation desc")->execute();
		while($arrData = $dataCourses->fetchAssoc()) {
			$arrCourses[$arrData['id']] = $arrData;
		}

		//add strings
		foreach($arrCourses as $id => $course) {
			$arrCourses[$id]['string'] = WBGym::course($course['id']);
			$arrCourses[$id]['classsp1_str'] = WBGym::student($course['classsp1'],false,false);
			$arrCourses[$id]['classsp2_str'] = WBGym::student($course['classsp2'],false,false);
			$arrCourses[$id]['parentsp1_str'] = WBGym::cParent($course['parentsp1']);
			$arrCourses[$id]['parentsp2_str'] = WBGym::cParent($course['parentsp2']);
		}
		
		$this->Template->data = $arrRows;
		$this->Template->fks = $arrFKs;
		$this->Template->courses = $arrCourses;
	}

	public static function sortByCommission($a, $b)
	{
		// Only sort Vertreter, not Schulkonferenz members
		if ($a['commission'] != '' || $b['commission'] != '')
		{
			/*
			* First level sorting: Schülervertretung before Elternvertretung before Lehrervertretung
			*/
			if ($a['type'] != $b['type'])
			{
				// Schüler: type == VTst || Lehrer: type == VTte || Eltern: type == VTpa
				
				if ($a['type'][2] == 's') return -1;
				elseif ($a['type'][2] == 'p' && $b['type'][2] != 's') return -1;
				return 1;
			}

			/*
			* Second level sorting: all Sprecher before all others
			*/
			// High priority commissions are Esp, EspST, Ssp, SspST
			$arrHighPriorityFirstChar = ['E','S'];

			// Low priority commissions are BLK, BSK, MdKs, MdKt, etc
			$arrLowPriorityFirstChar = ['B','M'];

			// $a has higher priority than $b
			if (in_array($a['commission'][0],$arrHighPriorityFirstChar) && in_array($b['commission'][0],$arrLowPriorityFirstChar))
			{
				return -1;
			}
			// $b has higher priority than $a
			elseif (in_array($a['commission'][0],$arrLowPriorityFirstChar) && in_array($b['commission'][0],$arrHighPriorityFirstChar))
			{
				return 1;
			}

			/*
			* Third level sorting: Sprecher before SprecherStellv before Beratendes Mitglied before Mitglied ...rat
			*/
			// commissions with shorter names (e.g. Ssp, Esp || BLK) have higher priorities
			// than commissions with longer names (e.g. SspST, EspST || MdKs)
			elseif (strlen($a['commission']) != strlen($b['commission']))
			{
				return strlen($a['commission']) < strlen($b['commission']) ? -1 : 1;
			}
		}
		return 0;
	}
}
?>