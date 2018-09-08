<?php

/**
 * WBGym
 * 
 * Copyright (C) 2008-2016 Webteam Weinberg-Gymnasium Kleinmachnow
 * 
 * @package     WGBym
 * @author      Marvin Ritter <marvin.ritter@gmail.com>
 * @author		Johannes Cram <craj.me@gmail.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Namespace
 */
namespace WBGym;

class WBGym extends \System {

	protected static $objInstance;

	protected static $arrTeachers = array();
	protected static $arrTeachersLoaded = false;

	protected static $arrStudents = array();
	protected static $arrStudentsLoaded = false;
	
	protected static $arrParents = array();
	protected static $arrParentsLoaded = false;
	
	protected static $arrCParents = array();
	protected static $arrCParentsLoaded = false;

	protected static $arrSubjects = array();
	protected static $arrSubjectsLoaded = false;
	
	protected static $arrCourses = array();
	protected static $arrCoursesLoaded = false;
	
	protected static $arrSmsCourses = array();
	protected static $arrSmsCoursesLoaded = false;
	
	protected static $arrTdwLectures = array();
	protected static $arrTdwLecturesLoaded = false;
	

	/**
	 * Clean the global GPC arrays
	 */
	public static function initialize() {
	}

	/*
	* Return current School Year (graduation, e.g. 2017 for 2016/17)
	* @return int
	*/
	public static function schoolYear() {
		$res = \Database::getInstance()->query("SELECT * FROM tl_schoolyear");
		while($arrRow = $res->fetchAssoc()) {
			if($arrRow['start'] < time() && $arrRow['end'] > time()) {
				return date('Y',$arrRow['end']);
			}
		}
		return false;
	}

	/*
	* Fetch Database result to associative Array
	* @param sql result
	* @return array
	*/
	public static function fetchAll($sql) {
		$return = array();
		$results = \Database::getInstance()->query($sql);
		while ($result = $results->fetchAssoc()) {
			$return[$result['id']] = $result;
		}
		return $return;
	}

	/* Students */

	/*
	* Get a student's name by id
	* @param int $studentId
	* @param bln $long If false, remove 'Klasse', etc from course
	* @param bln $withClass If false, return the name without any course
	*/
	public static function student($studentId,$long = false,$withClass = true) {
		return static::studentList($long,$withClass)[$studentId];
	}

	/*
	* Get the list of student's names
	* @param bln $long If false, remove 'Klasse', etc from course
	* @param bln $withClass If false, return the names without any courses
	*/
	public static function studentList($long = false,$withClass = true) {
		if (!static::$arrStudentsLoaded) {
			static::$arrStudents = array();
			$students = \Database::getInstance()
							->prepare("SELECT * FROM tl_member WHERE student = 1 ORDER BY grade, formSelector, firstName, lastName")
							->execute();
			while ($student = $students->fetchAssoc()) {
				$student['stringShortClass'] = static::studentToString($student,false,true);
				$student['stringShort'] = static::studentToString($student,false,false);
				$student['stringLongClass'] = static::studentToString($student,true,true);
				$student['stringLong'] = static::studentToString($student,true,false);
				static::$arrStudents[$student['id']] = $student;
			}
			static::$arrStudentsLoaded = true;
		}
		if($long && $withClass) return array_column(static::$arrStudents,'stringLongClass','id');
		elseif($long && !$withClass) return array_column(static::$arrStudents,'stringLong','id');
		elseif(!$long && $withClass) return array_column(static::$arrStudents,'stringShortClass','id');
		elseif(!$long && !$withClass) return array_column(static::$arrStudents,'stringShort','id');
	}

	/*
	* Get a student's name with the Course from the student's array
	* @param $arrStudent
	* @param $long If true, add 'Klasse', etc
	* @param $withClass If false, remove course completely
	*/
	public static function studentToString($arrStudent,$long = false,$withClass = true) {
		$res = $arrStudent['firstname'] . ' ' . $arrStudent['lastname'];
		if($withClass && static::course($arrStudent['course'])) 
			$res .= ' (' . static::course($arrStudent['course'],$long) . ')';
		
		return $res;
	}

	/* Teachers */

	/*
	* Get a Teacher's name
	* @param $teacherId 
	* @param $long If false, return only the lastname of the teacher
	* @return string
	*/
	public static function teacher($teacherId,$long = true) {
		return static::teacherList($long)[$teacherId];
	}
	
	/*
	* Get a teacher's array by acronym
	* @param $acronym
	* @return array
	*/
	public static function getTeacherByAcronym($acronym) {
		if($acronym != '') {
			static::teacherList();
			$i = array_search($acronym,array_column(static::$arrTeachers,'acronym','id'));
			return static::$arrTeachers[$i];
		}
		return array();
	}

