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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_committees']['type']			= array('Typ', 'Bitte geben Sie den Typ des Vertreters an.');
$GLOBALS['TL_LANG']['tl_committees']['course']		= array('von Kurs', 'Wählen Sie den Kurs des Kindes des Vertreters');
$GLOBALS['TL_LANG']['tl_committees']['m_id']		= array('Person', 'Wählen Sie die Person aus.');
$GLOBALS['TL_LANG']['tl_committees']['p_id']		= array('Elternteil', 'Geben Sie das Elternteil an. Elternteile können unter "Elternteile verwalten" angelegt werden.');
$GLOBALS['TL_LANG']['tl_committees']['commission']		= array('Kommission/Funktion', 'Geben Sie die Bezeichnung der Funktion und die Kommission an.');

$GLOBALS['TL_LANG']['tl_committees']['typeOptions'] = array(
				'SK'	=> 'Schulkonferenz',
				'SKvs'	=> 'SK-Vorstand',
				'SKvsST'=> 'SK-Vorstand - stellv.',
				'SKst'	=> 'SK-Schüler',
				'SKstST'=> 'SK-Schüler - stellv.',
				'SKte'	=> 'SK-Lehrer',
				'SKteST'=> 'SK-Lehrer - stellv.',
				'SKpa'	=> 'SK-Elternteil',
				'SKpaST'=> 'SK-Elternteil - stellv.',

				'VT'	=> 'Vertretungen',
				'VTte'	=> 'Lehrervertretung',
				'VTpa'	=> 'Elternvertretung',
				'VTst'	=> 'Schülervertretung',
);

$GLOBALS['TL_LANG']['tl_committees']['commission_options'] = array(
				'Ssp'	=> 'Schülersprecher',
				'SspST'	=> 'Schülersprecher - stellv.',
				'Esp'	=> 'Elternsprecher',
				'EspST'	=> 'Elternsprecher - stellv.',
				'BLK'	=> 'Beratendes Mitglied der Lehrerkonferenz',
				'BEK'	=> 'Beratendes Mitglied der Elternkonferenz',
				'BSK'	=> 'Beratendes Mitglied der Schülerkonferenz',
				'MdKs'	=> 'Mitglied des Kreisschülerrats',
				'MdKe'	=> 'Mitglied des Kreiselternrats',
				'MdKl'	=> 'Mitglied des Kreislehrerrats',
);

/**
* Header
*/

$GLOBALS['TL_LANG']['tl_committees']['type_header']		= 'Art des Vertreters';
$GLOBALS['TL_LANG']['tl_committees']['member_header']	= 'Angaben zum Vertreter';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_committees']['new']			= array('Neuer Vertreter', 'Einen neuen Vertreter hinzufügen');
$GLOBALS['TL_LANG']['tl_committees']['show']			= array('Details', 'Details zum Vertreter ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_committees']['edit']			= array('Vertreter bearbeiten', 'Vertreter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_committees']['copy']			= array('Vertreter duplizieren', 'Vertreter ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_committees']['delete']		= array('Vertreter löschen', 'Vertreter ID %s löschen');

$GLOBALS['TL_LANG']['tl_committees']['cparents']		= array('Elternteile verwalten', 'Elternteile in Mitwirkungsgremien verwalten');

?>
