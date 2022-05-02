<?php

session_start();

$welcomeMsg = "";
$loggedIn = false;
$workoutsDone = "";
$workoutsTotal = "";
$moneyEarned = "";
$lastWorkoutDate = "";
$lastWorkoutStatus = "";

$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['logout'])) {

  setcookie("id", "", time() - 3600);
  $_SESSION['userId'] = "";

  header("Location: index.php");
  exit;
};

if ($_SESSION and $_SESSION['userId']) {

  $_POST['UserId'] = $_SESSION['userId'];
  $loggedIn = true;
} else if ($_COOKIE and  isset($_COOKIE["id"])) {

  $_POST['UserId'] = $_COOKIE["id"];
  $loggedIn = true;
} else {

  header("Location: index.php");
  exit;
};

if ($loggedIn) {

  /// In production, replace with the actual data!!!
  $link = mysqli_connect("localhost","my_user","my_password","my_db");
  
  $userId = $_POST['UserId'];

  if (mysqli_connect_error()) {

    die("there is an error!");
    return false;
  } else {
    $query = "SELECT FirstName FROM `users` WHERE UserId = $userId";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $firstName = $row['FirstName'];

    $welcomeMsg = "Welcome, " . $firstName . "!";
  };

  // Retrieve the workouts done number

  $query = "SELECT COUNT(EntryId) as WorkoutsDone FROM `entries` WHERE UserId = $userId and TypeId > 0 ";
  $result = mysqli_query($link, $query);

  if ($result) {

    $row = mysqli_fetch_array($result);
    $workoutsDone = $row['WorkoutsDone'];
  };

  // Retrieve the workouts total number

  $query = "SELECT COUNT(EntryId) as WorkoutsTotal FROM `entries` WHERE UserId = $userId";
  $result = mysqli_query($link, $query);

  if ($result) {

    $row = mysqli_fetch_array($result);
    $workoutsTotal = $row['WorkoutsTotal'];
  };
  // Retrieve total money earned

  $query = "SELECT SUM(types.Earnings) as MoneyEarned FROM entries 
                INNER JOIN types ON entries.TypeId = types.TypeId WHERE UserId = $userId";
  $result = mysqli_query($link, $query);

  if ($result) {

    $row = mysqli_fetch_array($result);

    if (isset($row['MoneyEarned'])) {

      $moneyEarned = $row['MoneyEarned'];
    } else {

      $moneyEarned = 0;
    };
  };

  // Retrieve last workout date and status
  $query = "SELECT entries.Date, types.Status, entries.TypeId FROM entries 
                INNER JOIN types ON entries.TypeId = types.TypeId WHERE entries.UserId = $userId ORDER BY entries.EntryId DESC LIMIT 1";

  $result = mysqli_query($link, $query);


  if ($result) {

    $row = mysqli_fetch_array($result);

    if (isset($row)) {
      $lastWorkoutDate = $row['Date'];
      $lastWorkoutStatus = $row['Status'];
      $lastWorkoutTypeId = $row['TypeId'];
    } else {
      $lastWorkoutDate = "NA";
      $lastWorkoutStatus = "NA";
      $lastWorkoutTypeId = 1;
    }
  };

  // Logic for entering data into database
  if (isset($_POST['calendar']) and isset($_POST['yesOrNoOptions'])) {

    $newWorkoutDate = $_POST['calendar'];
    $newWorkoutTypeId = $_POST['yesOrNoOptions'];

    // Check uniqness of the date for the user

    $query = "SELECT `EntryId` FROM `entries` WHERE `Date` = '$newWorkoutDate' and `UserId` = $userId";
    $result = mysqli_query($link, $query);

    if ($result) {

      $row = mysqli_fetch_array($result);

      if (isset($row)) {

        $errorMsg = "The workout record for this date is already set.";
      }
    }


    // Calculate newWorkoutTypeId here

    if ($newWorkoutTypeId) {
      switch ($lastWorkoutTypeId) {
        case 0:
          $newWorkoutTypeId = 0.25;
          break;
        case 0.25:
          $newWorkoutTypeId = 0.5;
          break;
        case 0.5:
          $newWorkoutTypeId = 0.75;
          break;
        case 0.75:
          $newWorkoutTypeId = 1;
          break;
        case 1:
          $newWorkoutTypeId = 1;
          break;
        default:
          $errorMsg = "Workout type could not be determined";
          return;
      }
    }

    if (!$errorMsg) {

      $query = "INSERT INTO `entries`(`Date`, `UserId`, `TypeId`) VALUES ('$newWorkoutDate','$userId','$newWorkoutTypeId')";
      $result = mysqli_query($link, $query);

      header("Location: session2.php");
    }
  }
};
?>


<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <title>

    Overview

  </title>


  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <link href="Session/docs/assets/css/toolkit-inverse.css" rel="stylesheet">

  <link href="Session/docs/assets/css/application.css" rel="stylesheet">


</head>


