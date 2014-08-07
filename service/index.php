<?php
//setting slim framework
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

include("connect.php");
include("class/account.php");
include("class/event.php");
include("class/place.php");
include("class/timeline.php");
include("class/user.php");

//account
	$app->get('/accLogin/:email/:password', function($email,$password) use ($link){
		$account = new Account();
		echo $account->login($link,$email,$password);
	});
	
	
	
	$app->get('/accExist/:email', function($email) use ($link){
		$account = new Account();
		echo $account->checkExist($link,$email);
	});
	
	$app->post('/accAdmin',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$newAccount['username'] = mysqli_escape_string($link,$input['email']);
		$newAccount['password'] = mysqli_escape_string($link,$input['password']);
		$newAccount['role'] = 'admin';
		$newAccount['active'] = 1;
		$account = new Account();
		echo $account->addAccount($link,$newAccount);
	});
	
	$app->delete('/account/:id',function($id) use ($link) {
        $account = new Account();
		echo $account->deleteAccount($link,$id);
		
	//newcode
	$app->put('account',function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputId = $input['id'];
		$inputActive = $input['active'];
		
		$account = new Account();
		
		echo $account->changeActive($link,$inputId,$inputActive);
	});
	//endnewcode
    });
//end of account

//user
	$app->post('/user',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		//account
		$account = new Account();
		$inputAccount['username'] = $input['username'];
		$inputAccount['password'] = $input['password'];
		$inputAccount['role'] = "user";
		$inputAccount['active'] = 1;
		$respond = $account->addAccount($link,$inputAccount);
		if($respond=="success")
		{
			//user
			$user = new User();
			$inputUser['account_id'] = mysqli_insert_id($link);
			$inputUser['first_name'] = $input['first_name'];
			$inputUser['last_name'] = $input['last_name'];
			$inputUser['birthday'] = $input['birthday'];
			$inputUser['sex'] = $input['sex'];
			$inputUser['photo'] = $input['photo'];
			$inputUser['phone'] = $input['phone'];
			$respond = $user->addUser($link,$inputUser);
			if($respond=="success"){
				echo '{"status":"success","id":"'.mysqli_insert_id($link).'"}';
			}
			else
			{
				echo $respond;
			}
		}
		else
		{
			echo $respond;
		}
		
		//setting
	});
	
	$app->get('/user/:id', function($id) use ($link){
		$user = new User();
		$respond = $user->getUserById($link,$id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			//tempel last login
			$timeline = new Timeline();
			$lastCheckIn = $timeline->getLastCheckInByUser($link,$id);
			$respond['last_check_in'] = $lastCheckIn;
			$lastPlace = $timeline->getLastCheckInPlaceByUser($link,$id);
			$respond['last_place'] = $lastPlace;
			$place = new Place();
			$respond['last_place_name'] = $place->getNameFromId($link, $lastPlace);
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/user/:field/:value', function($field,$value) use ($link){
		if($field == "name")
		{
			$user = new User();
			$respond = $user->getUserByName($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				echo str_replace('\\/', '/', json_encode($respond));
			}
		}
	});
	
	$app->put('/user/:field/:id/:value',function($field,$id,$value) use ($link){
		$user = new User();
		if($field == "name")
		{
			echo $user->updateUserName($link,$id,$value);
		}
		else if($field == "email")
		{
			echo $user->updateUserEmail($link,$id,$value);
		}
		else if($field == "phone")
		{
			echo $user->updateUserPhone($link,$id,$value);
		}
	});
	
	//get all user --->> ke account dulu baru ke
	$app->get('/user', function() use ($link){
		$account = new Account();
		$respond = $account->getAllAccount($link);
		if(is_string($respond)){
			echo $respond;
		}
		else
		{		
			//echo $respond;
			$user = new User();	
			
			$result = array();
			$arrid = array();
			$arractive = array();
			$arrfirstname = array();
			$arrlastname = array();
			$arrphoto = array();
					
			
			$counter = 0;
				$newrow = array();
			foreach($respond as $rows)
			{
				$newuser = $user->getUserById($link,$rows['id']);					
				/*	$newrow[] = '
								"active":"'.$rows['active'].'",
								"first_name":"'.$newuser['first_name'].'",
								"last_name":"'.$newuser['last_name'].'",
								"photo":"'.$newuser['photo'].'"
								';
								*/
					$counter++;		

					$arrid[] = $rows['id'];
						//$id = $rows['id'];
					$arractive[] = $rows['active'];		
						//$active = $rows['active'];		
					$arrfirstname[] = $newuser['first_name'];
						//$firstname = $newuser['first_name'];
					$arrlastname[] = $newuser['last_name'];
						//$lastname = $newuser['last_name'];					
					$arrphoto[] = $newuser['photo'];
						//$photo = $newuser['photo'];
					
					
					/*$result[] = '
								"active":"'.$active.'",
								"first_name":"'.$firstname.'",
								"last_name":"'.$lastname.'",
								"photo":"'.$photo.'"
								';
					*/
					
					$result[] = array(
						"id" => $arrid,
						"active" => $arractive,
						"first_name" => $arrfirstname,
						"last_name" => $arrlastname,
						"photo" => $arrphoto
					);
					
			}

			//echo json_encode($result);	
			echo json_encode($result[$counter-1]);	
			
			//echo json_encode($newrow[$counter-1]);			
			//echo json_encode($newrow[$counter-1]);
		}
	});
//end of user

//place
	
	//newcode -----> cari 15 new place terhitung 1 bulan ke belakang
	$app->get('/get15newplace', function() use ($link){
		$place = new Place();		
		$respond = $place->getPlace($link);
		
		$currentdate = new DateTime('now');	
		$count = 0;
		$result = array();
			$id = array();
			$name = array();
			$address = array();
		foreach($respond as $rows)
		{
			$datetimerow = $rows['create_time']; 
			$daterow = new DateTime($datetimerow);
			$interval = $currentdate->diff($daterow);
			$intervaldays = $interval->format('%a');
			if($intervaldays < 30 ){
				$count++;				
				$id[] = $rows['id'];
				$name[] = $rows['name'];
				$address[] = $rows['address'];
				
			}
			if($count>14){
				break;
			}
			//echo $intervaldays;
			//echo 'berhasil';
			//$rows['id']
			//$rows['name']
			//$rows['address']
			//echo $date;
		}
		$result[] = array(
					"id" => $id,
					"name" => $name,
					"address" => $address
				);
		echo json_encode($result);
				
		/*$current_date = getdate();
		//echo $current_date['month'];
		$datetime1 = new DateTime('2009-10-11');
		$datetime2 = new DateTime('2009-10-17');
		$interval = $datetime1->diff($datetime2);
		echo $interval->format('%a');*/
	});
	
	//isi kolom position table trending (1-15)	
	$app->get('/get15trendingplace', function() use ($link){	
		$place = new Place();
		$respond = $place->getAllTrendingPlace($link);
		$result = array();
			$position = array();
			$id = array();
			$name = array();
			$address = array();
			$telp = array();
			$website = array();
			$email = array();
			$rating = array();
			$day_life = array();
			$create_time = array();
			$photo = array();
			$visibility = array();
			$city = array();
		if($respond == 'gagal'){
			echo 'gagal';
		}else{		
			foreach($respond as $rows)
			{	
				//id name address telp website email rating day_life create_time photo visibility city
				if($rows['position']>=1 && $rows['position']<=15){
					$getplace = $place->getPlaceFromId($link,$rows['place_id']); 
						$position[] = $rows['position'];
						$id[] = $getplace['id'];
						$name[] = $getplace['name'];
						$address[] = $getplace['address'];
						$telp[] = $getplace['telp'];
						$website[] = $getplace['website'];
						$email[] = $getplace['email'];
						$rating[] = $getplace['rating'];
						$day_life[] = $getplace['day_life'];
						$create_time[] = $getplace['create_time'];
						$photo[] = 'file_upload/place/'.$getplace['name'].'-'.$getplace['location'].'/'.$getplace['photo'];
						$visibility[] = $getplace['visibility'];
						$city[] = $geplace['city'];
				}
			}
			$result[] = array(
						"position" => $position,
						"id" => $id,
						"name" => $name,
						"address" => $address,
						"telp" => $telp,
						"website" => $website,
						"email" => $email,
						"rating" => $rating,
						"day_life" => $day_life,
						"create_time" => $create_time,
						"photo" => $photo,
						"visibilty" => $visibility,
						"city" => $city
			);
			echo json_encode($result);
		}
	});
	
	
	//isi kolom position table top (1-15)
	$app->get('/get15topplace', function() use ($link){
		$place = new Place();
		$respond = $place->getAllTopPlace($link);
		$result = array();
			$position = array();
			$id = array();
			$name = array();
			$address = array();
			$telp = array();
			$website = array();
			$email = array();
			$rating = array();
			$day_life = array();
			$create_time = array();
			$photo = array();
			$visibility = array();
			$city = array();
		if($respond == 'gagal'){
			echo 'gagal';
		}else{		
			foreach($respond as $rows)
			{
				//id name address telp website email rating day_life create_time photo visibility city
				if($rows['position']>=1 && $rows['position']<=15){
					$getplace = $place->getPlaceFromId($link,$rows['place_id']); 
					
					$position[] = $rows['position'];
					$id[] = $getplace['id'];
					$name[] = $getplace['name'];
					$address[] = $getplace['address'];
					$telp[] = $getplace['telp'];
					$website[] = $getplace['website'];
					$email[] = $getplace['email'];
					$rating[] = $getplace['rating'];
					$day_life[] = $getplace['day_life'];
					$create_time[] = $getplace['create_time'];
					$photo[] = 'file_upload/place/'.$getplace['name'].'-'.$getplace['location'].'/'.$getplace['photo'];
					$visibility[] = $getplace['visibility'];
					$city[] = $geplace['city'];
				}
			}
			$result[] = array(
						"position" => $position,
						"id" => $id,
						"name" => $name,
						"address" => $address,
						"telp" => $telp,
						"website" => $website,
						"email" => $email,
						"rating" => $rating,
						"day_life" => $day_life,
						"create_time" => $create_time,
						"photo" => $photo,
						"visibilty" => $visibility,
						"city" => $city
			);
			echo json_encode($result);
		}
	});
	
	$app->put('/trendingplace',function() use ($link,$app) {    
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPosition = $input['position'];
		$inputNewPlaceId = $input['newplaceid'];
		$place = new Place();					
		
		echo $place->updateTrendingPlace($link,$inputPosition,$inputNewPlaceId);		
	});
	
	$app->put('/topplace',function() use ($link,$app) {        
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPosition = $input['position'];
		$inputNewPlaceId = $input['newplaceid'];
		$place = new Place();	
		
		echo $place->updateTopPlace($link,$inputPosition,$inputNewPlaceId);
	});
	//endnewcode
	
	$app->get('/place', function() use ($link){
		$place = new Place();
		$respond = $place->getPlace($link);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
		
			$allplace = array();
			foreach($respond as $rows)
			{
				$category = $place->getCategoryByPlace($link,$rows['id']);
				if(!is_string($category))
				{
					$feature = array();
					$price = array();
					foreach($category as $categoryRows)
					{
						if($categoryRows['category']=="feature")
						{
							$feature[] = $categoryRows['value'];
						}
						else if($categoryRows['category']=="price")
						{
							$price[] = $categoryRows;
						}
						else if($categoryRows['category']=="cuisine")
						{
							$cuisine = $categoryRows['value'];
						}
						else if($categoryRows['category']=="membership")
						{
							$membership = $categoryRows['value'];
						}
					}
					
					//parse price
					$lowPrice = explode(" ", $price[0]['value']);
					$highPrice = explode(" ", $price[count($price)-1]['value']);
					$priceSummary = "";
					if($lowPrice[0]=="Below")
					{
						if($highPrice[0]=="Above")
							$priceSummary = "Any Price";
						else
							$priceSummary = "Below ".$highPrice[count($highPrice)-1];
					}
					else if($highPrice[0]=="Above")
					{
						$priceSummary = "Above ".$lowPrice[0];
					}
					else
					{
						$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
					}
					
					$allplace[] = array(
						"place" => $rows,
						"feature" => $feature,
						"price" => $priceSummary,
						"cuisine" => $cuisine,
						"membership" => $membership
					);
				}
				else{
					$allplace[] = array(
						"place" => $rows,
						"feature" => "",
						"price" => "",
						"cuisine" => "",
						"membership" => ""
					);
				}
			}
			echo json_encode($allplace);
		}
		
	});
	
	$app->get('/place/:field/:value', function($field,$value) use ($link){
		if($field == "days")
		{
			$place = new Place();
			$respond = $place->getPlaceFromDayLife($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
			
				$allplace = array();
				foreach($respond as $rows)
				{
					$category = $place->getCategoryByPlace($link,$rows['id']);
					if(!is_string($category))
					{
						$feature = array();
						$price = array();
						foreach($category as $categoryRows)
						{
							if($categoryRows['category']=="feature")
							{
								$feature[] = $categoryRows['value'];
							}
							else if($categoryRows['category']=="price")
							{
								$price[] = $categoryRows;
							}
							else if($categoryRows['category']=="cuisine")
							{
								$cuisine = $categoryRows['value'];
							}
							else if($categoryRows['category']=="membership")
							{
								$membership = $categoryRows['value'];
							}
						}
						
						//parse price
						$lowPrice = explode(" ", $price[0]['value']);
						$highPrice = explode(" ", $price[count($price)-1]['value']);
						$priceSummary = "";
						if($lowPrice[0]=="Below")
						{
							if($highPrice[0]=="Above")
								$priceSummary = "Any Price";
							else
								$priceSummary = "Below ".$highPrice[count($highPrice)-1];
						}
						else if($highPrice[0]=="Above")
						{
							$priceSummary = "Above ".$lowPrice[0];
						}
						else
						{
							$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
						}
						
						$allplace[] = array(
							"place" => $rows,
							"feature" => $feature,
							"price" => $priceSummary,
							"cuisine" => $cuisine,
							"membership" => $membership
						);
					}
					else{
						$allplace[] = array(
							"place" => $rows,
							"feature" => "",
							"price" => "",
							"cuisine" => "",
							"membership" => ""
						);
					}
				}
				echo str_replace('\\/', '/', json_encode($allplace));
			}
		}
	});
	
	$app->get('/place/:id', function($id) use ($link){
		
		$place = new Place();
		$respond = $place->getPlaceFromId($link,$id);
			
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			$respond['photo'] = 'file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
			$getPlace;
			$category = $place->getCategoryByPlace($link,$respond['id']);
			if(!is_string($category))
			{
				$feature = array();
				$price = array();
				foreach($category as $categoryRows)
				{
					if($categoryRows['category']=="feature")
					{
						$feature[] = $categoryRows['value'];
					}
					else if($categoryRows['category']=="price")
					{
						$price[] = $categoryRows;
					}
					else if($categoryRows['category']=="cuisine")
					{
						$cuisine = $categoryRows['value'];
					}
					else if($categoryRows['category']=="membership")
					{
						$membership = $categoryRows['value'];
					}
				}
				
				//parse price
				$lowPrice = explode(" ", $price[0]['value']);
				$highPrice = explode(" ", $price[count($price)-1]['value']);
				$priceSummary = "";
				if($lowPrice[0]=="Below")
				{
					if($highPrice[0]=="Above")
						$priceSummary = "Any Price";
					else
						$priceSummary = "Below ".$highPrice[count($highPrice)-1];
				}
				else if($highPrice[0]=="Above")
				{
					$priceSummary = "Above ".$lowPrice[0];
				}
				else
				{
					$priceSummary = $lowPrice[0]." - ".$highPrice[count($highPrice)-1];
				}
				
				$getPlace = array(
					"place" => $respond,
					"feature" => $feature,
					"price" => $priceSummary,
					"cuisine" => $cuisine,
					"membership" => $membership
				);
			}
			else{
				$getPlace = array(
					"place" => $respond,
					"feature" => "",
					"price" => "",
					"cuisine" => "",
					"membership" => ""
				);
			}
			echo str_replace('\\/', '/', json_encode($getPlace));
		}
		
	});

	$app->post('/place',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPlace = $input['place'];
		$inputCategory = $input['feature'];
		$inputPrice = explode(";", $input['price']) ;
		$inputCuisine = $input['cuisine'];
		$inputMembership = $input['membership'];
		
		$place = new Place();
		$respond = $place->insertPlace($link,$inputPlace);
		if($respond != "success")
		{
			echo $respond;
		}
		else
		{
		
			$errorCheck = true;
			$place_id = mysqli_insert_id($link);
			foreach($inputCategory as $value)
			{
				$respond = $place->addCategory($link,$place_id,"feature",$value);
				if($respond!="success") $errorCheck = false;
			}
			
			//cuisine
			$respond = $place->addCategory($link,$place_id,"cuisine",$inputCuisine);
			if($respond!="success") $errorCheck = false;
				
			//membership
			$respond = $place->addCategory($link,$place_id,"membership",$inputMembership);
			if($respond!="success") $errorCheck = false;
			
			$low = $inputPrice[0];
			$high = $inputPrice[1];
			if($low < $high)
			{
				if($low <= 30000)
				{
					$respond = $place->addCategory($link,$place_id,"price","Below 30000");
					if($respond!="success") $errorCheck = false;
				}
				$priceRange = array(30000,50000,100000,200000,300000,500000);
				for($i = 1;$i<count($priceRange);$i++)
				{
					if(($priceRange[$i-1] < $low && $low <= $priceRange[$i]) || ($priceRange[$i-1] < $high && $high <= $priceRange[$i]) || ($low <=$priceRange[$i-1] && $high > $priceRange[$i]))
					{
						$respond = $place->addCategory($link,$place_id,"price",($priceRange[$i-1]+1)." - ".$priceRange[$i]);
						if($respond!="success") $errorCheck = false;
					}
				}
				if($high >500000)
				{
					$respond = $place->addCategory($link,$place_id,"price","Above 500000");
					if($respond!="success") $errorCheck = false;
				}
			}
			if($errorCheck) echo '{"status":"success"}';
			else echo '{"status":"error","message":"not all category inserted"}';
		}
		
	});

	$app->put('/place/:id',function($id) use ($link,$app) {
		
        $request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPlace = $input['place'];
		$inputCategory = $input['feature'];
		$inputPrice = explode(";", $input['price']);
		$inputCuisine = $input['cuisine'];
		$inputMembership = $input['membership'];
		
		$place = new Place();
		
		$respond = $place->getPlaceFromId($link,$id);
		$respond['photo'] = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo']; 
		
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			
			//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
			if ($inputPlace['photo']!="" && file_exists($dir))
			{
				unlink($dir);
			}
			
			if ($inputPlace['photo']=="")
			{
				$inputPlace['photo'] = $respond['photo'];
			}
			rename('../file_upload/place/'.$respond['name'].'-'.$respond['location'],'../file_upload/place/'.$inputPlace['name'].'-'.$inputPlace['location']);
			$respond = $place->updatePlace($link,$id,$inputPlace);
			if($respond != "success")
			{
				echo $respond;
			}
			else
			{
				$place->delCategory($link,$id);
				$errorCheck = true;
				foreach($inputCategory as $value)
				{
					$respond = $place->addCategory($link,$id,"feature",$value);
					if($respond!="success") $errorCheck = false;
				}
				
				//cuisine
				$respond = $place->addCategory($link,$id,"cuisine",$inputCuisine);
				if($respond!="success") $errorCheck = false;
				
				//membership
				$respond = $place->addCategory($link,$id,"membership",$inputMembership);
				if($respond!="success") $errorCheck = false;
			
				$low = $inputPrice[0];
				$high = $inputPrice[1];
				if($low < $high)
				{
					if($low <= 30000)
					{
						$respond = $place->addCategory($link,$id,"price","Below 30000");
						if($respond!="success") $errorCheck = false;
					}
					$priceRange = array(30000,50000,100000,200000,300000,500000);
					for($i = 1;$i<count($priceRange);$i++)
					{
						if(($priceRange[$i-1] < $low && $low <= $priceRange[$i]) || ($priceRange[$i-1] < $high && $high <= $priceRange[$i]) || ($low <=$priceRange[$i-1] && $high > $priceRange[$i]))
						{
							$respond = $place->addCategory($link,$id,"price",($priceRange[$i-1]+1)." - ".$priceRange[$i]);
							if($respond!="success") $errorCheck = false;
						}
					}
					if($high >500000)
					{
						$respond = $place->addCategory($link,$id,"price","Above 500000");
						if($respond!="success") $errorCheck = false;
					}
				}
				if($errorCheck) echo '{"status":"success"}';
				else echo '{"status":"error","message":"not all category updated"}';
			}
		}
		
    });
	
	function deleteDirectory($dir) {
	    if (!file_exists($dir)) {
	        return true;
	    }
	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }
	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }
	
	        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }
	
	    }
	    return rmdir($dir);
	}
	
	$app->delete('/place/:id',function($id) use ($link) {
        $place = new Place();
		$respond = $place->getPlaceFromId($link,$id);
		$respond['photo'] = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond['photo'];
		
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'];
			if (is_dir($dir)) {
				deleteDirectory($dir);
			}
			
			$respond = $place->delCategory($link,$id);
			if($respond=="success"){
				$respond = $place->deletePlace($link,$id);
				if($respond=="success") echo '{"status":"success"}';
				else echo $respond;
			}else
			{
				echo $respond;
			}
		}
		
		//delete gallery
		//delete fav_place
		//delete review
		//delete check in
    });
