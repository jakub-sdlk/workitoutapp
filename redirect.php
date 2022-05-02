<?php


    if ( ($_SESSION and $_SESSION["userId"]) or ($_COOKIE and isset($_COOKIE["id"])) ) {

        header("Location: session2.php");
        exit;

        
  
    };

?>