<body>
  <div class="container">
    <div class="row">
      <div class="col-md-2 content"></div>
      <div class="col-md-8 content">
        <div class="dashhead">
          <div class="dashhead-titles">

            <h2 class="dashhead-title" id="welcomeMsg"></h2>

          </div>
        </div>

        <div class="mt-3 alert alert-danger alert-dismissible fade show d-none" id="errorDiv" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="hr-divider mt-5 mb-3">
          <h2 class="hr-divider-content hr-divider-heading">Your activity</h2>
        </div>

        <div class="row">
          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0 ">

            <button type="button" class="btn btn-info btn-lg btn-block" href="#docsModal" data-toggle="modal">Add
              Workout Record</button>
          </div>

          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0 ">
            <form method="post">
              <button type="sumbit" name="logout" class="btn btn-secondary btn-lg btn-block">Log Out</button>
            </form>
          </div>
        </div>

        <div class="hr-divider mt-3 mb-3">
          <h2 class="hr-divider-content hr-divider-heading">Your stats</h2>
        </div>

        <div class="row statcards">
          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0">
            <div class="statcard statcard-success">
              <div class="p-3">
                <span class="statcard-desc">Workouts done / total</span>
                <h2 class="statcard-number" id="workoutsDone">
                  0
                </h2>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0">
            <div class="statcard statcard-primary">
              <div class="p-3">
                <span class="statcard-desc">Money earned</span>
                <h2 class="statcard-number" id="moneyEarned">
                  0 CZK
                </h2>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0">
            <div class="statcard statcard-secondary">
              <div class="p-3">
                <span class="statcard-desc">Last workout date</span>
                <h2 class="statcard-number" id="lastWorkoutDate">
                  NA
                </h2>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6 mb-3 mb-md-4 mb-xl-0">
            <div class="statcard statcard-secondary">
              <div class="p-3">
                <span class="statcard-desc">Last workout status</span>
                <h2 class="statcard-number" id="lastWorkoutStatus">
                  NA
                </h2>
              </div>
            </div>
          </div>
        </div>

        <div id="docsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Workout Record</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>

              <div class="modal-body">

                <div class="container">
                  <form method="post" method>

                    <div class="form-group row mt-2">
                      <label for="calendar" class="col-sm col-form-label">Please enter sheduled workout date:</label>
                      <div class="col-sm">
                        <div class="btn-toolbar-item input-with-icon">
                          <input type="text" id="calendar" name="calendar" data-date-format="yyyy/mm/dd" class="form-control" value="0" data-provide="datepicker">
                          <span class="icon icon-calendar"></span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row mt-2">
                      <label for="yesOrNoOptions" class="col-sm col-form-label">Have you done that workout?</label>
                      <div class="col-sm">
                        <select class="form-control" id="yesOrNoOptions" name="yesOrNoOptions" aria-label="Default select example">
                          <option selected value="1">Yes, I have</option>
                          <option value="0">No, I have not</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row mt-2">
                      <div class="col-sm text-right">
                        <button type="submit" onClick="window.location.href=window.location.href" class="btn btn-primary">Save & Quit</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="Session/docs/assets/js/jquery.min.js"></script>
    <script src="Session/docs/assets/js/tether.min.js"></script>
    <script src="Session/docs/assets/js/chart.js"></script>
    <script src="Session/docs/assets/js/tablesorter.min.js"></script>
    <script src="Session/docs/assets/js/toolkit.js"></script>
    <script src="Session/docs/assets/js/application.js"></script>
    <script>
      // execute/clear BS loaders for docs
      $(function() {
        while (window.BS && window.BS.loader && window.BS.loader.length) {
          (window.BS.loader.pop())()
        }
      })
    </script>

    <script type="text/javascript">
      var welcomeMsg = "<?php echo "$welcomeMsg" ?>";
      var workoutsDone = "<?php echo "$workoutsDone" ?>";
      var workoutsTotal = "<?php echo "$workoutsTotal" ?>";
      var moneyEarned = "<?php echo "$moneyEarned" ?>";
      var lastWorkoutDate = "<?php echo "$lastWorkoutDate" ?>";
      var lastWorkoutStatus = "<?php echo "$lastWorkoutStatus" ?>";
      var errorMsg = "<?php echo "$errorMsg" ?>";


      document.getElementById("welcomeMsg").innerHTML = welcomeMsg;
      document.getElementById("workoutsDone").innerHTML = workoutsDone + " / " + workoutsTotal;
      document.getElementById("moneyEarned").innerHTML = moneyEarned + " CZK";
      document.getElementById("lastWorkoutDate").innerHTML = lastWorkoutDate;
      document.getElementById("lastWorkoutStatus").innerHTML = lastWorkoutStatus;

      if (errorMsg != "") {

        document.getElementById("errorDiv").classList.remove("d-none");
        document.getElementById("errorDiv").innerHTML = errorMsg;
      }

      // setting right date to the calendar value //
      var today = new Date();

      var year = today.getFullYear();
      var month = today.getMonth() + 1;
      var day = today.getDate();

      if (month < 10) {
        month = "0" + month
      };
      if (day < 10) {
        day = "0" + day
      };

      var date = `${year}/${month}/${day}`;

      document.getElementById("calendar").value = date;
    </script>


    <script type="text/javascript">
      $('#calendar').datepicker({
        autoclose: 'true',
        todayHighlight: 'true',
        disableTouchKeyboard: 'true',
        endDate: '0d',
        startDate: '-90d'

      });
    </script>

    <script>
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    </script>
</body>

</html>