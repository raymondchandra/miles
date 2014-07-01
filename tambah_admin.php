<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/md5.js"></script>
<script>
	$.validator.setDefaults({
		submitHandler: function() {
			$email = $('#new_admin_email').val();
			$password = $.md5($('#new_admin_pass').val());
			//alert($email);
			//var input = "email="+$email+"&password="+$password;
			var input = JSON.stringify({
				"email": $email,
				"password" : $password
			});
			alert(input);
			$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: "service/accAdmin",
				dataType: "json",
				data: input,
				success: function(data){
					alert(data.status);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		}
	});

	$().ready(function() {
		$("#add_admin").validate({
			rules: {
				new_admin_email: {
					required: true,
					email: true
				},
				new_admin_pass: {
					required: true,
					minlength: 5
				}
			},
			messages: {
				new_admin_pass: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				new_admin_name: "Please enter a valid email address"
			}
		});
	});
	</script>

</head>
<body>
<form method="POST" id="add_admin" name="add_admin">
	User Name : <input type="text" id="new_admin_email" name="new_admin_email" /><br />
	Password : <input type="password" id="new_admin_pass" name="new_admin_pass"/><br />
	<input type="submit" name="add_admin_button" id="add_admin_button" value="Add Admin" />
</form>

<input type="button" value="hapus" id="delete_admin" />
<script>
		$('#delete_admin').click(function(){
			$id = $('#lid').val();
			var input = JSON.stringify({
				"id": $id
			});
			
			$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: "php/delete_admin.php",
				dataType: "json",
				data: input,
				success: function(data){
					alert(data.status);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert('error');
				}
			});
		});
	</script>
</body>
</html>