	/*
	* Load Teachers and get a array with the teacher's names
	* @param $long If false, return only the lastnames
	* @return array
	*/
	public static function teacherList($long = true) {
		if (!static::$arrTeachersLoaded) {
			$teachers = \Database::getInstance()
							->prepare("SELECT * FROM tl_member WHERE teacher = 1 ORDER BY lastname, firstName")
							->execute();
			while ($teacher = $teachers->fetchAssoc()) {
				$teacher['stringLong'] = static::teacherToString($teacher);
				$teacher['stringShort'] = static::teacherToString($teacher,false);
				static::$arrTeachers[$teacher['id']] = $teacher;
			}
			static::$arrTeachersLoaded = true;
		}
		if($long) return array_column(static::$arrTeachers,'stringLong','id');
		else return array_column(static::$arrTeachers,'stringShort','id');
	}

	/*
	* Get a teacher's name from array
	* @param $arrTeacher
	* @param $long If false, return just lastname
	* @return string
	*/
	public static function teacherToString($arrTeacher,$long = true) {
		if($long == true) {
			switch ($arrTeacher['gender']) {
				case 'female': $strName = $GLOBALS['TL_LANG']['MSC']['mrs']; break;
				case 'male': $strName = $GLOBALS['TL_LANG']['MSC']['mr']; break;
				default: $strName = '';
			}
			if (TL_MODE == 'BE') {
				$strName .= ' ' . substr($arrTeacher['firstname'], 0, 1) . '.';
			}
			$strName .= ' ';
		}
		else $strName = '';
		
		$strName .= $arrTeacher['lastname'];
		return $strName;
	}
	
	/* Parents */
	
	/*
	* Get a parent's username by id
	* @param int $parentId
	* @return string
	*/
	public static function parent($parentId) {
		return static::parentList()[$parentId];
	}
	
	/*
	* Load Parents and return a list of parent's usernames 
	* @return array
	*/
	public static function parentList() {
		if(!static::$arrParentsLoaded) {
			$parents = \Database::getInstance()->prepare("SELECT * FROM tl_member ORDER BY lastname, username")->execute();
			while ($parent = $parents->fetchAssoc()) {
				if($parent['groups'] && in_array(static::getGroupIdFor('parents'),unserialize($parent['groups'])))
					static::$arrParents[$parent['id']] = $parent['username'];
			}
			static::$arrParentsLoaded = true;
		}
		return static::$arrParents;
	}
	
	/* Commmittees Parents */
	
	/*
	* Get a committee parent's name by id
	* @param int $parentId
	* @param int $long If true, return also Tutorium
	* @return string
	*/
	public static function cParent($parentId,$long = false) {
		return static::cParentList($long)[$parentId];
	}
	
	/*
	* Load Parents and return a list of parent's usernames 
	* @param bln $long
	* @return array
	*/
	public static function cParentList($long = false) {
		if(!static::$arrCParentsLoaded) {
			$parents = \Database::getInstance()->prepare("SELECT * FROM tl_committees_parent ORDER BY lastname,firstname")->execute();
			if($parents) {
				while ($parent = $parents->fetchAssoc()) {
					$parent['stringLong'] = static::cParentToString($parent,true);
					$parent['stringShort'] = static::cParentToString($parent,false);
					static::$arrCParents[$parent['id']] = $parent;
				}
			}
			static::$arrCParentsLoaded = true;
		}
		if($long) return array_column(static::$arrCParents,'stringLong','id');
		else return array_column(static::$arrCParents,'stringShort','id');
	}
	
	/*
	* Convert a parent's array to string
	* @param array $arrParent
	* @param bln $long If true, return also Tutorium
	* @return string
	*/
	protected static function cParentToString($arrParent,$long = false) {
		switch ($arrParent['salutation']) {
			case 'female': $strName = $GLOBALS['TL_LANG']['MSC']['mrs']; break;
			case 'male': $strName = $GLOBALS['TL_LANG']['MSC']['mr']; break;
		}
		$strName .= ' ' . $arrParent['title'] . ' ' . $arrParent['firstname'] . ' ' . $arrParent['lastname'];
		
		//check which courses still exist
		$arrCourses = array();
		if($arrParent['st1']) {
			static::studentList();
			$arrStudent = static::$arrStudents[$arrParent['st1']];
			if($course = static::course($arrStudent['course'])) {
				$arrCourses[] = $course;
			}
			if($arrParent['st2']) {
				$arrStudent = static::$arrStudents[$arrParent['st2']];
				if($course = static::course($arrStudent['course'])) {
					$courses[] = $course;
				}
				if($arrParent['st3']) {
					$arrStudent = static::$arrStudents[$arrParent['st3']];
					if($course = static::course($arrStudent['course'])) {
						$arrCourses[] = $course;
					}
				}
			}
		}
		elseif($arrParent['course'] && static::course($arrParent['course'])) {
			if($course = static::course($arrParent['course'])) {
				$arrCourses[] = $course;
			}
		}

		//add courses to name string
		if($arrCourses) {
			$blnFirst = true;
			foreach ($arrCourses as $strCourse) {
				if($blnFirst) $strName .= ' (von ' . $strCourse;
				else $strName .= ', ' .$strCourse;
				$blnFirst = false;
			}
			$strName .= ')';
		}

		return $strName;
	}


