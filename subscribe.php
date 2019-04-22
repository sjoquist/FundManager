<?php
$msg = "";
session_start();
    function emailAvailable($requestEmail){
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');  
        $sql = "SELECT *  FROM `users` WHERE `email` = '$requestEmail'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
        while($row = mysqli_fetch_assoc($result)){
            $email = $row['email'];
        }
        if(is_null($email)==TRUE){
            return TRUE;
        }
        else{
            return FALSE;
        }
        mysqli_close($conn);

    }
    function printLine($msg){
        echo $msg;
    }


    if (isset($_POST["signup"])) 
    {
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager'); 
        $firstName = trim($_POST["fName"]);
        $lastName = trim($_POST["lName"]);
        $email= trim($_POST["email"]);
        $password= trim($_POST["passwd"]);
        $passwordConfirm= trim($_POST["passwdConfirm"]);

        if (emailAvailable($email)==FALSE){
            $msg = "Email is already in use.";
            
        }
        else{
            $sql = "INSERT INTO `users` ( `firstName`, `lastName`, `email`, `password`) 
            VALUES ('$firstName', '$lastName', '$email', '$password')";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
            $msg = "Account created!";
        }
        mysqli_close($conn);
        


    }
        if (isset($_POST["back"])) 
    {
        header('Location: http://localhost/Project%20FundManager/login.php');
    }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Laboration 3</title>
    <link rel="stylesheet" media="screen and (min-width: 900px)" href="css/subscribe/subMain.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <form action="subscribe.php" method="post">
        <!--Vet ej varför denna måste vara här -->
</head>

<body>


    <div class="subscribeBox">
    <h2>Sign up</h2>
    <?php printLine($msg); ?>
    <form>
        <p>First Name:</p>
        <input type="text" name="fName">
        <p>Last Name:</p>
        <input type="text" name="lName">
        <p>E-mail:</p>
        <input type="text" name="email">
        <p>Password:</p>
        <input type="password" name="passwd">
        <p>Confirm Password:</p>
        <input type="password" name="passwdConfirm">

        <input type="submit" name="signup" value="Sign up!">
        <input type="submit" name="back" value="Back">
    </form>
    <p id="footer">Joakim Sjöquist - Projekt</p>
    </div>

</body>

</html>