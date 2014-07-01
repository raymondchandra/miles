var image = "A";
(function () {
	var input = document.getElementById("images"), 
		formdata = false;
	var submit = document.getElementById("submit_button");
	function showUploadedItem (source) {
  		var list = document.getElementById("image-list"),
	  		li   = document.createElement("li"),
	  		img  = document.createElement("img");
		var but = document.createElement("input");
		but.setAttribute("type","button");
		but.setAttribute("id","remove_but");
		but.setAttribute("value","X");
  		img.src = source;
		if(list.childNodes[0]){
			list.removeChild(list.childNodes[0]);
			//list.removeChild(list.childNodes[0]);
		}
  		li.appendChild(img);
		li.appendChild(but);
		list.appendChild(li);
	}   

	if (window.FormData){
  		formdata = new FormData();
  		document.getElementById("btn").style.display = "none";
	}
	
 	input.addEventListener("change", function (evt) {
 		//document.getElementById("response").innerHTML = "Uploading . . ."
 		var i = 0, len = this.files.length, img, reader, file;
		//document.getElementById("images").disabled = true;
		for ( ; i < len; i++ ) {
			file = this.files[i];
	
			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) { 
						showUploadedItem(e.target.result, file.fileName);
						var remove_button = document.getElementById("remove_but");
							remove_button.addEventListener("click",function(evt){
									image = "";
									//document.getElementById("images").disabled = false;
									document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
									document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
							},false);
					};
					reader.readAsDataURL(file);
				}
				if (formdata) {
					//formdata.append("images[]", file);
					image = file;
				}
			}	
		}
	}, false);
	
	submit.addEventListener("click",function(evt){
		var names = $('#location_name').val();
		var locations = $('#location_location').val();
		
		//upload data
			
			
			var days = $('#location_day option:selected').val();
			var address = $('#location_address').val();
			var phone = $('#location_phone').val();
			var email = $('#location_email').val();
			var url = $('#location_website').val();
			var foto = image.name;
			var range = $('#location_price').val();
			
			var obj = [];
			var counter = 0;
			$('input[type=checkbox]:checked').each(function() {
				if (!obj.hasOwnProperty(this.name)) 
					obj[counter] = this.value;
				else 
					obj[counter].push(this.value);
				counter++;
			});
						
			var photos = "../file_upload/place/"+names+"/"+image.name;
			var data = {
			  place: {
				'name': names,
				'locations' : locations,
				'days' : days,
				'address' : address,
				'telp' : phone,
				'email' : email,
				'website' : url,
				'photo' : photos
			  },
			  feature:obj,
			  price:range
			};
			var input = JSON.stringify(data);
			
			$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: 'http://milesyourday.com/service/place',
				dataType: 'json',
				data: input,
				success: function(data){
					//alert(data.status);
					formdata.append("images[]", image);
					formdata.append("name", names);
					formdata.append("location",locations);
					// upload gambar
					if (formdata) {
						$.ajax({
							url: "php/upload.php",
							type: "POST",
							data: formdata,
							processData: false,
							contentType: false,
							success: function (res) {
								//document.getElementById("response").innerHTML = res;
								location.reload();
							}
						});
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		
		//***********************************************************
	},false);
}());
