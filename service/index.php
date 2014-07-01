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
			$inputUser['account_id'] = $account->getLastId($link);
			$inputUser['first_name'] = $input['first_name'];
			$inputUser['last_name'] = $input['last_name'];
			$inputUser['birthday'] = $input['birthday'];
			$inputUser['sex'] = $input['sex'];
			$inputUser['photo'] = $input['photo'];
			$inputUser['email'] = $input['email'];
			$inputUser['phone'] = $input['phone'];
			$user->addUser($link,$inputUser);
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
			echo json_encode($respond);
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
//end of user

//place
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
						"price" => $priceSummary
					);
				}
				else{
					$allplace[] = array(
						"place" => $rows,
						"feature" => "",
						"price" => ""
					);
				}
			}
			echo json_encode($allplace);
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
					"price" => $priceSummary
				);
			}
			else{
				$getPlace = array(
					"place" => $respond,
					"feature" => "",
					"price" => ""
				);
			}
			echo json_encode($getPlace);
		}
		
	});

	$app->post('/place',function () use ($link,$app){
		$request = $app->request();
		$body = $request->getBody();
		$input = json_decode($body,true);
		
		$inputPlace = $input['place'];
		$inputCategory = $input['feature'];
		$inputPrice = explode(";", $input['price']) ;
		$place = new Place();
		$respond = $place->insertPlace($link,$inputPlace);
		
		if($respond != "success")
		{
			echo $respond;
		}
		else
		{
		
			$errorCheck = true;
			$place_id = $place->getLastId($link);
			foreach($inputCategory as $value)
			{
				$respond = $place->addCategory($link,$place_id,"feature",$value);
				if($respond!="success") $errorCheck = false;
			}
			
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
		$inputPrice = $input['price'];
		$place = new Place();
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
    });
	
	$app->delete('/place/:id',function($id) use ($link) {
        $place = new Place();
		$respond = $place->delCategory($link,$id);
		if($respond!="success")
		{
			echo $respond;
		}
		else
		{
			$respond = $place->deletePlace($link,$id);
			if($respond=="success") echo '{"status":"success"}';
			else echo $respond;
		}
    });
//end of place


//run
$app->run();


?>