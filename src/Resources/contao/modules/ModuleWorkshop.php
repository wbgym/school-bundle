<?php

/**
 * WBGym
 * 
 * Copyright (C) 2015 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package 	WGBym
 * @version 	0.3.0
 * @author 		Johannes Cram <j-cram@gmx.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Namespace
 */
namespace WBGym;

class ModuleWorkshop extends \Module
{
protected $strTemplate = 'wb_workshop';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### WBGym Arbeitsgemeinschaften ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		return parent::generate();
		
	}

protected function compile(){
	  $workshops = $this->Database->prepare("SELECT * FROM tl_workshop ORDER BY name")
						->execute();
	  while($arrWorkshop = $workshops->fetchAssoc()) {
		$arrWorkshop['time_string'] = WBGym::workshopTimeString($arrWorkshop);  
		$arrWorkshops[] = $arrWorkshop;
	  }
	  
	  $this->Template->workshops = $arrWorkshops;
}

}
?>