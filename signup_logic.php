<?php 

  $signInNewEmail = "";
  $signInNewPassword = "";

  $emailErr = "";
  $passwordErr = "";
  $firstNameErr = "";
  $lastNameErr = "";
  $alreadyRegisteredErr = "";

  function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  };

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["firstName"])) {
      $firstNameErr = "First name is required. <br>";
    } else {
      $firstName = validate_input($_POST["firstName"]);
    };

    if (empty($_POST["lastName"])) {
      $lastNameErr = "Last name is required. <br>";
    } else {
      $lastName = validate_input($_POST["lastName"]);    
    };

    if (empty($_POST["signInNewEmail"])) {
      $emailErr = "Email is required. <br>";
    } else {
      $signInNewEmail = $_POST["signInNewEmail"];
      if (!filter_var($signInNewEmail, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format. <br>";
      };
    };

    if (empty($_POST["signInNewPassword"])) {
      $passwordErr = "Password is required. <br>";
    } else {
      $signInNewPassword = $_POST["signInNewPassword"];
    }    
  };

  if ($emailErr or $passwordErr or $firstNameErr or $lastNameErr) {
    return;
  };

  if ($signInNewEmail and $signInNewPassword) {
  
    /// Create connection with the database

    $link = mysqli_connect("sdb-t.hosting.stackcp.net", "usersDb-323038746c", "opjfljdb7", "usersDb-323038746c");

    if (mysqli_connect_error()) {
    
      die("there is an error!");
      return false;
    };

    $escapedPassword = mysqli_real_escape_string($link, $signInNewPassword);
    $hashedPassword = password_hash($escapedPassword, PASSWORD_DEFAULT);
    $signInNewEmail = mysqli_real_escape_string($link, $signInNewEmail);
    $firstName =  mysqli_real_escape_string($link, $firstName);
    $lastName =  mysqli_real_escape_string($link, $lastName);

    $query = "SELECT `Email` FROM `users` WHERE `Email`= '$signInNewEmail'";
    $result = mysqli_query($link, $query);

    if ($result) {

      $row = mysqli_fetch_array($result);

      if (isset($row)) {

        $alreadyRegisteredErr = "The email is already registered.";
        return;
      };
    };
    
    $query = "INSERT INTO `users` (Email, Password, FirstName, LastName)
              VALUES ('$signInNewEmail', '$hashedPassword', '$firstName', '$lastName')";
    $result = mysqli_query($link, $query);

    if (isset($_POST["signInCookieToggle"])) {
      $signInCookieToggle = $_POST["signInCookieToggle"];

    } else {
      $signInCookieToggle ="";
    };

    $query = "SELECT `UserId` FROM users WHERE Email = '$signInNewEmail'";

    if ($result = mysqli_query($link, $query)) {
      $row = mysqli_fetch_array($result);
      $userId = $row[0];
    };
    
    if ($signInCookieToggle == "on") {
      $query = "SELECT `UserId` FROM users WHERE Email = '$signInNewEmail'";

      setcookie("id", "$userId", time() + (60 * 60 * 24 * 7));
    };

    $_SESSION['userId'] = $userId;
    header("Location:session2.php");
    exit;
  };
?>