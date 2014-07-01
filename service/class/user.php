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
		function addFollower(){}
		
		function deleteFollower($link,$id)
		{
			$sql = 'DELETE FROM follower WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getFollowerByUser(){}
	//end of fav_place
	
	//preference
		function addPreference(){}
		
		function deletePreference($link,$id)
		{
			$sql = 'DELETE FROM preference WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getPreferenceByUser(){}
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
			$email = mysqli_escape_string($link,$user['email']);
			$phone = mysqli_escape_string($link,$user['phone']);
			$sql = 'INSERT INTO profile (account_id,first_name,last_name,birthday,sex,photo,email,phone_number,num_follower,num_review,num_invited) VALUES ('.$account_id.',"'.$fname.'","'.$lname.'",'.$birthday.','.$sex.',"'.$photo.'","'.$email.'","'.$phone.'",0,0,0)';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not inserted"}';
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
				return '{"status":"error","message":"place not deleted"}';
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
				return '{"status":"error","message":"place not updated"}';
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
				return '{"status":"error","message":"place not updated"}';
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
				return '{"status":"error","message":"place not updated"}';
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
		
		function updateNumFollower(){}
		
		function updateNumReview(){}
		
		function updateNumInvited(){}
	//end of profile
	
	//setting 
		function addSetting(){}
		
		function updateSettingByUserType(){}
		
		function getSettingByUser(){}
	//end of setting
	}
?>