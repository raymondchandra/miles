<?php
	class Event{
	//event
		function addPublicEvent($link,$event,$place_id)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$cp = mysqli_escape_string($link,$event['cp']);
			$website = mysqli_escape_string($link,$event['website']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$dresscode = mysqli_escape_string($link,$event['dresscode']);
			$price = mysqli_escape_string($link,$event['price']);
			$type = mysqli_escape_string($link,$event['type']);
			
			$membership = 1;
			
			$sql = "INSERT INTO event (name,cp,website,place_id,start_time,end_time,description,membership,photo,dresscode,price,type) VALUES ('".$name."', '".$cp."','".$website."','".$place_id."','".$start_time."','".$end_time."', '".$description."', ".$membership.", '".$photo."', '".$dresscode."','".$price."','".$type."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not inserted";
				return $ret;
			}
		}
		
		function editPublicEvent($link,$event,$id,$place_id)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$cp = mysqli_escape_string($link,$event['cp']);
			$website = mysqli_escape_string($link,$event['website']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			$dresscode = mysqli_escape_string($link,$event['dresscode']);
			$price = mysqli_escape_string($link,$event['price']);
			$type = mysqli_escape_string($link,$event['type']);
			
			$membership = 1;
			$sql = 'UPDATE event SET name="'.$name.'",cp="'.$cp.'",website="'.$website.'",place_id="'.$place_id.'",start_time="'.$start_time.'",end_time="'.$end_time.'",description="'.$description.'",membership="'.$membership.'",photo="'.$photo.'",dresscode="'.$dresscode.'",price="'.$price.'",type="'.$type.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not updated";
				return $ret;
			}
		}
		
		function addPrivateEvent($link,$event,$place_id,$host)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			
			$membership = 0;
			
			$sql = "INSERT INTO event (name,host,website,place_id,start_time,end_time,description,membership,photo) VALUES ('".$name."', '".$host."','".$place_id."','".$start_time."','".$end_time."', '".$description."', ".$membership.", '".$photo."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not inserted";
				return $ret;
			}
		}
		
		function editPrivateEvent($link,$event,$id)
		{
			$name = mysqli_escape_string($link,$event['name']);
			$start_time = $event['start_time']; //YYYY-MM-DD HH:MM:SS
			$end_time = $event['end_time']; //YYYY-MM-DD HH:MM:SS
			$description = mysqli_escape_string($link,$event['description']);
			$photo = mysqli_escape_string($link,$event['photo']);
			
			$membership = 0;
			
			$membership = 1;
			$sql = 'UPDATE event SET name="'.$name.'",start_time="'.$start_time.'",end_time="'.$end_time.'",description="'.$description.'",membership="'.$membership.'",photo="'.$photo.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "event not updated";
				return $ret;
			}
		}
		
		function delEvent($link,$id)
		{
			$check = true;
			//delete comment
			$sql = 'DELETE FROM event_comment WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
				
			//delete gallery
			$sql = 'DELETE FROM event_gallery WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			//delete invite
			$sql = 'DELETE FROM event_invite WHERE event_id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			$sql = 'DELETE FROM event WHERE id ='.$id;
			if (!mysqli_query($link, $sql)) $check = false;
			
			if ($check)) {
				$ret['status'] = "success";
				return $ret;
			}else{
				$ret['status'] = "error";
				$ret['message'] = "failed to delete event";
				return $ret;
			}
		}
		
		function getEventById($link,$id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$rows['photo'] = 'file_upload/event/'.$rows['id'].'/'.$rows['photo'];
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}else{
					//error
					$ret['status'] = "error";
					$ret['message'] = "event not found";
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function getEventByMembership($link,$membership)
		{
			$sql = 'SELECT * FROM event WHERE membership ='.$membership;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "event not found";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$r['photo'] = 'file_upload/event/'.$r['id'].'/'.$r['photo'];
			
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function checkOngoingEvent($link,$profile_id) //private cm boleh 5
		{
			$sql = 'SELECT * FROM event WHERE host ="'.$profile_id.'" AND start_time <= now() AND end_time >= now()';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows < 5){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function isPublic($link,$id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					if($rows['membership'] == 1)
						return 1;
					else
						return 0;
				}else{
					//error
					return -1;
				}
			}else{
				return -1;
			}
		}
		
		function checkHost($link,$event_id,$profile_id)
		{
			$sql = 'SELECT * FROM event WHERE id ='.$event_id.' AND profile_id='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows >= 1){
					return "yes";
				}else{
					return "no";
				}
			}else{
				return "error";
			}
		}
		
		function updatePhoto($link,$id,$newphoto)
		{
			$sql = 'UPDATE event SET photo="'.$newphoto.'" WHERE id='.$id;
			
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
				return $ret;
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not updated";
				return $ret;
			}
		}
	//end of event 
	
	//event_gallery
		function addPhoto($link,$event_id,$photo)
		{
			$photo = mysqli_escape_string($link,$photo);
			
			$sql = "INSERT INTO event_gallery (event_id,photo) VALUES ('".$event_id."','".$photo."')";
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not inserted";
			}
			return $ret;
		}
		
		function deletePhoto($link,$id)
		{
			$sql = 'DELETE FROM event_gallery WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "photo not deleted";
			}
			return $ret;
		}
		
		function getGalleryFromId($link,$id)
		{
			$sql = 'SELECT * FROM event_gallery WHERE id ='.$id.' LIMIT 1';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$ret['status'] = "success";
					$ret['value'] = $rows;
				}else{
					$ret['status'] = "error";
					$ret['message'] = "photo not found";
				}
			}else{
				$ret['status'] = "error";
				$ret['message'] = "sql error";
			}
			return $ret;
		}
		
		function getPhotoByEvent($link,$event_id)
		{
			$sql = 'SELECT * FROM event_gallery WHERE event_id="'.$event_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					$ret['status'] = "error";
					$ret['message'] = "photo not found";
				}else{
					$sql2 = 'SELECT id FROM event WHERE id="'.$event_id.'" LIMIT 1';
					if($result2 = mysqli_query($link,$sql2))
					{
						$value = mysqli_fetch_assoc($result2);
						$rows = array();
						while($r = mysqli_fetch_assoc($result)) {
							$r['photo'] = 'file_upload/event/'.$value['id'].'/'.$r['photo'];
							$rows[] = $r;
						}
						$ret['status'] = "success";
						$ret['value'] = $rows;
					}else{
						$ret['status'] = "error";
						$ret['message'] = "sql error";
					}
					
				}
			}else{
				$ret['status'] = "error";
				$ret['message'] = "sql error";
			}
			return $ret;
		}
	//end of event_gallery
	
	//event_invite
		function inviteUser($link,$event_id,$profile_id)
		{
			$sql = 'INSERT INTO event_invite (event_id,profile_id) VALUES ('.$event_id.','.$profile_id.')';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to invite"}';
			}
		}
		
		function delInvited($link,$event_id,$profile_id)
		{
			$sql = 'DELETE FROM event_invite WHERE event_id ='.$event_id.' AND profile_id='.$profile_id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to unfollow"}';
			}
		}
		
		function getInvitedByEvent($link,$event_id)
		{
			$sql = 'SELECT * FROM event WHERE event_id ='.$event_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 invited";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$r['photo'] = $value->photo;
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
		
		function getInvitedByUser($link,$profile_id)
		{
			$sql = 'SELECT * FROM event WHERE profile_id ='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 invited";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT * FROM event WHERE id="'.$r['event_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->name;
						$r['photo'] = 'file_upload/event/'.$value->id.'/'.$value->photo;
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
	//end of event_invite
	
	//event_going
		function goingEvent($link,$event_id,$profile_id,$rsvp)
		{
			$sql = 'UPDATE event_invite SET rsvp="'.$rsvp.'" WHERE event_id="'.$event_id.'" AND profile_id="'.$profile_id.'"';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to invite"}';
			}
		}
			$sql = 'SELECT * FROM event_going WHERE event_id ='.$event_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					//error
					$ret['status'] = "error";
					$ret['message'] = "0 going";
					return $ret;
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$r['photo'] = $value->photo;
						$rows[] = $r;
					}
					$ret['status'] = "success";
					$ret['value'] = $rows;
					return $ret;
				}
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "sql error";
				return $ret;
			}
		}
	//end of event_going
	
	
	//event_comment
		function addEventComment($link,$event_id,$profile_id,$text)
		{
			$text = mysqli_escape_string($link,$text);
			$sql = 'INSERT INTO event_comment (event_id,profile_id,text,date) VALUES ('.$event_id.','.$profile_id.',"'.$text.'",now())';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"check in failed"}';
			}
		}
		
		function editEventComment($link,$id,$text)
		{
			$text = mysqli_escape_string($link,$text);
			$sql = 'UPDATE review SET text="'.$text.'",date=now() WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to edit"}';
			}
		}
		
		function deleteEventComment($link,$id)
		{
			$sql = 'DELETE FROM event_comment WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				$ret['status'] = "success";
			}else{
				//error
				$ret['status'] = "error";
				$ret['message'] = "comment not deleted";
			}
			return $ret;
		}
		
		function getEventCommentByEvent($link,$event_id)
		{
			$sql = 'SELECT * FROM event_comment WHERE event_id="'.$event_id.'" ORDER BY date';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"0 comment"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$r['photo'] = $value->photo;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of event_comment
	}
?>