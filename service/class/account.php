<?php
	class Account{
		// active 
		// 0 : nonactive
		// 1 : active
		// 
		function login($link,$email,$password)
		{
			$sql = 'SELECT * FROM account WHERE username = "'.$email.'" AND password="'.$password.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$value = mysqli_fetch_object($result);
					$sql = 'SELECT id FROM profile WHERE account_id="'.$value->id.'" LIMIT 1';
					$result = mysqli_query($link,$sql);
					$value = mysqli_fetch_object($result);
					return '{"status":"accept","id":"'.$value->id.'"}';
				}else{
					return '{"status":"reject"}';
				}
			}else{
				return '{"status":"error"}';
			}
		}
		
		function addAccount($link,$account)
		{
			$username = mysqli_escape_string($link,$account['username']);
			$password = mysqli_escape_string($link,$account['password']);
			$role = mysqli_escape_string($link,$account['role']);
			$active = $account['active'];
			$sql = 'INSERT INTO account (username,password,role,active) VALUES ("'.$username.'", "'.$password.'", "'.$role.'", '.$active.')';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"account not inserted"}';
			}
		}
		
		//newcode
		function changeActive($link,$id,$active)
		{
			//$changeid = mysqli_escape_string($link,$id);
			//$changeactive = mysqli_escape_string($link,$active);
			
			//$sql = 'UPDATE account SET active='.$changeactive.' WHERE id='.$changeid;			
			$sql = 'UPDATE account SET active='.$active.' WHERE id='.$id;
			
			if(mysqli_query($link,$sql)){
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"active not updated"}';
			}
		}
		//endnewcode
		
		function changePassword($link,$profile_id,$oldpassword,$newpassword)
		{
			$sql = 'SELECT account_id FROM profile WHERE id ='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$account_id = $rows['account_id'];
					$sql = 'SELECT * FROM account WHERE id ='.$account_id.' LIMIT 1';
					$result2 = mysqli_query($link, $sql);
					$rows2 = mysqli_fetch_assoc($result2);
					if($oldpassword == $rows2['password']){
					
						$sql = 'UPDATE account SET password='.$newpassword.' WHERE id='.$account_id;
						if(mysqli_query($link,$sql)){
							//success
							return true;
						}else{
							//error
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
			
			
		}
		function getRandomString($length = 8) {
			$validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?1234567890";
			$validCharNumber = strlen($validCharacters);
		 
			$result = "";
		 
			for ($i = 0; $i < $length; $i++) {
				$index = mt_rand(0, $validCharNumber - 1);
				$result .= $validCharacters[$index];
			}
		 
			return $result;
		}
		
		function forgotPassword($link,$profile_id)
		{
			$sql = 'SELECT account_id FROM profile WHERE id ='.$profile_id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$account_id = $rows['account_id'];
					
					$newPassword = getRandomString();
					$sql = 'UPDATE account SET password='.md5($newPassword).' WHERE id='.$account_id;
					
					if(mysqli_query($link,$sql)){
						//success
						//kirim email
						/*
						$subject = "Miles's password";
						$message = "You've just reset your Miles password, the following are your new passworrd: ".$newPassword;
						$email = $rows['username'];
						$message = wordwrap($message, 70);
						$from = 'noreply@milesyourday.com';
						mail($email,$subject,$message,"From: $from\n");
						*/
						return true;
					}else{
						//error
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function deleteAccount($link,$id)
		{
			$sql = 'DELETE FROM account WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"account not deleted"}';
			}
		}
		
		function changeRole()
		{
		}
		
		function checkRole()
		{
		}

		//newcode -------------------------------------------------------------------------
		//return all account yang user
		function getAllAccount($link)
		{	
			$sql = 'SELECT * FROM account WHERE role = "user"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"no account"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)){
						$rows[] = $r;						
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		//endnewcode -------------------------------------------------------------------------
		
		function getAccountById()
		{
			$sql = 'SELECT * FROM account WHERE id ='.$id;
			
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
		
		function getAccountByRole($link,$role){
			$sql = 'SELECT * FROM place WHERE role ='.$role;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$rows[] = $r;
					}
					return json_encode($rows);
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function checkExist($link,$email)
		{
			$sql = 'SELECT * FROM account WHERE username = "'.$email.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows >= 1){
					$value = mysqli_fetch_object($result);
					return '{"status":"exist"}';
				}else{
					return '{"status":"not exist"}';
				}
			}else{
				return '{"status":"error"}';
			}
		}
		
		
		/*(function getLastId($link)
		{
			$sql = 'SELECT id FROM account ORDER BY id DESC LIMIT 1';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return 0;
				}else{
					$ret = mysqli_fetch_assoc($result);
					return $ret['id'];
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}*/
	}
?>