<?php

/**
 * WBGym
 * 
 * Copyright (C) 2016 Webteam Weinberg-Gymnasium Kleinmachnow
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

class UpdateCourses extends \Backend
{

public function updateCourses(){
	$this->import('Database');
	
	//get all Committee Parents
	$parents = $this->Database->prepare("SELECT * FROM tl_committees_parent")->execute();
	while($parent = $parents->fetchAssoc()) $arrCParents[$parent['id']] = $parent;
	
	//get all courses
	$courses = $this->Database->prepare("SELECT * FROM tl_courses")->execute();
	while($course = $courses->fetchAssoc()) {
		
		$cBefore = $course['id'];
		$cFollowing = $course['following'];
		
		if($cFollowing) {
			
			//Update Classspeakers
			if($course['classsp1']) {
				$cCurrent = WBGym::courseOf($course['classsp1']);
				if($cBefore != $cCurrent && $cCurrent == $cFollowing) $arrNew[$cFollowing]['classsp1'] = $course['classsp1'];
			}
			if($course['classsp2']) {
				$cCurrent = WBGym::courseOf($course['classsp2']);
				if($cBefore != $cCurrent && $cCurrent == $cFollowing) $arrNew[$cFollowing]['classsp2'] = $course['classsp2'];
	
			}
			
			//Update Parentspeakers
			if($course['parentsp1']) {
				$par = $arrCParents[$course['parentsp1']];
				if($par) {
					if($par['st1'] || $par['st2'] || $par['st3']) {
						$cCurrent = array(WBGym::courseOf($par['st1']),WBGym::courseOf($par['st2']),WBGym::courseOf($par['st3']));
						if(!in_array($cBefore,$cCurrent) && in_array($cFollowing,$cCurrent))
							$arrNew[$cFollowing]['parentsp1'] = $course['parentsp1'];
					}
					elseif($par['course']) {
						$cCurrent = $par['course'];
						if($cBefore != $cCurrent && $cCurrent == $cFollowing)
							$arrNew[$cFollowing]['parentsp1'] = $course['parentsp1'];
					}
				}
			}
			if($course['parentsp2']) {
				$par = $arrCParents[$course['parentsp2']];
				if($par) {
					if($par['st1'] || $par['st2'] || $par['st3']) {
						$cCurrent = array(WBGym::courseOf($par['st1']),WBGym::courseOf($par['st2']),WBGym::courseOf($par['st3']));
						if(!in_array($cBefore,$cCurrent) && in_array($cFollowing,$cCurrent))
							$arrNew[$cFollowing]['parentsp2'] = $course['parentsp2'];
					}
					elseif($par['course']) {
						$cCurrent = $par['course'];
						if($cBefore != $cCurrent && $cCurrent == $cFollowing)
							$arrNew[$cFollowing]['parentsp2'] = $course['parentsp2'];
					}
				}
			}
		}
		//LIMITATION: zwei Schülersprecher, aber nur einer aktualisiert? -> Überschreiben verhindern
	}
	
	if($arrNew) {
		$strLog = 'Folgende Kurse wurden aktualisiert:<br /><br />';
		foreach($arrNew as $i => $course) {
			
			$this->Database->prepare("UPDATE tl_courses SET " .$this->implodeAssoc($course). " WHERE id=?")->execute($i);
			
			$strLog .= '<b>'.WBGym::course($i) . ':</b><br /><ul>';
			foreach($course as $field => $sp) {
				if($field == 'classsp1' || $field == 'classsp2') $strLog .= '<li>'.WBGym::student($sp).'</li>';
				elseif($field == 'parentsp1' || $field == 'parentsp2') $strLog .= '<li>'.WBGym::cParent($sp).'</li>';
			}
			$strLog .= '</ul><br />';
		}
		$strLog .= 'WICHTIG: Sie sollten jetzt die neuen Kurssprecher manuell überprüfen, da alle Gremien neu gewählt wurden und Änderungen vorgenommen worden sein könnten.<br /><br />
		BITTE BEACHTEN SIE auch, dass einige Elternteile möglicherweise statische Klassenangaben besitzen und daher nicht korrekt aktualisiert werden konnten.';
	}
	else {
		$strLog = 'Keine Änderungen vorgenommen.';
	}
	return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=updatecourses', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>' . \Message::generate() .'
<div class="tl_formbody_edit">
	<div class="tl_tbox">
		<h3>'.$GLOBALS['TL_LANG']['update_courses']['title'][0].'</h3>
			  '.$strLog.'
	</div>
</div>';
}

protected function implodeAssoc($arr) {
	$str = '';
	$first = true;
	foreach ($arr as $key => $val) {
		if ($first) {
			$first = false;
		} else {
			$str .= ',';
		}
		$str .= $key . '="' . $val . '"';
	}
	return $str;
}
}

?>