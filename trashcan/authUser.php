<?php 
    if($_SESSION['loggedIn'] == FALSE)
    {
        header('Location: http://localhost/Project%20FundManager/login.php');
    }
?>