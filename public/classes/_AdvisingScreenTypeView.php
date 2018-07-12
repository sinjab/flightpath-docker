<?php


/**
 * This class is the View by Type view for FlightPath.  As such, it
 * inherits most of it's classes from __advising_screen.
 *
 *	The biggest difference with View by Type from the default
 *	View by Year is that we don't care about the original semesters
 *	that were spelled out in the degree plan.  We need to re-organize them
 *	into new semesters for Major, Core, Supporting, and Electives.  So,
 *	most of the methods here will be about doing that.
 *
 */
class _AdvisingScreenTypeView extends _AdvisingScreen
{


  /**
   * In __advising_screen, this method simply displays the degree plan's
   * semesters to the screen.  But here, we need to go through the 4
   * type categories: Core, Major, Supporting, and Electives,
   * and only display courses and groups from each semester fitting
   * that type.
   *
   */
	function build_semester_list()
	{

		$list_semesters = $this->degree_plan->list_semesters;
		// Go through each semester and add it to the screen...
		$list_semesters->reset_counter();

		
		// We want to go through our requirement types, and create a box for each one, if available.
		$types = fp_get_requirement_types();
		foreach ($types as $code => $desc) {
		  $temp = $this->display_semester_list($list_semesters, $code, $desc, TRUE);
		  if ($temp) {
		    $this->add_to_screen($temp);
		  }
		}
		
		//$this->add_to_screen($this->display_semester_list($list_semesters, "c", t("Core Requirements"), true));
		//$this->add_to_screen($this->display_semester_list($list_semesters, "m", t("Major Requirements"), true));
		//$this->add_to_screen($this->display_semester_list($list_semesters, "s", t("Supporting Requirements"), true));
		//$this->add_to_screen($this->display_semester_list($list_semesters, "e", t("Electives"), true));

		
		$temp_d_s = new Semester(-55); // developmental requirements.
		if ($dev_sem = $list_semesters->find_match($temp_d_s))
		{
			$this->add_to_screen($this->display_semester($dev_sem));
		}
						
		
	}


	/**
	 * Does the testType match the reqType?  This function is used
	 * to make sure that courses or groups with a certain requirement_type
	 * are placed in the correct semester blocks on screen.
	 *
	 * @param string $test_type
	 * @param string $req_type
	 * @return bool
	 */
	function match_requirement_type($test_type, $req_type)
	{
		// Does the testType match the reqType?
		
		if ($test_type == $req_type)
		{
			return true;
		}
		
		// Does it match if there's a u in front?
		if ($test_type == ("u" . $req_type))
		{  // university captone type.
			return true;
		}

  	if ($req_type == "e")
		{
			// type "elective."  We will make sure the test_type isn't in
			// one of our defined types already.
			$types = fp_get_requirement_types();
			if (!isset($types[$test_type])) {
			  // Yes-- the user is using a code NOT defined, so let's just call it
			  // an "elective" type.
			  return TRUE;
			}
			
			
		}
		
		return false;
		
	}
	
	/**
	 * Display contents of a semester list as a single semester,
	 * only displaying courses matching the requirement_type.
	 * If the requirement_type is "e", then we will also look for anything
	 * not containing a defined requirement_type.
	 *
	 * @param SemesterList $list_semesters
	 * @param string $requirement_type
	 * @param string $title
	 * @param bool $bool_display_hour_count
	 * @return string
	 */
	function display_semester_list($list_semesters, $requirement_type, $title, $bool_display_hour_count = false)
	{

		// Display the contents of a semester object
		// on the screen (in HTML)
		$pC = "";
		$pC .= $this->draw_semester_box_top($title);

		$is_empty = TRUE;
		
		$count_hours_completed = 0;
		$list_semesters->reset_counter();
		while($list_semesters->has_more())
		{
			$semester = $list_semesters->get_next();
			if ($semester->semester_num == -88)
			{ // These are the "added by advisor" courses.  Skip them.
				continue;
			}
			
			// First, display the list of bare courses.
			$semester->list_courses->sort_alphabetical_order();
			$semester->list_courses->reset_counter();
			$sem_is_empty = true;
			$sem_rnd = rand(0,9999);
			$pC .= "<tr><td colspan='4' class='tenpt'>
					<b><!--SEMTITLE$sem_rnd--></b></td></tr>";
			while($semester->list_courses->has_more())
			{
				$course = $semester->list_courses->get_next();
				// Make sure the requirement type matches!
				if (!$this->match_requirement_type($course->requirement_type, $requirement_type))
				{
					continue;
				}
		
				$is_empty = FALSE;
				
				// Is this course being fulfilled by anything?
				//if (is_object($course->courseFulfilledBy))
				if (!($course->course_list_fulfilled_by->is_empty))
				{ // this requirement is being fulfilled by something the student took...
					//$pC .= $this->draw_course_row($course->courseFulfilledBy);
					$pC .= $this->draw_course_row($course->course_list_fulfilled_by->get_first());
					//$count_hours_completed += $course->courseFulfilledBy->hours_awarded;
					$course->course_list_fulfilled_by->get_first()->bool_has_been_displayed = true;
					
					if ($course->course_list_fulfilled_by->get_first()->display_status == "completed")
					{ // We only want to count completed hours, no midterm or enrolled courses.
						//$count_hours_completed += $course->course_list_fulfilled_by->get_first()->hours_awarded;
            $h = $course->course_list_fulfilled_by->get_first()->hours_awarded;
					  if ($course->course_list_fulfilled_by->get_first()->bool_ghost_hour == TRUE) {
					   $h = 0;
					  }
					  $count_hours_completed += $h;						
					}
				} else {
					// This requirement is not being fulfilled...
					$pC .= $this->draw_course_row($course);
				}				
				$sem_is_empty = false;
			}

			// Now, draw all the groups.
			$semester->list_groups->sort_alphabetical_order();
			$semester->list_groups->reset_counter();
			while($semester->list_groups->has_more())
			{
				//debug_c_t("dddd");
				$group = $semester->list_groups->get_next();
				if (!$this->match_requirement_type($group->requirement_type, $requirement_type))
				{
					continue;
				}

				$pC .= "<tr><td colspan='8'>";
				$pC .= $this->display_group($group);
				$count_hours_completed += $group->hours_fulfilled_for_credit;
				$pC .= "</td></tr>";
				$sem_is_empty = false;
				$is_empty = FALSE;
			}

			if ($sem_is_empty == false)
			{
				// There WAS something in this semester, put in the title.
				
				//debugCT("replacing $sem_rnd with $semester->title");
				$pC = str_replace("<!--SEMTITLE$sem_rnd-->",$semester->title,$pC);
			}
			
		}
		
		
		if ($is_empty == TRUE) {
		  // There was nothing in this box.  Do not return anything.
		  return FALSE;
		}
		
		
		// Add hour count to the bottom...
		if ($bool_display_hour_count == true && $count_hours_completed > 0)
		{
			$pC .= "<tr><td colspan='8'>
				<div class='tenpt' style='text-align:right; margin-top: 10px;'>
				Completed hours: $count_hours_completed
				</div>
				";
			$pC .= "</td></tr>";
		}

		$pC .= $this->draw_semester_box_bottom();

		return $pC;

	}

}

