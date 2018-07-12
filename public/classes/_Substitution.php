<?php

class _Substitution
{
	public $course_requirement; // The original degree requirement.
	public $course_list_substitutions; // The course(s) which are
	//filling in for that requirement.
	public $bool_has_been_applied;
	public $bool_group_addition;
	public $faculty_id;  // The faculty member that made the substitution.

	public $bool_outdated;  // set to true if this is an outdated sub (for an old major or the like)
	public $remarks; // like a comment for the substitution.

	public $outdated_note; // will contain information about WHY this was outdated.
	
	function __construct()
	{

		$this->course_requirement = new Course();
		$this->course_list_substitutions = new CourseList();
		$this->bool_group_addition = false;
		$this->bool_outdated = false;
	}

	function to_string()
	{
		$rtn = "";
		if ($this->bool_group_addition)
		{
			$ga = "group addition ";
		}
		$rtn .= "Substitution: $ga " . $this->course_requirement->to_string() . " fulfilled by ";
		$rtn .= $this->course_list_substitutions->to_string() . "\n";
		$tcrgroup = new Group($this->course_requirement->assigned_to_group_id);
		$rtn .= "CR group: " . $tcrgroup->title . " CR semester num: " . $this->course_requirement->assigned_to_semester_num . "\n";
		$tsubgroup = new Group($this->course_list_substitutions->get_first()->assigned_to_group_id);
		$rtn .= "Sub group: " . $tsubgroup->title . " Sub semester num: " . $this->course_list_substitutions->get_first()->assigned_to_semester_num . "\n";


		return $rtn;
	}
  
} // end class Substitution
