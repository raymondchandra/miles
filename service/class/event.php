<?php
	class Event{
	//event
		function addEvent()
		{}
		
		function editEvent()
		{}
		
		function delEvent($link,$id)
		{
			$sql = 'DELETE FROM event WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getEventByName()
		{}
		
		function getEventById($link,$id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return json_encode($rows);
				}else{
					return '{"status":"error","message":"not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getEventByHost()
		{}
		
		function getEventByPlace()
		{}
	//end of event 
	
	//event_invite
		function inviteUser()
		{}
		
		function delInvited($link,$id)
		{
			$sql = 'DELETE FROM event_invite WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getInvitedByEvent()
		{}
		
		function getInvitedByUser()
		{}
	//end of event_invite
	
	//event_comment
		function addEventComment(){}
		
		function editEventComment(){}
		
		function deleteEventComment($link,$id)
		{
			$sql = 'DELETE FROM event_comment WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getEventCommentByEvent(){}
	//end of event_comment
	
	//private event
	
		function addPrivateEvent()
		{}
		
		function editPrivateEvent()
		{}
		
		function delPrivateEvent($link,$id)
		{
			$sql = 'DELETE FROM private_event WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getPrivateEventByName()
		{}
		
		function getPrivateEventById($link,$id)
		{
			$sql = 'SELECT * FROM private_event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return json_encode($rows);
				}else{
					return '{"status":"error","message":"not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getPrivateEventByHost()
		{}
		
		function getPrivateEventByPlaceName()
		{}
	//end of private event
	
	//private_comment
		function addPrivateComment(){}
		
		function editPrivateComment(){}
		
		function deletePrivateComment($link,$id)
		{
			$sql = 'DELETE FROM private_comment WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getPrivateCommentByPrivateEvent(){}
	//end of private_comment
	}
?>