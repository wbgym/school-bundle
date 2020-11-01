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

class ContentMemberlist extends \ContentElement
{
	protected function compile()
	{
		$this->Template = new \FrontendTemplate($this->arrData['wb_ml_template']);

		$arrGroups = unserialize($this->arrData['wb_ml_groups']);

		$arrMembers = array();
		$members = $this->Database->prepare('SELECT * FROM tl_member ORDER BY lastname, firstname')
									->execute();

		while ($member = $members->fetchAssoc()) {
			$groups = unserialize($member['groups']);
			if(is_array($groups)) {
				foreach ($groups as $iGroup) {
					if (in_array($iGroup, $arrGroups)) {
						$member['groups'] = $groups;
						$arrMembers[] = $member;
						break;
					}
				}
			}
		}

		$memberCount = count($arrMembers);
		for ($i = 0; $i < $memberCount; $i++) {
			if ($arrMembers[$i]['teacher'] == 1) {

				$subjects = array();

				if ($arrMembers[$i]['subjects']) {
					$s = unserialize($arrMembers[$i]['subjects']);

					for ($j = 0; $j < count($s); $j++) {
						$subjectId = $s[$j];
						$subjects[$subjectId] =
						[
							'title' => WBGym::subject($subjectId,true),
							'abbr' =>WBGym::subject($subjectId,false)
						];
					}
				}

				$mail = explode('@', $arrMembers[$i]['email']);
				$localMail = explode('.', $mail[0]);

				$arrMembers[$i]['subjects'] = $subjects;
				$arrMembers[$i]['name'] = WBGym::teacherToString($arrMembers[$i]);
				$arrMembers[$i]['localEmail1'] = $localMail[0];
				$arrMembers[$i]['localEmail2'] = $localMail[1];
			}
		}

		$this->Template->members = $arrMembers;
	}
}

?>
