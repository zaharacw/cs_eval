<?php
	require_once "CourseEnrollmentEntities.php";
	try
	{
		$proxy = new CourseEnrollmentEntities("https://webapps.eastern.ewu.edu/datainterfaces/CourseEnrollment.svc");
		$query = $proxy->COURSES()->Select('SUBJ,TERM,CRN,CRSENUMB,SECTION')->IncludeTotalCount();
		//$query = $proxy->COURSES()->Select('SUBJ')->IncludeTotalCount();
		$courseResponse = $query->Execute();
		 echo "<br/>";
		 echo "Number of COURSES:" . $courseResponse->TotalCount() . "<br/>";
		 echo "----------" . "<br/>";
		 $nextCourseToken = null;
		 do
		 {
			if($nextCourseToken != null)
			{
				$courseResponse = $proxy->Execute($nextCourseToken);
			}

			foreach($courseResponse->Result as $course)
			{
				 echo $course->TERM . " ".  $course->CRN . " " . $course->SUBJ . " " . $course->CRSENUMB . " " . $course->SECTION . "<br/>";
                        }

		 }while(($nextCourseToken = $courseResponse->GetContinuation()) != null);

	}
	catch(DataServiceRequestException $ex)
	{
			echo 'Error: while running the query ' . $ex->Response->getQuery();
			echo "<br/>";
			echo $ex->Response->getError();
	}
	catch (ODataServiceException $e)
	{
		echo "Error:" . $e->getError() . "<br>" . "Detailed Error:" . $e->getDetailedError();
	}
?>
