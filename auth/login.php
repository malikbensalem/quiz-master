<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
$_SESSION['user_id']='0';
$_SESSION['user_level']='0';
?>

<style type="text/css">
	#hero-img{
		background-image: url('<?echo $baseURL?>assets/images/login.png');
		height: 100vh;
		background-repeat: no-repeat;
		background-size: cover;
	    box-shadow: inset 10px 10px 10px 0px #00000080;
	}
	body{
		overflow-x: hidden;
	}


</style>

<html>
	<head>
		<?getHead('Login Page')?>

	<style>
		.row{
			margin-bottom: 10px;
		}
		
	</style>
	</head>
	<body class="mt-0">
		<div class="row mb-0">
			<div class="col-xl-3 col-lg-4 " style="padding:50px">
				<div class="row">
					<div class="col-sm-12 text-center">
						<img src="<?echo $baseURL?>assets/images/logo.svg" width="200">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<h1>Welcome to WebbiSkools</h1>
						<p>login to access the site</p>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12 text-center" id="alert">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<label for="email" class="required">Email address</label>
						<input id="email" placeholder="Email" class="form-control">
					</div>
				</div>
				
				<div id="reg-rows" style="display:none">
					<div class="row" >
						<div class="col-sm-12">
							<label for="first-name" class="required">First Name</label>
							<input id="first-name" placeholder="First name" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label for="last-name" class="required">Last Name</label>
							<input id="last-name" placeholder="Last name" class="form-control">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<label for="password" class="required">Password</label>
						<input type="password" id="password" placeholder="Password" class="form-control">
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-sm-12">
						<button class="btn btn-lg btn-block btn-info" id="login" data-login='login'>Login</button>
						<a href="#" id="register" class="btn btn-sm btn-block btn-link">Register</a>
					</div>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 d-none d-lg-block" id='hero-img'></div>
		</div>
		<script type="text/javascript" src="<?echo $baseURL?>assets/js/functions.js"></script>
		<script type="text/javascript">
		$('#register').click(function(){
			$('#reg-rows').toggle()
			if ($('#login').data('login')=='register'){
				$(this).text('Register')
				$('#login').text('Login')
				$('#login').data('login','login')
			}
			else{
				$(this).text('Login')
				$('#login').text('Register')
				$('#login').data('login','register')	
			}
		})

		$('#login').click(function(){
			action=$(this).data('login')           	

			if ($('#password').val().length<6){
				timedAlert('#alert','<div class="alert alert-danger"> Password needs to be atleast 6 characters long.')
            	return
			}
			if (action=='register' && ($('#first-name').val().length==''||$('#last-name').val().length=='')){
				timedAlert('#alert','<div class="alert alert-danger"> User must have a first and last name.')
				return
			}
			loginHtml = $(this).html();
			$(this).addClass('disabled')

			$(this).html('<i class="fa-solid fa-spinner fa-spin"></i>')


			$.ajax({
                method: 'POST',
                url: 'verify.php',
                data: {
                    action:action,
                    em:$('#email').val(),
                    pw:$('#password').val(),
                    fn:$('#first-name').val(),
                    ln:$('#last-name').val(),
                },
                dataType: 'json',
                success: function(data) {
                	timedAlert('#alert',data.alert)
                	if (data.result){
                		timedRedirect('<?echo $baseURL?>questionnaire/')
                	}
                	else{
            			$('#login').removeClass('disabled')
                		$('#login').html(loginHtml)
                	}
                }
            })
		})
		</script>
	</body>
</html>