<?php
	class Timeline{
	//check_in
		function checkIn($link,$profile_id,$place_id)
		{
			$sql = 'INSERT INTO check_in (profile_id,place_id,date) VALUES ('.$profile_id.', '.$place_id.',now())';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"check in failed"}';
			}
		}
		
		function getCheckInByUser($link,$profile_id)
		{
			$sql = 'SELECT * FROM check_in WHERE profile_id ='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"check in data not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getLastCheckInByUser($link,$profile_id)
		{
			$sql = 'SELECT date FROM check_in WHERE profile_id ='.$profile_id.' ORDER BY date DESC LIMIT 1';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows['date'];
				}else{
					return "never";
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getCheckInByPlace($link,$place_id)
		{
			$sql = 'SELECT * FROM check_in WHERE place_id ='.$place_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"check in data not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of check_in
	
	//post
		function postTimeline($link,$profile_id,$type,$description,$place_id)
		{
			if($type=="check_in")
			{
				$text = "checked in @ ".$description;
			}
			else if($type=="review")
			{
				$text = "write a review @ ".$description;
			}
			else if($type=="likereview")
			{
				$text = "like a review @ ".$description;
			}
			
			
			$sql = 'INSERT INTO post (profile_id,type,text,place_id,time) VALUES ('.$profile_id.', "'.$type.'","'.$text.'",'.$place_id.',now())';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"post failed"}';
			}
		}
		
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
		
		function getTimelineByUser($link,$profile_id)
		{
			$sql = 'SELECT * FROM post WHERE profile_id ='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"timeline data not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getTimelineByUserNFollowing($link,$profile_id)
		{
			$sql = 'SELECT * FROM post WHERE profile_id = ANY(SELECT profile_id FROM follower WHERE follower_id='.$profile_id.')';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"timeline data not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of post
	}
?>