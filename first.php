<?php
    session_start();

    require("includes/fetchUserInfo.php");
    


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
    <form action="first.php" method="post">
</head>

<body>


    <div class="loginBox">
    <h1><img src="pics/logo.svg" height="20%" width="20%"alt="FundManager">Fund Manager</h1>
    <form>
        <p style="text-align:center; margin:0; font-size:1em;" >Welcome ! It seems as this is your first time using our service.
        <br> Before you start, you need to set a start capital.</p> <br>
    </form>
    <input type="text" placeholder="$USD" name="startCapitalInput"style="color:black;">
        <p style="font-size:0.7em; margin:0; text-align:center;">We recommend to start with atleast $10000.</p>
    <input type="submit" name="setCapital" value="Apply">
    <p id="footer" style="text-align: center; margin:5% 0%; font-size:1em;">Joakim Sjöquist <br> Web Developement Project</p>
    </div>

</body>

</html>