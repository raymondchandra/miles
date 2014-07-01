<?php
	class Place{
	//place
		function insertPlace($link,$place){
			$name = mysqli_escape_string($link,$place['name']);
			$location = mysqli_escape_string($link,$place['locations']);
			$address = mysqli_escape_string($link,$place['address']);
			$telp = mysqli_escape_string($link,$place['telp']);
			$website = mysqli_escape_string($link,$place['website']);
			$email = mysqli_escape_string($link,$place['email']);
			$photo = mysqli_escape_string($link,$place['photo']);
			$day_life = $place['days'];
			$sql = 'INSERT INTO place (name,location,address,telp,website,email,rating,day_life, create_time,photo) VALUES ("'.$name.'", "'.$location.'","'.$address.'","'.$telp.'","'.$website.'","'.$email.'", 3, '.$day_life.', now(), "'.$photo.'")';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not inserted"}';
			}
		}
		
		function deletePlace($link,$id){
			$sql = 'DELETE FROM place WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function updatePlace($link,$id,$place){
			$name = mysqli_escape_string($link,$place['name']);
			$address = mysqli_escape_string($link,$place['address']);
			$telp = mysqli_escape_string($link,$place['telp']);
			$website = mysqli_escape_string($link,$place['website']);
			$email = mysqli_escape_string($link,$place['email']);
			$photo = mysqli_escape_string($link,$place['photo']);
			$day_life = $place['days'];
			
			$sql = 'UPDATE place SET name="'.$name.'", address="'.$address.'",telp="'.$telp.'",website="'.$website.'",email="'.$email.'", day_life='.$day_life.', create_time=now(), photo="'.$photo.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not updated"}';
			}
		}
		
		function updatePhoto($link,$id,$dir)
		{
			
		}
		
		function getPlaceFromId($link,$id){
			$sql = 'SELECT * FROM place WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows;
				}else{
					return '{"status":"error","message":"place not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getPlace($link){
			$sql = 'SELECT * FROM place';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"place not found"}';
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
		
		function getPlaceFromLocation(){}
		
		function getPlaceFromName(){}
		
		function getPlaceFromDayLife(){}
		
		function getLastId($link)
		{
			$sql = 'SELECT id FROM place ORDER BY id DESC LIMIT 1';
			
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
		}
	//end of place
		
	//place_category
		function addCategory($link,$place_id,$category,$value)
		{
			$sql = 'INSERT INTO place_category (place_id,category,value) VALUES ("'.$place_id.'", "'.$category.'","'.$value.'")';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not inserted"}';
			}
		}
		
		function delCategory($link,$place_id)
		{
			$sql = 'DELETE FROM place_category WHERE place_id ='.$place_id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		//function updateCategory(){}
		
		function getCategoryByPlace($link,$place_id)
		{
			$sql = 'SELECT * FROM place_category WHERE place_id="'.$place_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"category not found"}';
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
		
		function getCategoryByPlaceCategory(){}
	//end of place_category
	
	//gallery
		function addPhoto(){}
		
		function deletePhoto($link,$id)
		{
			$sql = 'DELETE FROM gallery WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getPhotoByUser(){}
		
		function getPhotoByPlace(){}
	//end of gallery
	
	//review
		function addReview(){}
		
		function deleteReview($link,$id)
		{
			$sql = 'DELETE FROM review WHERE id ='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function editReview(){}
		
		function getReviewByPlace(){}
		
		function getReviewByUser(){}
	//end of review
	
	//like_review
		function likeReview(){}
		
		function unlikeReview(){}
		
		function getLikeByReview(){}
	//end of like_review
	
	
	}
?>