//end of place

//check_in
	$app->post('/checkIn',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$profile_id = $input['profile_id'];
		$place_id = $input['place_id'];
		$timeline = new Timeline();
		$respond = $timeline->checkIn($link,$profile_id,$place_id);
		if($respond == "success")
		{
			$place = new Place();
			$place_name = $place->getNameFromId($link,$place_id);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
				echo $timeline->postTimeline($link,$profile_id,"check_in",$place_name,$place_id);
		}
		else
			echo $respond;
		
	});
//end of check_in

//review
	$app->get('/review/:field/:value/:profile_id',function ($field,$value,$profile_id) use ($link){
		$place = new Place();
		if($field == "place")
		{
			$respond = $place->getReviewByPlace($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				$index = 0;
				foreach($respond as $iter)
				{
					$getLikeReview = $place->checkLikeReview($link,$iter['id'],$profile_id);
					if($getLikeReview['status'] == "success")
					{
						$respond[$index]['like'] = $getLikeReview['like'];
					}else{
						$respond[$index]['status'] = $getLikeReview['status'];
					}
					$index++;
				}
				echo str_replace('\\/', '/', json_encode($respond));
			}
		}
		else if($field == "user")
		{
			$respond = $place->getReviewByUser($link,$value);
			if(is_string($respond))
			{
				echo $respond;
			}
			else
			{
				$getLikeReview = $place->checkLikeReview($link,$respond['id'],$profile_id);
				if($getLikeReview ['status'] == "success")
				{
					$respond['countLike'] = $getLikeReview['count'];
					$respond['like'] = $getLikeReview['like'];
				}
				echo json_encode($respond);
			}
		}
	});
	
	$app->post('/review',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review['place_id'] = $input['place_id'];
		$review['profile_id'] = $input['profile_id'];
		$review['text'] = $input['text'];
		$review['rating'] = $input['rating'];
		//review
		$place = new Place();
		$respond = $place->addReview($link,$review);
		
		$user = new User();
		$user->updateNumReview($link,$review['profile_id']);
		//timeline
		if($respond == "success")
		{
			$place_name = $place->getNameFromId($link,$review['place_id']);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
			{
				//hitung rating
				$place->countRating($link,$review['place_id']);
				$timeline = new Timeline();
				echo $timeline->postTimeline($link,$review['profile_id'],"review",$place_name,$review['place_id']);
			}
		}
		else
			echo $respond;
		
	});
	
	$app->put('/review/:id',function($id) use ($link,$app) {
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review['text'] = $input['text'];
		$review['rating'] = $input['rating'];
		//review
		$place = new Place();
		echo $place->addReview($link,$review);
	});
	
	$app->delete('/review/:id',function($id) use ($link) {
		$place = new Place();
		$respond = $place->deleteLikeReview($link,$id);
		if($respond!="success")
		{
			echo $respond;
		}
		else
		{
			$respond = $place->deleteReview($link,$id);
			if($respond=="success") echo '{"status":"success"}';
			else echo $respond;
		}
	});
