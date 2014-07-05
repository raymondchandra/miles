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
			
			$sql = 'UPDATE place SET name="'.$name.'",address="'.$address.'",telp="'.$telp.'",website="'.$website.'",email="'.$email.'", day_life='.$day_life.', create_time=now(), photo="'.$photo.'" WHERE id='.$id;
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
		
		function countRating($link,$id)
		{
		
			$sql = 'SELECT avg(rating) as avg FROM review WHERE place_id ='.$id.' GROUP BY place_id';
			$result = mysqli_query($link,$sql);
			$value = mysqli_fetch_object($result);
			$rating = $value->avg; 
			$sql = 'UPDATE place SET rating="'.$rating.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not updated"}';
			}
		}
		
		function getPlaceFromId($link,$id){
			$sql = 'SELECT * FROM place WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					$rows['photo'] = 'file_upload/place/'.$rows['name'].'-'.$rows['location'].'/'.$rows['photo'];
			
					return $rows;
				}else{
					return '{"status":"error","message":"place not found"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function getNameFromId($link,$id){
			$sql = 'SELECT name FROM place WHERE id ='.$id;
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 1){
					$rows = mysqli_fetch_assoc($result);
					return $rows['name'];
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
						$r['photo'] = 'file_upload/place/'.$r['name'].'-'.$r['location'].'/'.$r['photo'];
			
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
		
		/*function getLastId($link)
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
		}*/
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
		function addReview($link,$review)
		{
			$place_id = mysqli_escape_string($link,$review['place_id']);
			$profile_id = mysqli_escape_string($link,$review['profile_id']);
			$text = mysqli_escape_string($link,$review['text']);
			$rating = $review['rating'];
			$sql = 'INSERT INTO review (place_id,profile_id,text,rating,num_like) VALUES ('.$place_id.','.$profile_id.',"'.$text.'",'.$rating.',0)';
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"check in failed"}';
			}
		}
		
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
		
		function editReview($link,$id,$review)
		{
			$text = mysqli_escape_string($link,$review['text']);
			$rating = $review['rating'];
			
			$sql = 'UPDATE review SET text="'.$text.'", rating="'.$rating.'" WHERE id='.$id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"check in failed"}';
			}
		}
		
		function getReviewByPlace($link,$place_id)
		{
			$sql = 'SELECT * FROM review WHERE place_id="'.$place_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"review not found"}';
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
		
		function getReviewByUser($link,$profile_id)
		{
			$sql = 'SELECT * FROM review WHERE place_id="'.$profile_id.'"';
			
			if($result = mysqli_query($link, $sql)){
				$num_rows = mysqli_num_rows($result);
				if($num_rows == 0){
					return '{"status":"error","message":"review not found"}';
				}else{
					$rows = array();
					while($r = mysqli_fetch_assoc($result)) {
						$sql = 'SELECT name FROM place WHERE id="'.$r['place_id'].'" LIMIT 1';
						$result = mysql_query($sql);
						$value = mysql_fetch_object($result);
						$r['place'] = $value->name;
						$rows[] = $r;
					}
					return $rows;
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
	//end of review
	
	//like_review
		function likeReview($link,$review_id,$profile_id)
		{
			$sql = 'INSERT INTO like_review (review_id,profile_id) VALUES ('.$review_id.','.$profile_id.')';
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function unlikeReview($link,$review_id,$profile_id)
		{
			$sql = 'DELETE FROM like_review WHERE review_id ='.$review_id.' AND profile_id='.$profile_id;
			if (mysqli_query($link, $sql)) {
				//success
				return '{"status":"success"}';
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
		
		function getLikeByReview($link,$review_id,$profile_id)
		{
			$sql = 'SELECT count(profile_id) as ct FROM like_review GROUP BY review_id HAVING review_id='.review_id;
						
			if($result = mysqli_query($link, $sql)){
				$value = mysql_fetch_object($result);
				$numLike = $value->ct;
				
				$sql = 'SELECT * FROM like_review WHERE review_id='.$review_id.' AND profile_id='.$profile_id.' LIMIT 1';
				if($result = mysqli_query($link, $sql)){
					$num_rows = mysqli_num_rows($result);
					if($num_rows == 1){
						return '{"count":"'.$numLike.'","like":"yes"}';
					}else{
						return '{"count":"'.$numLike.'","like":"no"}';
					}
				}else{
					return '{"status":"error","message":"sql error"}';
				}
			}else{
				return '{"status":"error","message":"sql error"}';
			}
		}
		
		function deleteLikeReview($link,$review_id)
		{
			$sql = 'DELETE FROM like_review WHERE review_id ='.$review_id;
			if (mysqli_query($link, $sql)) {
				//success
				return "success";
			}else{
				//error
				return '{"status":"error","message":"place not deleted"}';
			}
		}
	//end of like_review
	
	
	}
?>