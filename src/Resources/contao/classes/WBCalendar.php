<?php

/**
 * WBGym
 *
 * Copyright (C) 2016 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * Provides methods to handle A/B-week events and to generate the school calendar.
 *
 * @package     WGBym
 * @author		Johannes Cram <craj.me@gmail.com>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL
 */

/**
 * Namespace
 */
namespace WBGym;

class WBCalendar extends \System {

	protected static $objInstance;

	public static $arrDBCalendar = null;
	public static $arrFullCalendar = null;

	public static function initialize() {
	}

	/**
	* Load School Years and Holidays
	* @var $arrDBCalendar
	*/
	public static function loadData() {
		if(static::$arrDBCalendar === null) {
			$years = \Database::getInstance()->prepare("SELECT * FROM tl_schoolyear")->execute();
			while($arrSY = $years->fetchAssoc()) {
				static::$arrDBCalendar[$arrSY['id']] = $arrSY;
			}
			$holidays = \Database::getInstance()->prepare("SELECT * FROM tl_schoolyear_holiday")->execute();
			while($arrHD = $holidays->fetchAssoc()) {
				static::$arrDBCalendar[$arrHD['pid']]['holidays'][$arrHD['id']] = $arrHD;
			}
		}
		return true;
	}

	/**
	* Get graduation year of current school year
	* @return int
	*/
	public static function schoolYear() {
		static::loadData();
		foreach(static::$arrDBCalendar as $sy) {
			//return graduation year of current school year if available
			if($sy['start'] < time() && $sy['end'] > time()) return date('Y',$sy['end']);
			//else - if a school year is found which begins later than now but already in the current calendar year, return the end year
			//-> summer holidays := following school year
			elseif($sy['start'] > time() && date('Y',$sy['start']) == date('Y')) return date('Y',$sy['end']);
		}
		return false;
	}
	/**
	* Get graduation year of any school year
	* @param int $year | unix time of some day in the year
	* @return int
	*/
	public static function schoolYearAtTime($year = null) {
		static::loadData();
		if (is_null($year)) return static::schoolYear();
		foreach(static::$arrDBCalendar as $sy) {
			//return graduation year of current school year if available
			if($sy['start'] < $year && $sy['end'] > $year) return date('Y',$sy['end']);
			//else - if a school year is found which begins later than now but already in the current calendar year, return the end year
			//-> summer holidays := following school year
			elseif($sy['start'] > $year && date('Y',$sy['start']) == date('Y')) return date('Y',$sy['end']);
		}
		return false;
	}
	/**
	* Get id of current school year
	* @param int $grad
	* @return int
	*/
	public static function schoolYearId($grad = null) {
		static::loadData();
		if($grad == null) {
			$grad = static::schoolYear();
		}
		foreach(static::$arrDBCalendar as $sy) {
			if(date('Y',$sy['end']) == $grad) return $sy['id'];
		}
		return false;
	}

	/**
	* Create full calendar array incl. A/B-Week or Holiday/Project information for every day of the school year
	*
	* @param str $year 'all' | 'current' | int $schoolYearId
	* @var array $arrDBCalendar
	* @var array $arrFullCalendar
	* @return array
	*/
	public static function generateFullCalendar($years = 'current') {
		static::loadData();
		$oneday = new \DateInterval('P1D');

		//check param
		if($years == 'current')
			$arrCalRaw = array(static::$arrDBCalendar[static::schoolYearId()]);
		elseif($years == 'all')
			$arrCalRaw = static::$arrDBCalendar;
		elseif(!empty(static::$arrDBCalendar[$years]))
			$arrCalRaw = array(static::$arrDBCalendar[$years]);
		else return false;

		foreach ($arrCalRaw as $sy) {
			$arrDays[$sy['id']] = array();

			/*
			* put all days of holiday into array
			*/
			if($sy['holidays']) {
				foreach($sy['holidays'] as $holi) {
					//if entry has date range
					if($holi['start'] !== null && $holi['end'] !== null) {

						//create date period
						$begin = new \DateTime(date('Y-m-d',$holi['start']));
						$end = new \DateTime(date('Y-m-d',$holi['end']));
						$end->add($oneday); //"end" day belongs also to the holiday period
						$period = new \DatePeriod($begin, $oneday, $end);

						//add every day of holiday period to array
						foreach($period as $day){
							$arrIrregularDays[$day->format('Y-m-d')] = $holi;
						}
					}
					//if entry has only one date
					else {
						$arrIrregularDays[date('Y-m-d',$holi['date'])] = $holi;
					}
				}
			}

			/*
			* check for every day of schoolyear if the day is a holiday
			* if it's no holiday, assign the day week A or B status
			*/

			//create DateTime period
			$begin = new \DateTime(date('Y-m-d',$sy['start']));
			$end = new \DateTime(date('Y-m-d',$sy['end']));
			$end->add($oneday);	//"end" day belongs also to the school year
			$period = new \DatePeriod($begin, $oneday, $end);

			$weekStatus = 'A'; //initialize weekStatus
			foreach($period as $objDate) {
				//current day
				$curWeek = $objDate->format('W');
				$curDate = $objDate->format('Y-m-d');
				$curWeekday = $objDate->format('w');


				//find next SCHOOLday
				//Friday -> 3 days until monday
				if($curWeekday == 5) { $interval = new \DateInterval('P3D'); }
				//Saturday -> 2 days until monday
				elseif ($curWeekday == 6) { $interval = new \DateInterval('P2D'); }
				//Sunday or Mon-Thu -> 1 day until next school day
				else { $interval = $oneday; }

				$objNextSDate = clone $objDate;
				$objNextSDate->add($interval);
				$nextSWeek = $objNextSDate->format('W');
				$nextSDate = $objNextSDate->format('Y-m-d');

				//find next DAY (also saturday, sunday)
				$objNextDate = clone $objDate;
				$objNextDate->add($oneday);
				$nextDate = $objNextDate->format('Y-m-d');

				//if day is registered as holiday
				if($arrIrregularDays[$curDate]) {
					//set holiday status
					$arrDays[$sy['id']][$curDate] = $arrIrregularDays[$curDate];
					//change week status if there are no holidays anymore the next DAY and also not the next possible SCHOOLday
					//and the next SCHOOLday is in another calendar week than last school week
					if(!$arrIrregularDays[$nextDate] && !$arrIrregularDays[$nextSDate] && $lastSchoolWeek != $nextSWeek) {
						$weekStatus == 'A' ? $weekStatus = 'B' : $weekStatus = 'A';
					}
				}

				//if day is schoolday (no holiday and no weekend)
				elseif($curWeekday != 0 && $curWeekday != 6) {
					//set week status
					$arrDays[$sy['id']][$curDate] = $weekStatus;
					//change week status if the next SCHOOLday is no holiday and is in another calendar week than this week
					if($nextSWeek != $curWeek && !$arrIrregularDays[$nextSDate]) {
						$weekStatus == 'A' ? $weekStatus = 'B' : $weekStatus = 'A';
					}
					//save current calendar week number if holidays begin the next SCHOOLday
					elseif($arrIrregularDays[$nextSDate]) {
						$lastSchoolWeek = $curWeek;
					}
				}
			}
		}
		static::$arrFullCalendar = $arrDays;
		if($strYears == 'current') return static::$arrFullCalendar[static::schoolYearId()];
		else return static::$arrFullCalendar;
	}

