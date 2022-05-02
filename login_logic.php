<?php 

  $logInEmail = "";
  $logInPassword = "";

  $emailErr = "";
  $passwordErr = "";

  function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  };
  
  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["logInEmail"])) {
      $emailErr = "Login Email is required. <br>";
    } else {
      $logInEmail = $_POST["logInEmail"];
      if (!filter_var($logInEmail, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid login email format. <br>";
      }
    }

    if (empty($_POST["logInPassword"])) {
      $passwordErr = "Login Password is required. <br>";
    } else {
      $logInPassword = $_POST["logInPassword"];
    }
  };

  if ($emailErr or $passwordErr) {
    return;
  }

  if ($logInEmail and $logInPassword) {

    /// In production, replace with the actual data!!!
    $link = mysqli_connect("localhost","my_user","my_password","my_db");

    if (mysqli_connect_error()) {
    
      die("there is an error!");
      return false;
    };

    $logInPassword = mysqli_real_escape_string($link, $logInPassword);
    $logInEmail = mysqli_real_escape_string($link, $logInEmail);

    $query = "SELECT `Email` FROM `users` WHERE `Email`= '$logInEmail'";
    $result = mysqli_query($link, $query);

    if ($result) {

      $row = mysqli_fetch_array($result);

      if (isset($row)) {

        $existingEmail = true;
        $query = "SELECT `Password` FROM users WHERE Email = '$logInEmail'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result)[0];

        if (password_verify($logInPassword, $row)) {
                    
          if (isset($_POST["logInCookieToggle"])) {
            $logInCookieToggle = $_POST["logInCookieToggle"];
                  
          } else {
            $logInCookieToggle ="";       
          };

          $query = "SELECT `UserId` FROM users WHERE Email = '$logInEmail'";
          if ($result = mysqli_query($link, $query)) {
            $row = mysqli_fetch_array($result);
            $userId = $row[0]; 
          }
                      
          if ($logInCookieToggle == "on") {
            $query = "SELECT `UserId` FROM users WHERE Email = '$logInEmail'";                 
            
            setcookie("id", "$userId", time() + (60 * 60 * 24 * 7));        
          }
                  
          $_SESSION['userId'] = $userId;
          header("Location: session2.php");
          exit;


        } else {
          $passwordErr = 'Invalid password.';
          return;
        }
            
      } else {
        $existingEmail = false;
      };
      
      if ($existingEmail == false) {

        $emailErr = "Could not find the email in the database. <br> Check for typoos or click on sign up button to join the team!";
        return;
      } 
    };
  };
?>