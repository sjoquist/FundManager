<?php
    session_start();

    if (isset($_POST["subscribe"])) 
    {
        header('Location: http://localhost/Project%20FundManager/subscribe.php');
    }

    if (isset($_POST["login"])) 
    {
        //require "includes/loginFunction.php";
        
        $email= trim($_POST["email"]);
        $password= trim($_POST["password"]);
        $_SESSION['email'] = $email;

        
        if(empty($email) || empty($password)){
            $_SESSION['msg'] = "<p style='color:red; font-weight:600; text-align:center; margin:2%;'> Please fill required fields.</p> ";
            $_SESSION['loggedIn'] = FALSE;
            $password=".";
            header('Location: http://localhost/Project%20FundManager/login.php');
        }
        else{
            $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
            $dbConnected = mysqli_select_db($conn, 'fundmanager');  
            $sql = "SELECT *  FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
            while($row = mysqli_fetch_assoc($result)){
                $returnPass = $row['password'];    
                $returnEmail = $row['email'];  
                $_SESSION['isVerified'] = $row['isVerified']; 
            }
            if (mysqli_num_rows($result)==0){
                $_SESSION['msg'] = "<p style='color:red; font-weight:600; text-align:center; margin:2%;'> Wrong E-mail or Password.</p> ";
                $_SESSION['loggedIn'] = FALSE;
                header('Location: http://localhost/Project%20FundManager/login.php');
            }
            mysqli_close($conn);
        }
        
        
        
        if($returnPass==$password){
            
            if($_SESSION['isVerified']==false){
                header('Location: http://localhost/Project%20FundManager/first.php');
            }
            else{
                $_SESSION['loggedIn'] = TRUE;
                $_SESSION['msg'] = "Logged in" ." " . $_SESSION['loggedIn'] . $_SESSION['email'];
                header('Location: http://localhost/Project%20FundManager/index.php');
            }
        }
        
    }

    function printLine(){
        $msg = $_SESSION['msg'];
        if(empty($msg)){
            echo " ";
        }
        else{
            echo $_SESSION['msg'];

        }

    }
    


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Laboration 3</title>
    <link rel="stylesheet" media="screen and (min-width: 900px)" href="css/login/largeLogin.css">
    <link rel="stylesheet" media="screen and (max-width: 900px)" href="css/login/smallLogin.css">
    <link rel="stylesheet" href="https://use.typekit.net/tay6xsi.css">
    <link href="pics/favicon.ico" rel="shortcut icon">
    <form action="login.php" method="post">
</head>

<body>


    <div class="loginBox">
    <h1><img src="pics/logo.svg" height="20%" width="20%"alt="FundManager">Fund Manager</h1>
    <form>
        <p>Email</p>
        <input type="text" name="email">
        <p>Password</p>
        <input type="password" name="password">
        <?php 
        @printLine(); 
        ?>
        <input type="submit" name="login" value="Login">
        <input type="submit" name="subscribe" value="Sign up">
    </form>
    <p id="footer" style="text-align: center; margin:5% 0%; font-size:1em;">Joakim Sjöquist <br> Web Developement Project</p>
    </div>

</body>

</html>