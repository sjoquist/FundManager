<?php

  if (isset($_POST["myPage"])) 
  {
    header('Location: http://localhost/Project%20FundManager/index.php');
  }

  if (isset($_POST["fundPage"])) 
  {
    header('Location: http://localhost/Project%20FundManager/fund.php');
  }

  if (isset($_POST["settingsPage"])) 
  {
    header('Location: http://localhost/Project%20FundManager/index.php');
  }

  if (isset($_POST["logout"])) 
  {
    $_SESSION['loggedIn'] = FALSE;
    session_destroy();
    header('Location: http://localhost/Project%20FundManager/login.php');
  }



?>