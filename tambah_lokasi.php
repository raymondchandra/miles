<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8' />
	<title>Add Location</title>
	<link rel='stylesheet' href='css/style.css' />
	<link rel='stylesheet' href='css/slider/jslider.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.blue.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.plastic.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.css' type='text/css'>
	<link rel='stylesheet' href='css/slider/jslider.round.plastic.css' type='text/css'>
		
	<link rel='stylesheet' href='sass/all.css' type='text/css'>
	
	<meta name='viewport' content='initial-scale=1.0, user-scalable=no'>
    <meta charset='utf-8'>
    <style>
      html, body{
        height: 100%;
        margin: 0px;
        padding: 0px
      }
	  #map-canvas {
		/*height: 50%;*/
		/*width:75%;*/
		margin-left:auto;
		margin-right:auto;
	  }
    </style>
   <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
   <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
   
   <script>
		function initialize(){
		  var mapOptions = {
			zoom: 20,
			center: new google.maps.LatLng(-6.917114, 107.609662)
		  };

		  var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
			var image = {
			url: 'images/icon/marker.png',
			// This marker is 20 pixels wide by 32 pixels tall.
			scaledSize: new google.maps.Size(30, 45)
			};
		  var marker = new google.maps.Marker({
			position: map.getCenter(),
			icon: image,
			map: map,
			title: 'Drag Me!',
			draggable:true
		  });
		  $('#location_location').val(marker.getPosition());
		  setMarkers(map,lokasi);
		  google.maps.event.addListener(map, 'click', function (e) {
				var latLng = e.latLng;
				marker.setPosition(latLng);
			});
		  google.maps.event.addListener(marker, 'mouseup', function() {
			$('#location_location').val(marker.getPosition());
		  });
		}
		
		function setMarkers(map, locations) {
		  for (var i = 0; i < locations.length; i++) {
			var beach = locations[i].substring(1, locations[i].length-1);
			var arr = beach.split(',');
			var myLatLng = new google.maps.LatLng(arr[0], arr[1]);
			var spot = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: nama[i]
			});
			bindListener(spot,map,id_tempat[i],nama[i],alamat[i],telepon[i],rendah[i]);
		  }
		}
		
	function bindListener(marker, map,id, name,address,phone,lprice) {
		google.maps.event.addListener(marker, 'click', function(){
			$('#store_id').val(id);
			$('#store_name').val(name);
			$('#address').val(address);
			$('#phone_number').val(phone);
			$('#price_range').val(lprice);
			//$('#hi_price').val(hprice);
			show_pop();
		});
	}
		//initialize();
		//google.maps.event.addDomListener(window, 'load', initialize);
	</script>
   
   <script>
    var id_tempat = [];
   	var nama = [];
   	var lokasi = [];
	var alamat = [];
	var telepon = [];
	var rendah = [];
	var tinggi = [];
   	function getLocation(){
	   	$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service/place',
			dataType: 'json',
			success: function(data){
				var counter2 = 0;
				if(data.length > 0){
				$(data).each(function(index,value){
					id_tempat[counter2] = value.place.id;
					nama[counter2]=value.place.name;
					lokasi[counter2]=value.place.location;
					alamat[counter2]=value.place.address;
					telepon[counter2]=value.place.telp;
					rendah[counter2]=value.price;
					//tinggi[counter2]=value.price;
					counter2++;
				});
				}
				//google.maps.event.addDomListener(window, 'load', initialize);
				initialize();
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	}
	
	$(document).ready(function(){
		setTimeout(getLocation,1);
		getLocation();
	});
   </script>
		
		
	<!--Slider-->
	
	
	
	<script type='text/javascript' src='js/slider/jshashtable-2.1_src.js'></script>
	<script type='text/javascript' src='js/slider/jquery.numberformatter-1.2.3.js'></script>
	<script type='text/javascript' src='js/slider/tmpl.js'></script>
	<script type='text/javascript' src='js/slider/jquery.dependClass-0.1.js'></script>
	<script type='text/javascript' src='js/slider/draggable-0.1.js'></script>
	<script type='text/javascript' src='js/slider/jquery.slider.js'></script>
	
	
	
</head>
<body>
	
	<div id='map-canvas' class='map_height'></div>
	<script type='text/javascript'>
		
		function updateSize(){
			// Get the dimensions of the viewport
			var width = $(window).width();
			var height = $(window).height();
			
			var tambah_lokasi = $('.tambah_lokasi').height();
			$('.map_height').height(height - tambah_lokasi - 42);
		};
		$(document).ready(updateSize);
		$(window).resize(updateSize); 
		
		/*$(document).scroll(function(){
			var y = $(this).scrollTop();
			var height = $(window).height();
			
			if (y > (height-35)) {
				$('.link_nav').removeClass('clr_white').addClass('clr_green');
				$('.header_pgc').removeClass('clr_white').addClass('clr_green');
				$('#main_nav').addClass('bgclr_white_08').addClass('border_white_07');
				$('.header_logo').css('background-color', 'rgba(255, 255, 255, 0.0)');
			} else {
				$('.link_nav').removeClass('clr_green').addClass('clr_white');
				$('.header_pgc').removeClass('clr_green').addClass('clr_white');
				$('#main_nav').removeClass('bgclr_white_08').removeClass('border_white_07');
				$('.header_logo').css('background-color', '#fff');

			}
		}); */
	</script>
	
	<div id='main' class='tambah_lokasi'>
		<h1>Upload Your Images</h1>
			<form method='post' enctype='multipart/form-data'  action='php/upload.php'>
				<div class='form_1'>
					&nbsp;
					 <div id='response'></div>
					<ul id='image-list'>
			 
					</ul>
				</div>
				<div class='form_1'>
					<input type='file' name='images' id='images'/>
						<span class='clear' style='height: 10px;'></span>
					<label>Name</label><input type='text' id='location_name' /> 
						<span class='clear' style='height: 10px;'></span>
					<input type='hidden' id='location_location' />
					<label>Day-life</label>
					<select id='location_day'>
						<option value='0'>Day</option>
						<option value='1'>Night</option>
						<option value='2'>Day-Night</option>
					</select>
						<span class='clear' style='height: 10px;'></span>
					<label>Address</label><input type='text' id='location_address' />
						<span class='clear' style='height: 10px;'></span>
					<label>Phone</label><input type='text' id='location_phone' />
						<span class='clear' style='height: 10px;'></span>
					
				</div>
				<div class='form_1'>
					<label>Website</label><input type='url' id='location_website' />
						<span class='clear' style='height: 10px;'></span>
					<label>E-mail</label><input type='email' name='email' id='location_email' />
						<span class='clear' style='height: 10px;'></span>
					<label>Preferensi</label>
					<input type='checkbox' id='drink' value='drink'>Drink
					<input type='checkbox' id='wine' value='wine'>Wine
					<input type='checkbox' id='beer' value='beer'>Beer
						<span class='clear' style='height: 10px;'></span>
					<input type='checkbox' id='liquor' value='liquor' style='margin-left: 108px;'>Liquor
					<input type='checkbox' id='coffee' value='coffee'>Coffee
					<input type='checkbox' id='wifi' value='wifi'>Wifi
						<span class='clear' style='height: 10px;'></span>
					<input type='checkbox' id='breakfast' value='breakfast' style='margin-left: 108px;'>Breakfast
					<input type='checkbox' id='hightea' value='hightea'>High Tea
						<span class='clear' style='height: 10px;'></span>
					<input type='checkbox' id='eatery' value='eatery'  style='margin-left: 108px;'>Eatery
					<input type='checkbox' id='nightonly' value='nightonly'>Night Only
					<input type='checkbox' id='club' value='club'>Club
						<!--<select id='location_prefer'>
							<option value='0'>Drink</option>
							<option value='1'>Wine</option>
							<option value='2'>Beer</option>
							<option value='3'>Liquor</option>
							<option value='4'>Coffee</option>
							<option value='5'>Wi-Fi</option>
							<option value='6'>Breakfast</option>
							<option value='7'>High Tea</option>
							<option value='8'>Eatery</option>
							<option value='9'>Night Only</option>
							<option value='10'>Club</option>
						</select>-->
				</div>
				<div class='form_1'>
					<label style='margin-bottom: 20px;'>Price Range</label>
						<!--<select id='location_range'>
							<option value='0'>0-30.000</option>
							<option value='1'>30.000-50.000</option>
							<option value='2'>50.000-100.000</option>
							<option value='3'>100.000-300.000</option>
							<option value='4'>300.000-500.000</option>
							<option value='5'>beyond 500.000</option>
						</select>-->
					
					<div class='layout-slider'>
					  <input id='location_price' type='slider' name='area' value='2;10' />
					</div>
					<script type='text/javascript' charset='utf-8'>
						jQuery('#location_price').slider({ 
						from: 0, 
						to: 500000,
						heterogeneity: ['0/0','99/500000'],
						scale: [0,'|',100000,'|',200000,'|',300000,'|',400000,'|',500000],
						limits: false,
						step: 1000,
						dimension: '',
						skin: 'blue',
						callback: function( value ){ console.dir( this ); } });
					</script>
				</div>
			<button type='submit' id='btn'>Upload Files!</button>
			<input type='button' id='submit_button' value='Submit'>
			
			<!-- tombol untuk memunculkan pop-up -->
			<a href='#' id='popup_button'>
				The Pop-upper!
			</a>
		</form>
	   
	</div>
	
	<!-- div pop-up -->
	<div class='pu_00 pop_up_super_c lazy' style='display: none;'>
		<a class='exit sprt close_56'></a>
		<div class='pop_up_tbl'>
			<div class='pop_up_cell'>
				<div class='c_12'>
					<div class='g_8 push_2 pd_30 pdt_20' style='background: #fff;'>
						<input type='hidden' id='store_id' value='' />
						<label>Store Name</label><input type='text' id='store_name' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>Day Life</label><input type='combobox' id='store_day' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>Address</label><input type='text' id='address' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>Phone</label><input type='text' id='phone_number' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>Website</label><input type='text' id='store_website' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>E-mail</label><input type='text' id='store_email' /> 
							<span class='clear' style='height: 10px;'></span>
						<label>Price Range</label><input type='text' id='price_range' /> 
							<span class='clear' style='height: 10px;'></span>
						<!--<label>Highest Price</label><input type='text' id='hi_price' /> 
							<span class='clear' style='height: 10px;'></span>-->
							
						<input type='button' id='update_button' value='Update' />
						<input type='button' id='remove_button' value='Remove' />
						<script>
							$('#remove_button').click(function(){
								$store_id = $('#store_id').val();
								$.ajax({
									type: 'DELETE',
									url: 'http://milesyourday.com/service/place/'+$store_id,
									dataType: 'json',
									success: function(data){
										alert(data.status);
										location.reload();
									},
									error: function(jqXHR, textStatus, errorThrown){
										alert(errorThrown);
									}
								});
							});
						</script>
					</div>
				</div>
				
			</div>
		</div>
		<script>
			//$('#popup_button').click(function() {$( '.pu_00' ).fadeIn( 277, function(){});});
			function show_pop() {
				$( '.pu_00' ).fadeIn( 277, function(){});
			;}
		</script>
	</div>
 
 
  <script src='js/upload/upload.js'></script>
  
  		<script type='text/javascript'>
			/**javascript untuk meng-close pop up**/
			$('.exit').click(function() {$( '.pop_up_super_c' ).fadeOut( 200, function(){});});	
			
			$('.pop_up_super_c').click(function (e)
			{
				var container = $('.pop_up_cell');

				if (container.is(e.target) )// if the target of the click is the container...
				{
					$( '.pop_up_super_c' ).fadeOut( 200, function(){});
					$('html').css('overflow-y', 'auto');
				}
			});
			
		</script>
</body>
</html>