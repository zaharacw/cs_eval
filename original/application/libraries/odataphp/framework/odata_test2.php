<?php
	require_once "CourseEnrollmentEntities.php";
	echo "Hello devteam";

	$courseFields = 'TERM,CRN,SUBJ,CRSENUMB,SECTION';
	$courseEntity = 'COURSES';
	DisplayEntityInfo($courseEntity, $courseFields);
	echo "Displayed Courses" . "</br>";

	$memberFields = 'TERM,CRN,ID,ROLE';
	$memberEntity = 'MEMBERS';
	DisplayEntityInfo($memberEntity, $memberFields);
	echo " Displayed Members" . "</br>";

	echo "All Done" . "</br>";

	function DisplayEntityInfo($entity, $fields)
	{
		try
		{
			$proxy = new CourseEnrollmentEntities("https://webapps.eastern.ewu.edu/datainterfaces/CourseEnrollment.svc");
			$query = $proxy->$entity()->Select($fields)->IncludeTotalCount();
			$entityResponse = $query->Execute();
			$fieldsArray = explode(",", $fields);

			 echo "<br/>";
			 echo "Number of $entity:" . $entityResponse->TotalCount() . "<br/>";
			 foreach($fieldsArray as $field){ echo $field . "<br/>";}
			 echo "----------" . "<br/>";
			 $nextEntityToken = null;

			 do
			 {
				if($nextEntityToken != null)
				{
					$entityResponse = $proxy->Execute($nextEntityToken);
				}

				foreach($entityResponse->Result as $entityObject)
				{
					foreach($fieldsArray as $field){ echo $entityObject->$field . " ";}
					echo "</br>";

				}

			 }while(($nextEntityToken = $entityResponse->GetContinuation()) != null);

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
	}
?>
