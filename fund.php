<?php
    require_once ("includes/sidebar.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" media="screen and (min-width: 1000px)"href="css/mod/main.css">
    <link rel="stylesheet" media="screen and (max-width: 1000px)" href="css/mod/smallMain.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <title>Fund Manager Home</title>
    <form action="fund.php" method="post">

</head>
<body>
    <div class="header">
          <div class="griditem"></div>
        <div class="griditem" id="headerText"><h1>FundManager</h1></div>
        
        <div class="griditem">
            <input type="text" placeholder="Ticker Symbol.." id="searchField">
        </div>
        <div class="griditem">
            <button type="button" class="btn" id="searchButton">Search</button>
        </div>
        
        <div class="griditem">LogOut</div>

    </div>

    <div class="userNameBar">
                <h1>Rugby VM Fond</h1>

    </div>

    <div class="container">
        <div class="griditem"></div>
        <div class="sideBar">
                    <h2>Username</h2>
                    <input type="submit" name="myPage" value="My Page">
                    <input type="submit" name="fundPage" value="My Fund" style="background-color:#86c3ff;  color:white;">
                    <input type="submit" name="settingsPage" value="Settings">
                    <input type="submit" name="aboutPage" value="About">
                    <input type="submit" name="logout" value="Logout">


        </div>
        <div class="fundBox">
            <div class="fundBoxTitle">
                <h2 class="siteItem" id="fundNameTitle">Name</h2>
                <h2 class="siteItem" id="userNameTitle">Stocks</h2>
                <h2 class="siteItem" id="totalValueTitle">30 000;-</h2>
            </div>
            <section>

            </section>

            <section>
                <div class="users">
                    <p class="memberID"></p>
                    <h2 class="userName">Christoffer</h2>
                    <div class="stocks" id="user1">
                        <p class="stockItem">STK</p>
                        <p class="stockItem">APL</p>
                        <p class="stockItem">SAM</p>
                        <p class="stockItem">SAS</p>
                    </div>
                    <h2 class="totalValue">TotVal();</h2>
                </div>            
            </section>
            <section>
                <div class="users">
                    <p class="memberID"></p>
                    <h2 class="userName">Sebastian</h2>
                    <div class="stocks" id="user2">
                        <p class="stockItem">STK</p>
                        <p class="stockItem">APL</p>
                        <p class="stockItem">SAM</p>
                        <p class="stockItem">SAS</p>
                    </div>
                    <h2 class="totalValue">TotVal();</h2>
                </div>            
            </section>
            <section>
                <div class="users">
                    <p class="memberID"></p>
                    <h2 class="userName">Jonas</h2>
                    <div class="stocks" id="user3">
                        <p class="stockItem">STK</p>
                        <p class="stockItem">APL</p>
                        <p class="stockItem">SAM</p>
                    </div>
                    <h2 class="totalValue">TotVal();</h2>
                </div>
            </section>
        
        
        </div> <!-- End of fundBox-->
        <div class="griditem"></div>


    </div><!--End of container-->
    

    <script src="js/main.js"></script> <!-- NOTE: Imports main.js into HTML , Has to be below items.-->
</body>

</html>