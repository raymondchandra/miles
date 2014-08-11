<?php
	class User{
	//fav_place
		function insertFavPlace($link,$profile_id,$place_id)
		{
			$sql = 'INSERT INTO fav_place (profile_id,place_id) VALUES ('.$profile_id.','.$place_id.')';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to insert favourite place"}';
			}
		}
		
		function deleteFavPlace($link,$profile_id,$place_id)
		{
			$sql = 'DELETE FROM fav_place WHERE profile_id ='.$follower_id.' AND place_id='.$place_id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to delete favourite place"}';
			}
		}
		
		function getFavPlaceByUser($link,$profile_id)
		{
			$sql = 'SELECT place_id FROM fav_place WHERE profile_id="'.$profile_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"fav place not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT name,photo,location FROM place WHERE id="'.$r['place_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->name;
						$r['photo'] = 'file_upload/place/'.$value->name.'-'.$value->location.'/'.$value->photo;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function checkFavPlace($link,$place_id,$profile_id)
		{
			$sql = 'SELECT * FROM fav_place WHERE profile_id="'.$profile_id.'" AND place_id="'.$place_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows >= 1){
					$value = mysqli_fetch_object($result);
					return '{"status":"yes"}';
				}else{
					return '{"status":"no"}';
				}
			}else{
				return '{"status":"error"}';
			}
		}
	//end of fav_place
	
	//most_visited
		function getMostVisited($link,$profile_id)
		{
			$sql = 'SELECT COUNT(id) as ct,place_id FROM check_in GROUP BY place_id,profile_id HAVING profile_id = "'.$profile_id.'" ORDER BY ct DESC LIMIT 3';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"fav place not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT name,photo,location FROM place WHERE id="'.$r['place_id'].'" LIMIT 1';
						$result2 = mysqli_query($link,$sql);
						$value = mysqli_fetch_object($result2);
						$r['name'] = $value->name;
						$r['photo'] = 'file_upload/place/'.$value->name.'-'.$value->location.'/'.$value->photo;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of most visited
	
	//follower
		function follow($link,$profile_id,$follower_id)
		{
			$sql = 'INSERT INTO follower (profile_id,follower_id) VALUES ('.$profile_id.','.$follower_id.')';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"failed to follow"}';
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
				return '{"status":"error","message":"failed to unfollow"}';
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
						$sql = 'SELECT last_name,first_name,photo FROM profile WHERE id="'.$r['follower_id'].'" LIMIT 1';
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
		
		function getSuggestFollow($link,$profile_id)
		{
			$sql = 'SELECT
				  me.id                               AS member_id,
				  their_friends.profile_id              AS suggested_friend_id,
				  COUNT(*)                            AS friends_in_common
				FROM
				  profile        AS me
				INNER JOIN
				  follower    AS my_friends
					ON my_friends.follower_id = me.id
				INNER JOIN
				  follower    AS their_friends
					ON their_friends.follower_id = my_friends.profile_id
				LEFT JOIN
				  follower    AS friends_with_me
					ON  friends_with_me.follower_id     = their_friends.profile_id
					AND friends_with_me.profile_id = me.id
				WHERE
				  me.id = '.$profile_id.'
				GROUP BY
				  me.id,
				  their_friends.profile_id
				ORDER BY
				  friends_in_common';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"suggest friend not found"}';
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
		
		function checkFollow($link,$profile_id,$following_id)
		{
			$sql = 'SELECT * FROM follower WHERE profile_id="'.$following_id.'" AND follower_id="'.$profile_id.'"';
			
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
				return "success";
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
			$sql = 'SELECT * FROM preference WHERE profile_id="'.$profile_id.'"';
			
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
		
		function getUserByName($link,$name)
		{
			$sql = 'SELECT * FROM profile WHERE last_name LIKE "%'.$name.'%" OR first_name LIKE "%'.$name.'%"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"user not found"}';
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
		
		function updateNumInvited($link,$id)
		{
			$sql = 'SELECT COUNT(id) as number FROM event_invite GROUP BY profile_id HAVING profile_id="'.$id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return false;
				}else{
					$r = mysqli_fetch_assoc($result);
					
					$sql = 'UPDATE profile SET num_invited="'.$r['number'].'" WHERE id='.$id;
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
	//end of profile
	
	//setting 
		function addSetting(){}
		
		function updateSettingByUserType(){}
		
		function getSettingByUser(){}
	//end of setting
	}
?>