<?php
	require_once "CourseEnrollmentEntities.php";
	$courseFields = 'TERM,CRN,SUBJ,CRSENUMB,SECTION,START_DATE,END_DATE';
	$courseEntity = 'COURSES';
	DisplayEntityInfo($courseEntity, $courseFields);
	function DisplayEntityInfo($entity, $fields)
	{
		try
		{
			$proxy = new CourseEnrollmentEntities("https://webapps.eastern.ewu.edu/datainterfaces/CourseEnrollment.svc");
			$query = $proxy->$entity()->Expand('MEMBERS')->IncludeTotalCount();
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
					$members = $entityObject->MEMBERS;
					$instructor = null;
					foreach($members as $member){ if($member->ROLE == "faculty"){$instructor = $member->ID;break;}}
					echo "$instructor";
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
