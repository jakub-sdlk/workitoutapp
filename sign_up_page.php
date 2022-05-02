<?php 

  session_start();

  include("redirect.php");
  include("signup_logic.php");

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
						<h3 class="mb-4 text-center">Join the crew!</h3>

						<div class="alert alert-danger d-none" id="errorDiv" role="alert"></div>

						<form method="post" class="signin-form" >
							<div class="form-group">
								<input type="text" name="firstName" class="form-control" value="<?php if (isset($_POST["firstName"])) {echo $_POST["firstName"];}?>" placeholder="First Name" title="First name without diacritics, letters only" required>
							</div>
							<div class="form-group">
								<input type="text" name="lastName" class="form-control" value="<?php if (isset($_POST["lastName"])) {echo $_POST["lastName"];}?>" placeholder="Last Name"  title="Last name without diacritics, letters only" required>
							</div>
							<div class="form-group">
								<input type="email" name="signInNewEmail" class="form-control" value="<?php if (isset($_POST["signInNewEmail"])) {echo $_POST["signInNewEmail"];}?>" placeholder="Email" title="Please insert your email in correct format, e.g. jarmil@gmail.com" required>
							</div>
							<div class="form-group">
								<input id="password-field" name="signInNewPassword" type="password" class="form-control" placeholder="Password"
									title="Choose your password" required>
								<span toggle="#password-field"
									class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary submit px-3">Sign up</button>
							</div>

							

							
							<div class="form-group d-md-flex">
								<div class="w-50">
									<label class="checkbox-wrap checkbox-primary">Remember Me
										<input type="checkbox" name="signInCookieToggle" checked>
										<span class="checkmark"></span>
									</label>
								</div>

							</div>
						</form>
						<p class="w-100 text-center">&mdash; Already a member? &mdash;</p>
						<div class="social d-flex text-center">
							<a href="index.php" class="px-2 py-2 mr-md-1 rounded"><span
									class="icon-logo-facebook mr-2"></span>Log in!</a>

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
		var firstNameErr = "<?php echo "$firstNameErr" ?>";
		var lastNameErr = "<?php echo "$lastNameErr"?>";
		var alreadyRegisteredErr = "<?php echo "$alreadyRegisteredErr"?>";
	
		if (emailErr != "" || passwordErr != "" || firstNameErr != "" || lastNameErr != "" || alreadyRegisteredErr != "")  {

			document.getElementById("errorDiv").classList.remove("d-none");
			document.getElementById("errorDiv").innerHTML = emailErr + passwordErr + firstNameErr + lastNameErr + alreadyRegisteredErr;
		}

	</script>

</body>

</html>