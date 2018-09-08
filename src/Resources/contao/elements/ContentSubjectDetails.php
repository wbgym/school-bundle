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

class ContentSubjectDetails extends \ContentElement {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'wb_subject_details';

    /**
     * Generate content element
     */
    protected function compile() {
        $intSubject = $this->arrData['wb_subject'];

        // get general information about the subject
        $objSubjectResult = $this->Database->prepare('SELECT * FROM tl_subject WHERE id = ?')
                ->limit(1)
                ->execute($intSubject);

        $arrSubject = $objSubjectResult->fetchAssoc();
        $intHeadTeacher = $arrSubject['headTeacher'];
        $this->Template->name = $arrSubject['name'];
        $this->Template->abbreviation = $arrSubject['abbreviation'];
        $this->Template->faculty = $GLOBALS['TL_LANG']['tl_subject']['faculties'][$arrSubject['faculty']];
        $this->Template->rooms = $arrSubject['rooms'];


        // get all the subjects teachers
        $arrTeachers = array();
        $objTeacherResult = $this->Database->prepare('SELECT * FROM tl_member WHERE teacher = 1 ORDER BY lastname, firstname')
                ->execute();

        while ($arrTeacher = $objTeacherResult->fetchAssoc()) {
            $subjects = deserialize($arrTeacher['subjects']);
            if (is_array($subjects) && in_array($intSubject, $subjects)) {
                $arrTeacher['name'] = WBGym::teacherToString($arrTeacher);
                $arrTeacher['isHeadTeacher'] = $arrTeacher['id'] == $intHeadTeacher;
                $arrTeacher['subjects'] = $subjects;

                if ($arrTeacher['isHeadTeacher']) {
                    array_unshift($arrTeachers, $arrTeacher);
                    $this->Template->headTeacher = $arrTeacher;
                } else {
                    $arrTeachers[] = $arrTeacher;
                }
            }
        }

        $this->Template->teachers = $arrTeachers;
    }

}

?>