	/* Subjects */

	public static function subject($subjectId,$long = true) {
		return static::subjectList($long)[$subjectId];
	}
	
	public static function subjectList($long = true) {
		if (!static::$arrSubjectsLoaded) {
			$subjects = \Database::getInstance()
							->prepare("SELECT * FROM tl_subject ORDER BY name")
							->execute();
			while ($subject = $subjects->fetchAssoc()) {
				static::$arrSubjects[$subject['id']] = $subject;
			}
			static::$arrSubjectsLoaded = true;
		}
		if($long) return array_column(static::$arrSubjects,'name','id');
		else return array_column(static::$arrSubjects,'abbreviation','id');
	}
	
	
	/* Courses  - Klassen, Tutorien und Jahrgänge */
	
	/*
	* Return course list
	* @param bln $long If true, return array with 'Klasse','Tutorium', etc
	* @return array
	*/
	public static function courseList($long = false) {
		if (!static::$arrCoursesLoaded) {
			static::$arrCourses = array();
			$courses = \Database::getInstance()
							->prepare("SELECT * FROM tl_courses ORDER BY grade, formSelector, graduation DESC, leader")
							->execute();
			while ($course = $courses->fetchAssoc()) {
				$course['stringLong'] = static::courseToString($course);
				$course['stringShort'] = static::courseToString($course,false);
				static::$arrCourses[$course['id']] = $course;
			}
			static::$arrCoursesLoaded = true;
		}
		if($long) return array_column(static::$arrCourses,'stringLong','id');
		else return array_column(static::$arrCourses,'stringShort','id');
	}
	
	/*
	* Return the course name by id
	*
	* @param int $courseId
	* @param bln $long If True, add 'Klasse', etc to string
	* @return string
	*/
	public static function course($courseId,$long = false) {
		return static::courseList($long)[$courseId];
	}

	/*
	* Build string from course array
	* @param array $arrCourse
	* @param bln $long If true, add 'Klasse', etc
	* @return string
	*/
	public static function courseToString($arrCourse,$long = false) {
		//add title of course ('Klasse', 'Tutorium', ...)
		if($long) {
			$strName = $arrCourse['title'] . ' ';
		}
		else $strName = '';
		
		//add grade&formSelector / grade&teacher
		if($arrCourse['grade'] <= 10) {
			$strName .= $arrCourse['grade'] . '/' . $arrCourse['formSelector'];
		}
		elseif(static::getGOSTGrade($arrCourse['graduation'])) {
			if($arrCourse['leader'] == 0) {
				$strName .= static::getGOSTGrade($arrCourse['graduation']);
			}
			elseif($arrCourse['leader'] != 0) {
				static::teacherList();
				$strName .= static::getGOSTGrade($arrCourse['graduation']) . ' ' . static::$arrTeachers[$arrCourse['leader']]['acronym'];
			}
		}
		else return false;
		return $strName;
	}
	
	/*
	* Get current GOST grade by graduation year
	*
	* @param int $intGraduation 
	* @return int 
	*/
	public static function getGOSTGrade($intGraduation) {
		$yearEnds = static::schoolYear();
		if($intGraduation == $yearEnds) { //Abi this school year
			return 12;
		}
		elseif($intGraduation == $yearEnds + 1) { //Abi next Year
			return 11;
		}
		return false;
	}
	
	/*
	* Return the grade by course
	*
	* @param cid Id of course, if null return course of current user
	* @return int
	*/
	public static function grade($cid = 0) {
		if($cid == 0 && FE_USER_LOGGED_IN) {
			$user = \FrontendUser::getInstance();
			$cid = $user->course;
		}
		static::courseList();
		$course = static::$arrCourses[$cid];
		
		if($course['grade'] <= 10) {
			return $course['grade'];
		}
		else return static::getGOSTGrade($course['graduation']);
		return false;
	}
	
