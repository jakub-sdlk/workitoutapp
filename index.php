<?php

session_start();
include("redirect.php");
include("login_logic.php");

?>

<!doctype html>
<html lang="en">

<head>
	<title>Virtual Money Box</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body class="img js-fullwidth" style="background-image: url(images/workout_landing_1_small2.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6 text-center mb-5">
					<h2 class="heading-section">Virtual Money Box</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
						<h3 class="mb-4 text-center">Have an account?</h3>
						<div class="alert alert-danger d-none" id="errorDiv" role="alert"></div>

						<form method="post" class="signin-form">
							<div class="form-group">
								<input type="email" name="logInEmail" class="form-control" placeholder="Email" value="<?php if (isset($_POST["logInEmail"])) {echo $_POST["logInEmail"];}?>" title="Please insert your email in correct format, e.g. jarmil@gmail.com" required autocomplete="username email">
							</div>
							<div class="form-group">
								<input id="current-password" name="logInPassword" type="password" class="form-control" placeholder="Password" title="Choose your password" required autocomplete="current-password">
								<span toggle="#current-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary submit px-3">Log In</button>
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50">
									<label class="checkbox-wrap checkbox-primary">Remember Me
										<input type="checkbox" name="logInCookieToggle" checked>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</form>
						<p class="w-100 text-center">&mdash; Not a member? &mdash;</p>
						<div class="social d-flex text-center">
							<a href="sign_up_page.php" class="px-2 py-2 mr-md-1 rounded"><span class="icon-logo-facebook mr-2"></span>Sign up!</a>

						</div>
					</div>
				</div>
			</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

	<script type="text/javascript">
		var emailErr = "<?php echo "$emailErr" ?>";
		var passwordErr = "<?php echo "$passwordErr" ?>";
		var emailErr = "<?php echo "$emailErr" ?>";
		var passwordErr = "<?php echo "$passwordErr" ?>";
	
		if (emailErr != "" || passwordErr != "") {

			document.getElementById("errorDiv").classList.remove("d-none");
			document.getElementById("errorDiv").innerHTML = emailErr + passwordErr;
		}

	</script>
</body>
</html>