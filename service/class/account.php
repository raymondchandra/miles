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
		
		function changePassword()
		{
		}
		
		function nonActiveAccount()
		{
		}
		
		function deleteAccount($link,$id)
		{
			$sql = 'DELETE FROM account WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function changeRole()
		{
		}
		
		function checkRole()
		{
		}

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
			$sql = 'SELECT * FROM account WHERE username = "'.$email;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
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