//end of review

//like_review
	//$app->get('/likeReview',function () use ($link,$app){});
	
	$app->get('/likeReview/:review_id',function ($review_id) use ($link,$app){
		$place = new Place();
		echo $place->getLikeByReview($link,$review_id);
	});
	
	$app->post('/likeReview',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$review_id = $input['review_id'];
		$profile_id = $input['profile_id'];
		//like_review
		$place = new Place();
		$respond = $place->likeReview($link,$review_id,$profile_id);
		
		//timeline
		if($respond == "success")
		{
			$place_name = $place->getNameFromId($link,$review['place_id']);
			json_decode($place_name);
			if(json_last_error() == JSON_ERROR_NONE)
				echo $place_name;
			else
			{
				$respond = $place->updateLikeReview($link,$review_id);
				if($respond)
				{
					$timeline = new Timeline();
					echo $timeline->postTimeline($link,$review['profile_id'],"likereview",$place_name,$review['place_id']);
				}
				else
				{
					echo '{"status":"error","message":"update failed"}';
				}
				
			}
		}
		else
			echo $respond;
	});
	
	$app->delete('/likeReview/:review_id/:profile_id',function ($review_id,$profile_id) use ($link,$app){
		$place = new Place();
		$respond = $place->unlikeReview($link,$review_id,$profile_id);
		
		
		$respond = $place->updateLikeReview($link,$review_id);
		if($respond)
		{
			echo '{"status":"success"}';
		}
		else
		{
			echo '{"status":"error","message":"update failed"}';
		}
	});
	
