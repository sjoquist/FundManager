<?php

    $email= trim($_POST["email"]);
    $password= trim($_POST["password"]);

 
    echo sizeof($email);


    if( sizeof($email)==0 || sizeof($password)==0 ){
        $_SESSION['msg'] = "<p style='color:red; font-weight:600; text-align:center; margin:2%;'> Please fill required fields.</p> ";
        $_SESSION['loggedIn'] = FALSE;
        header('Location: http://localhost/Project%20FundManager/login.php');
    }

    $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
    $dbConnected = mysqli_select_db($conn, 'fundmanager');  
    $sql = "SELECT *  FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 


    if (mysqli_num_rows($result)==0){
        $_SESSION['msg'] = "<p style='color:red; font-weight:600; text-align:center; margin:2%;'> Wrong E-mail or Password.</p> ";
        $_SESSION['loggedIn'] = FALSE;
        header('Location: http://localhost/Project%20FundManager/login.php');
    }
    else {
        $_SESSION['email'] = $email;
        $_SESSION['loggedIn'] = TRUE;
        $_SESSION['msg'] = "Logged in" ." " . $_SESSION['loggedIn'];
        header('Location: http://localhost/Project%20FundManager/index.php');
    }
    mysqli_close($conn);
?>