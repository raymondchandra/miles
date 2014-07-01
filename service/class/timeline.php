<?php
	class Timeline{
	//check_in
		function checkIn()
		{
		}
		
		function getCheckInByUser()
		{
		
		}
		
		function getCheckInByPlace()
		{
		
		}
	//end of check_in
	
	//post
		function postTimeline()
		{}
		
		//function editPost(){}
		
		function deletePost($link,$id)
		{
			$sql = 'DELETE FROM post WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"post not deleted"}';
			}
		}
		
		function getTimelineByUser()
		{}
		
	//end of post
	}
?>