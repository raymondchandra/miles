<?php
	class User{
	//fav_place
		function insertFavPlace(){}
		
		function deleteFavPlace($link,$id)
		{
			$sql = 'DELETE FROM fav_place WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getFavPlaceByUser(){}
		
		function getFavPlaceByPlace(){}
	//end of fav_place
	
	//follower
		function follow($link,$profile_id,$follower_id)
		{
			$sql = 'INSERT INTO follower (profile_id,follower_id) VALUES ('.$profile_id.','.$follower_id.')';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function unfollow($link,$profile_id,$follower_id)
		{
			$sql = 'DELETE FROM follower WHERE follower_id ='.$follower_id.' AND profile_id='.$profile_id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getFollower($link,$profile_id)
		{
			$sql = 'SELECT follower_id FROM follower WHERE profile_id="'.$profile_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"follower not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name FROM profile WHERE id="'.$r['follower_id'].'" LIMIT 1';
						$result = mysql_query($sql);
						$value = mysql_fetch_object($result);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getFollowing($link,$follower_id)
		{
			$sql = 'SELECT profile_id FROM follower WHERE follower_id="'.$follower_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"following not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT last_name,first_name FROM profile WHERE id="'.$r['profile_id'].'" LIMIT 1';
						$result = mysql_query($sql);
						$value = mysql_fetch_object($result);
						$r['name'] = $value->first_name.' '.$value->last_name;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function checkFollow($link,$profile_id,$following_id)
		{
			$sql = 'SELECT * FROM follower WHERE profile_id="'.$profile_id.'" AND follower_id="'.$follower_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$value = mysqli_fetch_object($result);
					return '{"status":"yes"}';
				}else{
					return '{"status":"no"}';
				}
			}else{
				return '{"status":"error"}';
			}
		}
	//end of follower
	
	//preference
		function addPreference($link,$profile_id,$value)
		{
			$value = mysqli_escape_string($link,$value);
			$profile_id = mysqli_escape_string($link,$profile_id);
			$sql = 'INSERT INTO preference (profile_id,value) VALUES ('.$profile_id.',"'.$value.'")';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"preference not inserted"}';
			}
		}
		
		function deletePreference($link,$profile_id,$value)
		{
			$value = mysqli_escape_string($link,$value);
			$profile_id = mysqli_escape_string($link,$profile_id);
			
			$sql = 'DELETE FROM preference WHERE profile_id ='.$id.' AND value='.$value;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"preference not deleted"}';
			}
		}
		
		function getPreferenceByUser($link,$profile_id)
		{
			$sql = 'SELECT * FROM preference WHERE place_id="'.$profile_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"preference not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of preference
	
	//profile
		function addUser($link,$user)
		{
			$account_id = mysqli_escape_string($link,$user['account_id']);
			$fname = mysqli_escape_string($link,$user['first_name']);
			$lname = mysqli_escape_string($link,$user['last_name']);
			$birthday = mysqli_escape_string($link,$user['birthday']);
			$sex = mysqli_escape_string($link,$user['sex']);
			$photo = mysqli_escape_string($link,$user['photo']);
			$phone = mysqli_escape_string($link,$user['phone']);
			$sql = 'INSERT INTO profile (account_id,first_name,last_name,birthday,sex,photo,phone_number,num_follower,num_review,num_invited) VALUES ('.$account_id.',"'.$fname.'","'.$lname.'",'.$birthday.','.$sex.',"'.$photo.'","'.$phone.'",0,0,0)';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"user not inserted"}';
			}
		}
		
		function deleteUser($link,$id)
		{
			$sql = 'DELETE FROM profile WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"user not deleted"}';
			}
		}
		
		function updateUserName($link,$id,$name)
		{
			$sql = 'UPDATE profile SET name="'.$name.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"user not updated"}';
			}
		}
		
		function updateUserEmail($link,$id,$email)
		{
			$sql = 'UPDATE profile SET email="'.$email.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"user not updated"}';
			}
		}
		
		function updateUserPhone($link,$id,$phone)
		{
			$sql = 'UPDATE profile SET phone_number="'.$phone.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"user not updated"}';
			}
		}
		
		function getUserById($link,$id)
		{
			$sql = 'SELECT * FROM profile WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"user not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getUserByName(){}
		
		function getUserByEmail(){}
		
		function getUserByAccount(){}
		
		function updateNumFollower($link,$id)
		{
			$sql = 'SELECT COUNT(follower_id) as number FROM follower GROUP BY profile_id HAVING profile_id="'.$id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return false;
				}else{
					$r = mysqli_fetch_assoc($result);
					
					$sql = 'UPDATE profile SET num_follower="'.$r['number'].'" WHERE id='.$id;
					if($result = mysqli_query($link, $sql)){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
		
		function updateNumReview($link,$id)
		{
			$sql = 'SELECT COUNT(id) as number FROM review GROUP BY profile_id HAVING profile_id="'.$id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return false;
				}else{
					$r = mysqli_fetch_assoc($result);
					
					$sql = 'UPDATE profile SET num_review="'.$r['number'].'" WHERE id='.$id;
					if($result = mysqli_query($link, $sql)){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
		
		function updateNumInvited(){}
	//end of profile
	
	//setting 
		function addSetting(){}
		
		function updateSettingByUserType(){}
		
		function getSettingByUser(){}
	//end of setting
	}
?>