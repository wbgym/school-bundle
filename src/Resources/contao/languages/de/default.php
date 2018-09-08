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

$GLOBALS['TL_LANG']['CTE']['wbgym']					= 'WBGym';
$GLOBALS['TL_LANG']['CTE']['wb_faculty_subjects']	= array('Fachbereichsdetails', 'Fügt eine Tabelle mit den Fächern des Fachbereichs ein.');

$GLOBALS['TL_LANG']['CTE']['wb_memberlist']			= array('Mitgliedliste', 'Erzeugt Mitgliedliste aus Mitgliedergruppen.');
$GLOBALS['TL_LANG']['CTE']['wb_subject_details']	= array('Fachdetails', 'Fügt Fachdetails wie die unterrichtenden Lehrer und die Fachräume ein.');
$GLOBALS['TL_LANG']['CTE']['wb_workshop_details']	= array('AG-Details','Fügt Seiten von  Arbeitsgemeinschaften den Raum, die Leitenden und Zeiten hinzu.');

$GLOBALS['TL_LANG']['wbgym']['facultyIds']					= array('natural', 'linguistics', 'social', 'sports', 'creativity');
$GLOBALS['TL_LANG']['wbgym']['faculties']['natural'] 		= 'Naturwissenschaften';
$GLOBALS['TL_LANG']['wbgym']['faculties']['linguistics'] 	= 'Sprachen';
$GLOBALS['TL_LANG']['wbgym']['faculties']['social'] 		= 'Gesellschaftswissenschaften';
$GLOBALS['TL_LANG']['wbgym']['faculties']['sports'] 		= 'Sport';
$GLOBALS['TL_LANG']['wbgym']['faculties']['creativity'] 	= 'Kreative Unterrichtsfächer';

$GLOBALS['TL_LANG']['wbgym']['weekdayIds']			= array('Mo', 'Di', 'Mi', 'Do', 'Fr');
$GLOBALS['TL_LANG']['wbgym']['weekdayOptions'] 		= array('mon','tue','wed','thu','fri','sat','sun');

$GLOBALS['TL_LANG']['wbgym']['weekday'] = array(
	'mon'	=> 'Montag',
	'tue'	=> 'Dienstag',
	'wed'	=> 'Mittwoch',
	'thu'	=> 'Donnerstag',
	'fri'	=> 'Freitag',
	'sat'	=> 'Samstag',
	'sun'	=> 'Sonntag'
);

$GLOBALS['TL_LANG']['wbgym']['weekdayShort'] = array(
	'mon'	=> 'Mo',
	'tue'	=> 'Di',
	'wed'	=> 'Mi',
	'thu'	=> 'Do',
	'fri'	=> 'Fr',
	'sat'	=> 'Sa',
	'sun'	=> 'So'
);

$GLOBALS['TL_LANG']['wbgym']['headTeacherMale']		= 'Fachbereichsleiter';
$GLOBALS['TL_LANG']['wbgym']['headTeacherFemale']	= 'Fachbereichsleiterin';
$GLOBALS['TL_LANG']['wbgym']['form']				= 'Klasse';
$GLOBALS['TL_LANG']['wbgym']['grades']				= 'Klassenstufen';

$GLOBALS['TL_LANG']['wbgym']['holiday_types'] = array(
	'holidays' 			=> 'Ferien',
	'variable_holiday'	=> 'Variabler Ferientag',
	'nat_holiday' 		=> 'Feiertag',
	'project'			=> 'Projekt',
);

$GLOBALS['TL_LANG']['MSC']['mrs']					= 'Frau';
$GLOBALS['TL_LANG']['MSC']['mr']					= 'Herr';

$GLOBALS['TL_LANG']['WHM']['name_empty']	= 'Bitte füllen Sie das Feld "Name"aus.';
$GLOBALS['TL_LANG']['WHM']['tutorium_empty']	= 'Bitte füllen Sie das Feld "Tutorium"aus.';
$GLOBALS['TL_LANG']['WHM']['email_empty']	= 'Bitte füllen Sie das Feld "Kontakt-E-Mail"aus.';
$GLOBALS['TL_LANG']['WHM']['wishnum_empty']	= 'Bitte füllen Sie das Feld "Erst-/Zweitwunsch"aus.';
$GLOBALS['TL_LANG']['WHM']['success']	= 'Ihr Wunsch wurde erfolgreich übernommen.';
$GLOBALS['TL_LANG']['WHM']['wishnum_already_exists']	= 'Es wurde bereits ein Wunsch mit dieser Priorität für diese Klasse/Tutorium eingetragen!';
$GLOBALS['TL_LANG']['WHM']['access_denied']	= 'Es können ausschließlich Lehrer bzw. Schüler- oder Tutoriumssprecher Standwünsche eintragen.';
$GLOBALS['TL_LANG']['WHM']['only_logged_in']	= 'Sie müssen als Lehrer bzw. Schüler- oder Tutoriumssprecher eingeloggt sein, um Standwünsche eintragen zu können.';

/*
* UpdateCourses
*/
$GLOBALS['TL_LANG']['update_courses']['title'] = array('Kurssprecher aktualisieren','Hier können Sie Klassen- und ELternsprecher für ein neues Schuljahr automatisch für die neue Klasse übernehmen.');
$GLOBALS['TL_LANG']['update_courses']['confirm'] = 'Wollen Sie wirklich Klassen- und Elternsprecher ÜBERSCHREIBEN?';
?>