//end of like_review

//timeline
	$app->get('/post/:profile_id', function($profile_id) use ($link){
		$timeline = new Timeline();
		$respond = $timeline->getTimelineByUser($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/timeline/:profile_id', function($profile_id) use ($link){
		$timeline = new Timeline();
		$respond = $timeline->getTimelineByUserNFollowing($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
//end of timeline

//follower
	$app->get('/following/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getFollowing($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/follower/:follower_id', function($follower_id) use ($link){
		$user = new User();
		
		$respond = $user->getFollower($link,$follower_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->get('/checkfollow/:profile_id/:following_id', function($profile_id,$following_id) use ($link){
		$user = new User();
		
		echo $user->checkFollow($link,$profile_id,$following_id);
	});
	
	$app->post('/follow', function() use ($link){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		//echo '{"status":"tes"}';
		$user = new User();
		$respond = $user->follow($link,$input['profile_id'],$input['follower_id']);
		$user->updateNumFollower($link,$input['profile_id']);
		echo $respond;
	});
	
	$app->delete('/follow/:profile_id/:follower_id', function() use ($link){
		$user = new User();
		echo $user->unfollow($link,$profile_id,$follower_id);
	});
	
	$app->get('/suggestFollow/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getSuggestFollow($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
//end of follower

//preferences
	$app->get('/preference/:profile_id', function($profile_id) use ($link){
		$user = new User();
		
		$respond = $user->getPreferenceByUser($link,$profile_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo json_encode($respond);
		}
	});
	
	$app->post('/preference', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		
		$user = new User();
		$ret = true;
		foreach($input['value'] as $r)
		{
			$respond = $user->addPreference($link,$input['profile_id'],$r);
			if($respond != "success") $ret = false;
		}
		
		if($ret) echo '{"status":"success"}';
		else echo '{"status":"error"}';
		
	});
	
	$app->delete('/preference/:profile_id/:value', function($profile_id,$value) use ($link){
		$user = new User();
		echo $user->deletePreference($link,$input['profile_id'],$input['value']);
	});
	
//end of preference

//gallery
	$app->get('/gallery/:place_id', function($place_id) use ($link){
		$place = new Place();
		
		$respond = $place->getPhotoByPlace($link,$place_id);
		if(is_string($respond))
		{
			echo $respond;
		}
		else
		{
			echo str_replace('\\/', '/', json_encode($respond));
		}
	});
	
	$app->post('/gallery', function() use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		//echo '{"status":"'.$input['profile_id'].'-'.$input['place_id'].'-'.$input['photo'].'"}';
		$place = new Place();
		echo $place->addPhoto($link,$input['profile_id'],$input['place_id'],$input['photo']);
	});
	
	$app->delete('/gallery/:id', function($id) use ($link){
		echo deleteGallery($link,$id);
	});
	
	function deletePlaceGallery($link,$place_id)
	{
		$place = new Place();
		
		$respond = $place->getPhotoByPlace($link,$place_id);
		if(is_string($respond))
		{
			return $respond;
		}
		else
		{
			foreach($respond as $row)
			{
				deleteGallery($link,$row['id']);
			}
			return '{"status":"success"}';
		}
	}
	
	function deleteGallery($link,$id)
	{
		$place = new Place();
		
		$respond1 = $place->getGalleryFromId($link,$id);
		
		$respond = $place->getPlaceFromId($link,$respond1['place_id']);
		//delete foto
			$dir = '../file_upload/place/'.$respond['name'].'-'.$respond['location'].'/'.$respond1['photo'];
			if (file_exists($dir))
			{
				unlink($dir);
			}
		return $place->deletePhoto($link,$id);
	}
//end of gallery

//recommendation
	$app->get('/recommendation/:type', function($type) use ($link){
		$place = new Place();
		if($type=='new')
		{
			$respond = $place->getNewPlace($link);
			if($respond['status'] =='success')
			{
				
					$getPlace = $place->getPlaceFromId($link,$respond['value']['place_id']);
					$respond['value']['name'] = $getPlace['name'];
					$respond['value']['photo'] = 'file_upload/place/'.$getPlace['name'].'-'.$getPlace['location'].'/'.$getPlace['photo'];
					
				echo json_encode($respond['value']);
			}
			else
			{
				echo json_encode($respond);
			}
		}
		else
		{
			$respond = $place->getRecommendationByType($link,$type);
		
			if($respond['status'] =='success')
			{
				$iter = 0;
				foreach($respond['value'] as $rows)
				{
					$getPlace = $place->getPlaceFromId($link,$rows['place_id']);
					$respond['value'][$iter]['name'] = $getPlace['name'];
					$respond['value'][$iter]['photo'] = 'file_upload/place/'.$getPlace['name'].'-'.$getPlace['location'].'/'.$getPlace['photo'];
					$iter++;
				}
				
				echo json_encode($respond['value']);
			}
			else
			{
				echo json_encode($respond);
			}
		}
		
		
	});
	
	$app->post('/recommendation/:type', function($type) use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$place = new Place();
		
		$check = true;
		for($i = 0; $i<10;$i++)
		{
			if($rows == "") $place_id = NULL;
			else $place_id = $input['value'][$i];
			
			$exist = $place->checkRecommendationExist($link,$place_id,$type,($i+1));
			if(!$exist)
			{
				$respond = $place->addRecommendation($link,$place_id,$type,($i+1));
				if(!$respond) $check = false;
			}else
			{
				$respond = $place->editRecommendation($link,$place_id,$type,($i+1));
				if(!$respond) $check = false;
			}
			
		}
		
		if($check) echo '{"status":"success"}';
		else echo '{"status":"error","message":"sql error"}';
	});
//end of recommendation

//run
$app->run();


?>