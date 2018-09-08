<?php

/**
 * WBGym
 * 
 * Copyright (C) 2017 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Johannes Cram <johannes@jonesmedia.de>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Namespace
 */
namespace WBGym;

class ContentWorkshopDetails extends \ContentElement {
    
    /**
    * Template
    * @var string
    */
    protected $strTemplate = 'wb_workshop_details';
        
    /**
    * Generate content element
    */
    protected function compile() {
        $this->import('Database');
        $arrWorkshop = $this->Database->prepare("SELECT * FROM tl_workshop WHERE id = ?")
            ->execute($this->arrData['wb_workshop'])->fetchAssoc();
        $arrWorkshop['time_string'] = WBGym::workshopTimeString($arrWorkshop);  
        
        //unset non-active dates, they are saved in the DB due to the contao DCA behaviour
        if(!$arrWorkshop['isAll']) {
            unset($arrWorkshop['weekday_all']);
        }
        if(!$arrWorkshop['isA']) {
            unset($arrWorkshop['weekday_a']);
        }
        if(!$arrWorkshop['isB']) {
            unset($arrWorkshop['weekday_b']);
        }
        
        $arrWorkshop['next'] = date('d.m.Y',strtotime(WBCalendar::getNextRecurring($arrWorkshop['weekday_all'],$arrWorkshop['weekday_a'],$arrWorkshop['weekday_b'])));

        $this->Template->workshop = $arrWorkshop;
    }
}