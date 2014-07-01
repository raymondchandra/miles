<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/md5.js"></script>
<script>
	$.validator.setDefaults({
		submitHandler: function(){
			$email = $('#login_email').val();
			$password = $.md5($('#login_pass').val());
			alert($password);
			$.ajax({
				type: 'GET',
				url: "service/accLogin/"+$email+"/"+$password,
				dataType: "json",
				success: function(data){
					alert(data.status);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert('error');
				}
			});
		}
	});

	$().ready(function() {
		$("#login").validate({
			rules: {
				login_email: {
					required: true,
					email: true
				},
				login_pass: {
					required: true,
					minlength: 5
				}
			},
			messages: {
				login_pass: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				login_name: "Please enter a valid email address"
			}
		});
	});
	</script>
</head>
<body>
<form id="login" name="login">
	<label>User Name :</label> <input type="text" id="login_email" name="login_email" /><br />
	<label>Password :</label> <input type="password" id="login_pass" name="login_pass"/><br />
	<input type="submit" name="login_button" id="login_button" value="Log in" />
</form>
</body>
</html>