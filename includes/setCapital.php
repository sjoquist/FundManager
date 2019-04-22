<?php
    if (isset($_POST["setCapital"])){
        echo "Here";
        $startCapital = trim($_POST["startCapitalInput"]);
        $_SESSION['totalCapital'] = $startCapital;
        echo $startCapital;

        //$conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        //$dbConnected = mysqli_select_db($conn, 'fundmanager');  
    } 



?>