	/*
	* Return the class form by course
	*
	* @param cid Id of course, if null return course of current user
	* @return int
	*/
	public static function formSelector($cid = 0) {
		if($cid == 0 && FE_USER_LOGGED_IN) {
			$user = \FrontendUser::getInstance();
			$cid = $user->course;
		}
		static::courseList();
		$course = static::$arrCourses[$cid];
		
		if($course['grade'] <= 10) {
			return $course['formSelector'];
		}
		else return 0;
		return false;
	}
	
	/* 
	* Return the course of a student
	* @param int id Id of the student
	* @return int Id of course
	*/
	public static function courseOf($stId) {
		static::studentList();
		return static::$arrStudents[$stId]['course'];
	}
	
	/* SmS-Courses */
	
	public static function smsCourseList() {
		if (!static::$arrSmsCoursesLoaded) {
			static::$arrSmsCourses = array();
			$courses = \Database::getInstance()
							->prepare("SELECT * FROM tl_sms_course ORDER BY id")
							->execute();
			while ($course = $courses->fetchAssoc()) {
				static::$arrSmsCourses[$course['id']] = $course;
			}
			static::$arrSmsCoursesLoaded = true;
		}
		return array_column(static::$arrSmsCourses,'name','id');
	}
	
	public static function smsCourse($courseId) {
		return static::smsCourseList()[$courseId];
	}
	
	/* TdW-Lectures */
	
	public static function tdwLectureList() {
		if (!static::$arrTdwLecturesLoaded) {
			static::$arrTdwLectures = array();
			$lectures = \Database::getInstance()
							->prepare("SELECT * FROM tl_tdwLectures ORDER BY id")
							->execute();
			while ($lecture = $lectures->fetchAssoc()) {
				static::$arrTdwLectures[$lecture['id']] = $lecture;
			}
			static::$arrTdwLecturesLoaded = true;
		}
		return array_column(static::$arrTdwLectures,'title','id');
	}
	
	public static function tdwLecture($lectureId) {
		return static::tdwLectureList()[$lectureId];
	}
	
	/*
	* Delete wish entries of user when user is deleted
	*/
	public function deleteMemberWishEntries(\DataContainer $dc) {
		if ($dc->activeRecord) {
			$db = \Database::getInstance();
			//SmS Course Choice
			$db->prepare("DELETE FROM tl_sms_course_choice WHERE id = ?")->execute($dc->activeRecord->id);
			//SmS Exchange
			$db->prepare("DELETE FROM tl_sms_exchange WHERE student = ?")->execute($dc->activeRecord->id);
			//TdW Choice
			$db->prepare("DELETE FROM tl_tdwChoices WHERE student = ?")->execute($dc->activeRecord->id);
		}
	}

	/* Workshops */

	/**
	* Get Date and Time String of a workshop array
	*
	* @param arr $arrRow Row of tl_workshop
	* @return string
	*/
	public static function workshopTimeString($arrRow) {
		$str = '';
		//\System::loadLanguageFile('default');
		$arrTimes = array('All' => 'all','A' => 'a','B' => 'b');
		foreach($arrTimes as $caps => $nocaps) {
			if($arrRow['is'.$caps] == 1) {
				if($str != '') $str .= ' / ';
				$str .= $GLOBALS['TL_LANG']['wbgym']['weekdayShort'][$arrRow['weekday_'.$nocaps]] . ', ' . $arrRow['time_'.$nocaps];
				if($nocaps != 'all') {
					$str .= ' ('.$caps.')';
				}
			}
		}
		return $str;
	}

	/* Member Groups */

	/**
	 * Find out the ID of the member group for e.g. students
	 * 
	 * @param str $type The type of group
	 * @return int The ID of the group.
	 */
	public static function getGroupIdFor($type) {
		$arrSupported = ['students','teachers','parents','moderators'];
		
		if(!in_array($type,$arrSupported)) {
			$strSupported = '';
			foreach($arrSupported as $elem) {
				$strSupported .= "'".$elem."', ";
			}
			throw new \Exception("Sorry, the member group type '".$type."' is currently not supported.\n Supported: ".$strSupported);
		}

		$res = \Database::getInstance()->prepare("SELECT id FROM tl_member_group WHERE wb_group_function = ?")->execute($type)->fetchAssoc();

		return $res['id'];
	}

	/**
	 * Clean the keys of the request arrays
	 * 
	 * @deprecated Input is now a static class
	 */
	protected function __construct() {
		static::initialize();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 * 
	 * @return \WBGym The object instance
	 */
	public static function getInstance() {
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}

}

?>