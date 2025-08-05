
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">

		.main-header {
			background-image: url(<?php echo base_url(); ?>.'assets/images/background.jpg');
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
			width: 100%;
			height: 100%;
		}

		</style>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<?php
		     $modeDev = "";
            if(base_url() == "https://varshadigital.id/pos/")
            {
                $modeDev = "Dev";
            }
            
            echo "<title>".$modeDev." POSinaja | Log in</title>";
		?>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/AdminLTE.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

		<link rel="shortcut icon" href="assets/images/icon.png">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
			<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->
			
			<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<body class="hold-transition login-page" style="background-image:url('<?php echo base_url(); ?>assets/images/background.jpg');background-repeat: no-repeat; background-position: center; background-size: cover;">
	<?php
	if (isset($errMsg)) {
		echo '<script>alert(\''.$errMsg.'\')</script>';
	}
	?>

	<div class="login-box" style="margin: 0 auto;transform: translateY(calc((100vh - 100%) / 2));">
		<!-- /.login-logo -->
		<div class="login-box-body"  style="margin-top:-30px;">
			<center><img src='<?php echo base_url(); ?>assets/images/logo.jpg' height="180px"></center>
			<div class="form-group has-feedback" style="margin-top:-30px;">
				<input type="text" id="username" name="username" class="form-control" placeholder="Username">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" id="password" name="password" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<br>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
					<!--<label>
					<input type="checkbox"> Remember Me
					</label>
					!-->
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button id="btn_sign_in" class="btn btn-primary btn-block btn-flat" style="background:#448bf9;">Sign In</button>
				</div>
				<!-- /.col -->
			</div>
		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->
	
	<!-- jQuery 3 -->
	<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
	<!-- SWAL -->
	<script src="<?php echo base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
	<script>
	$('#password').keyup(function(e){
		if(e.keyCode == 13)
		{
			var email = $("#email").val();
			var password = $("#password").val();
			
			if(email != "" && password != "")
			{
				login();
			}
			else
			{
					Swal.fire({
						title: "Email dan Password harus diisi",
						type: 'error',
						showConfirmButton: false,
						timer: 15000000000
					});
			}
		}
	});
	
	$("#btn_sign_in").click(function(){
		login();
	});
	
	function login(){
		$.ajax({
			type    : 'POST',
			url     : '<?php echo base_url(); ?>auth/Login/cekLogin/',
			data    : {
				username:$("#username").val(), 
				password:$("#password").val(),
			},
			dataType: 'json',
			success : function(msg){
				if (msg.message) {
					Swal.fire({
						title            : msg.message,
						type             : 'error',
						showConfirmButton: false,
						timer            : 15000000000
					});
				} else {	
					Swal.fire({
						title            : msg.success,
						type             : 'success',
						showConfirmButton: false,
						timer            : 1000
					}).then(function() {
						window.location.replace('<?php echo base_url(); ?>home');
					});
				}			
				$('#KODEMEMBER').val("");	
			}
		});
		
	}
	
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' /* optional */
		});
	});
	</script>
	</body>
</html>