	/**
	* Get Calendar Info for a specific date (Holidays or A/B Week)
	*
	* @param int $intTime Timestamp of day
	* @return str
	*/
	public static function getInfoForDay($intTime) {
		static::loadData();

		//find school year
		foreach (static::$arrDBCalendar as $id => $year) {
			if($intTime > $year['start'] && $intTime < $year['end']) {
				$schoolYearId = $id;
			}
		}

		static::generateFullCalendar($schoolYearId);
		$info = static::$arrFullCalendar[$schoolYearId][date('Y-m-d',$intTime)];

		//Holidays
		if(is_array($info)){
			\System::loadLanguageFile('tl_schoolyear_holiday');
			return array(
				'type' 	=> array(
					'name'	=> $info['type'],
					'str' 	=> &$GLOBALS['TL_LANG']['tl_schoolyear_holiday']['type'][$info['type']]
				),
				'title'	=> $info['name'],
			);
		}
		//Normal school day
		elseif($info) {
			return array(
				'type'	=> array(
					'name'	=> 'lessons',
					'str'	=> 'Unterricht'
				),
				'title'	=> $info
			);
		}
		return false;
	}


	/**
	* Find all dates of a weekly recurring event in the current school year
	*
	* @param string $strWeekdayAll | Options: mon,tue,wed,thu,sat,sun,false
	* @param string $strWeekdayA | Options: mon,tue,wed,thu,sat,sun,false
	* @param string $strWeekdayB | Options: mon,tue,wed,thu,sat,sun,false
	* @param bool $blnHolidays | If true, holidays weeks are also included
	* @param int $schoolYear | set in of the school year.
	*
	* @return array dates in the format 'Y-m-d'
	*/
	public static function getCalRecurring($strWeekdayAll,$strWeekdayA,$strWeekdayB,$blnHolidays = false, $schoolYearId = null) {
		static::loadData();
		static::generateFullCalendar('all');
		$arrCal = static::$arrFullCalendar[$schoolYearId ?? static::schoolYearId()];
		$arrDates = [];

		foreach ($arrCal as $strDate => $info) {
			$intTime = strtotime($strDate);
			$calWeekday = strtolower(date('D',$intTime));
			$blnIsAllDate = $calWeekday == $strWeekdayAll && !is_array($info);
			$blnIsADate = $calWeekday == $strWeekdayA && strtolower($info) == 'a';
			$blnIsBDate = $calWeekday == $strWeekdayB  && strtolower($info) == 'b';

			if($blnIsAllDate || $blnIsADate || $blnIsBDate || is_array($info) && $blnHolidays) {
				$arrDates[] = $strDate;
			}
		}
		return $arrDates;
	}

	/**
	* Find next date for a weekly recurring event
	*
	* @param string $strWeekdayAll | Options: mon,tue,wed,thu,fri,sat,sun,fals
	* @param string $strWeekdayA | Options: mon,tue,wed,thu,fri,sat,sun,false
	* @param string $strWeekdayB | Options: mon,tue,wed,thu,fri,sat,sun,false
	* @param bool $blnHolidays | If true, holidays weeks are also included
	*
	* @return string date in the format 'Y-m-d'
	*/
	public static function getNextRecurring($strWeekdayAll,$strWeekdayA,$strWeekdayB,$blnHolidays = false) {
		$arrDates = static::getCalRecurring($strWeekdayAll,$strWeekdayA,$strWeekdayB,$blnHolidays);

		foreach($arrDates as $i => $strDate) {
			if(time() > strtotime($arrDates[$i-1]) && time() < strtotime($arrDates[$i])) {
				return $strDate;
			}
		}
		#try next year if none found:
		$arrDates = static::getCalRecurring($strWeekdayAll,$strWeekdayA,$strWeekdayB,$blnHolidays, static::schoolYearId(static::schoolYearAtTime(strtotime('now +1 year'))));
		foreach($arrDates as $i => $strDate) {
			if(time() > strtotime($arrDates[$i-1]) && time() < strtotime($arrDates[$i])) {
				return $strDate;
			}
		}
		return false;
	}

}
?>
