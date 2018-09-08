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
 * Namespace
 */
namespace WBGym;

class ContentFacultySubjects extends \ContentElement {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'wb_faculty_subjects';

    /**
     * Generate content element
     */
    protected function compile() {
        $faculty = $this->arrData['wb_faculty'];
        $this->Template->faculty = $faculty;
        $this->Template->facultyName = $GLOBALS['TL_LANG']['wbgym']['faculties'][$faculty];

        $arrSubjects = array();
        $objSubjects = $this->Database->prepare('SELECT * FROM tl_subject WHERE faculty = ? ORDER BY name')
                ->execute($faculty);
        while ($arrSubject = $objSubjects->fetchAssoc()) {
            $arrSubject['headTeacher'] = WBGym::teacher($arrSubject['headTeacher']);
            $arrSubjects[] = $arrSubject;
        }

        $this->Template->subjects = $arrSubjects;
    }

}

?>