<?php
    /*
    Version 0.7;
    Joakim Sjöquist;
    22-04-2019;
    */
    
    session_start();
    
    
    $userArray = [];
    
    require_once("includes/sidebar.php");
    require_once("includes/search.php");
    //  require_once ("includes/authUser.php");
    if($_SESSION['loggedIn'] == FALSE)
    {
        header('Location: http://localhost/Project%20FundManager/login.php');
    }
    $email = $_SESSION['email'];
    $_SESSION['matched'] = false;
    
    if(isset ($_POST['sendTicker'] )){
        $_SESSION['ticker'] = $_POST['sendTicker'];
    }
    if(isset ($_POST['sendCompany'] )){
        $_SESSION['company'] = $_POST['sendCompany'];
    }
    if(isset ($_POST['sendPrice'] )){
        $_SESSION['price'] = $_POST['sendPrice'];
    }

    require_once ("includes/fetchUserInfo.php");
    getCapital();
    
    $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
    $dbConnected = mysqli_select_db($conn, 'fundmanager');  
    $sql = "SELECT *  FROM `users` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
    while($row = mysqli_fetch_assoc($result)){
        $firstName = $row['firstName'];    
        $lastName = $row['lastName'];    
    }

    $_SESSION['FirstName']= $firstName;
    $_SESSION['LastName']= $lastName;
    mysqli_close($conn);

    if (isset($_POST["searchButton"])){
        $searchInput = $_POST["searchField"];
        $_SESSION['stock'] = $searchInput;
        printErrorMessage($searchInput);
        if($searchInput==""){
            header("Location: http://localhost/Project%20FundManager/index.php");
        }
        else{
            header("Location: http://localhost/Project%20FundManager/stock.php?search=$searchInput");

        }
    }

    if(isset($_POST["cancel"])){
        header("Location: ". $_SERVER['PHP_SELF']);
    }

    if(isset($_POST["confirmPurchase"])){
        getCapital();
        $tradeCapital = $_SESSION['tradingCapital'];
        $totalCapital = $_SESSION['totalCapital'];
        $portolioCapital = $_SESSION['portfolioCapital'];
        $amount = $_SESSION['amt']; 
        $price = $_SESSION['totPrice'];
        $ticker = $_SESSION['ticker'];
        addPortfolio($ticker,$amount,$price);
        //decTradeCapital($total);
        //incPortCapital($total);

        header("Location: http://localhost/Project%20FundManager/index.php");
    }

    function addPortfolio($ticker,$amount,$price){
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');
        $_SESSION['SQL'] = "Got to Addportfolio:" . $amount . " " . $ticker;
        $email = $_SESSION['email'];
        // Get current value.
        $matched = $_SESSION['matched'];
        $sql = "SELECT *  FROM `usertostock` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
        if (mysqli_num_rows($result)==0){
            //$_SESSION['SQL'] = "No such stock in inventory";
            insertDB($amount,$ticker,$email);
            //$_SESSION['SQL'] += "First entry num_rows";
        } 
        else{
            //$_SESSION['SQL'] = "Got to 92 :" . $amount;
            while($row = mysqli_fetch_assoc($result)){
              //  $_SESSION['SQL'] = "Got to 93 :" . $amount;

                $tickerDB = $row['ticker'];
                $amountDB = $row['amount'];
                //$_SESSION['SQL'] = "Got to 99 :" . $amountDB . " " . $tickerDB;

                if($tickerDB == $ticker){
                    $_SESSION['matched'] = true;
                    continue;
                }
                else{
                    $_SESSION['matched'] = false;
                }
            }

            if($_SESSION['matched'] == true){
                $amount = $amountDB + $amount;
                updateDB($amount, $ticker, $email);
            }
            else{
                insertDB($amount, $ticker, $email);    
            }
            // Forced to break up, otherwise duplicates in DB. Fix later.
              //  $_SESSION['SQL'] = "103 :" . $amount;
            
        }
        decTradeCapital($_SESSION['totPrice']);
        mysqli_close($conn);
        

    }

    function incTradeCapital($total){

    }

    function calculateStock(){
        global $userArray;
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');
        $email = $_SESSION['email'];
        $sql = "SELECT *  FROM `usertostock` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
        while($row = mysqli_fetch_assoc($result)){
            $tickerDB = $row['ticker'];
            $amountDB = $row['amount'];
            array_push($userArray, $tickerDB);
            array_push($userArray, $amountDB);
        }
        
        $_SESSION['SQL'] = $userArray[0] . $userArray[1];
    }

    function updatePortfolio(){
    }

    function decTradeCapital($total){
        getCapital();
        $current = $_SESSION['tradingCapital'];
        $_SESSION['SQL'] = " DECTRADE Current Tradecap: ". $_SESSION['tradingCapital'];
        
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');
        $email = $_SESSION['email'] ;   
    
        $new = floatval($current-$total);
        $_SESSION['SQL'] = "New tradecap: ". $new;
        $sql = "UPDATE `users` SET `tradeCapital`='$new' WHERE `email`='$email'";
        //$sql = "UPDATE `users` SET `tradeCapital`= $new WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


        mysqli_close($conn);
    }


    function insertDB($amount,$ticker,$email){

        $_SESSION['SQL'] = "InsertDB amount :" . $amount . " and ticker". $ticker;

        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');

        $sql = "INSERT INTO `usertostock`(`email`, `ticker`, `amount`) VALUES ('$email','$ticker','$amount')";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        
        mysqli_close($conn);

        //Add to history.
    }

    function updateDB($amount, $ticker, $email){
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');
      //  $_SESSION['SQL'] = $amount . " " . $ticker ;


        $sql = "UPDATE `usertostock` SET `amount`='$amount' WHERE `ticker`='$ticker' AND `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


        mysqli_close($conn);
        //Add to history.
    }

    function printConfirmtable(){
        if(isset($_POST["buybtn"])){

            $amount = $_POST['amt']; 
            $price = $_POST['totPrice'];
            $price = number_format((float)$price, 2, '.', '');
            echo '<div class="confirmTradeBg">';
            echo '<div class="confirmTrade">';
            echo '<h1 style="color:white">Are you sure you want to buy <span style="color:#1ABC9C">'. $amount . '</span> shares of '.  $_SESSION['company']  .' for <span style="color:#1ABC9C">$' . $price. '</span> USD?</h1>';
            echo '<input type="submit" name="cancel" value="Cancel"> ';
            echo '<input type="submit" name="confirmPurchase" value="Confirm Purchase"> ';
            echo '</div>';
            echo '</div>';
            $_SESSION['totPrice'] = $price;
            $_SESSION['amt'] = $amount;



        }
        else{
            echo " ";
        }
        if(isset($_POST["sellbtn"])){

            $amount = $_POST['amt']; 
            $price = $_POST['totPrice'];
            $price = number_format((float)$price, 2, '.', '');
            $_SESSION['totPrice'] = $price;
            $_SESSION['amt'] = $amount;
            
            echo '<div class="confirmTradeBg">';
            echo '<div class="confirmTrade">';
            echo '<h1 style="color:white">Are you sure you want to sell <span style="color:#1ABC9C">'. $amount . '</span> shares of '.  $_SESSION['company'] .' for <span style="color:#1ABC9C">$' . $price. '</span> USD?</h1>';
            echo '<input type="submit" name="cancel" value="Cancel"> ';
            echo '<input type="submit" name="confirmSale" value="Confirm Sales"> ';
            echo '</div>';
            echo '</div>';
        }
        else{
            echo " ";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" media="screen and (min-width: 1000px)" href="css/mod/main.css">
    <link rel="stylesheet" media="screen and (max-width: 1000px)" href="css/mod/smallMain.css">
    <link rel="stylesheet" href="https://use.typekit.net/tay6xsi.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/firstTime.js"></script>
    <script type="text/javascript" src="js/eventListeners.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <title>Fund Manager Home</title>
    <form action="stock.php" method="post">
    
</head>
<body>
    <?php printConfirmtable();
        //calculateStock();
    ?>



    <script>
        /*function calculatePortfolio(userArray){
            
            var BASEURL = "https://api.iextrading.com/1.0";
            //var string;
            var ticker;
            var amount;
            var price;
            var portfolioValue = 0;
            var newprice = 0;
            for(var i=0; i<userArray.length; i++){
              //  string += userArray[i] + " ";
            

            
                if(i%2==0){
                    ticker = userArray[i];
                    amount = userArray[i+1];
                    console.log("ticker :" + ticker);
                    console.log("amount: " + amount);
                    var price2 = BASEURL + "/stock/" + ticker + "/price";
                    console.log(price2);
                    var price2XML   = new XMLHttpRequest();
                    // GET/POST, URL, async
                    price2XML.open("GET", price2, true);
                    price2XML.onreadystatechange = function () {

                        if (this.readyState == 4 && this.status == 200) {
                            var unparsedResponse = price2XML.responseText; // Skapa en variabel med texten vi får som response.
                            var parsedData = JSON.parse(unparsedResponse);
                            console.log(parsedData);
                            console.log("Return: " + parsedData);
                        }

                        

                    }
                    price2XML.send();

                    portfolioValue = (newprice*amount);
                    console.log("PORTFOLIO VALUE: " + portfolioValue)
                }
                
            /*
                var priceXML   = new XMLHttpRequest();
                // GET/POST, URL, async
                priceXML.open("GET", price, true);
                priceXML.onreadystatechange = function () {

                    if (this.readyState == 4 && this.status == 200) {
                        var unparsedResponse = priceXML.responseText; // Skapa en variabel med texten vi får som response.
                        var parsedData = JSON.parse(unparsedResponse);
                        price = parsedData;
                    }

                    

                }
                priceXML.send();
                portfolioValue += (price*amount);
                console.log("PORTFOLIO VALUE: " + portfolioValue);
                /*
                $.ajax({
                    url: window.location.href,
                    data: {sendPortfolio: },
                    method:'POST'
                })
                */


            }
        /*
            alert(portfolioValue);
            //alert(string);
        } 
        calculatePortfolio(<?php// echo json_encode($userArray); ?>); 
        */
    </script>
    
    <header class="header"> <!-- Start of Header -->
        <label class="logo"><!-- Logotype --> 
            <img src="pics/logo.svg" height="40px" width="80px" alt="FundManager">
        </label>
        <label class="homepage"> <!-- Name --> 
            <h3 id="logoName">FundManager</h3>
        </label>

        <div class="headerUserBox"><!-- User First & Last name --> 
            <?php
            printFirstName();
            printLastName();
            ?>

        </div>
        <div class="profilePicture"><!-- Profile Picture -->
            <img src="pics/UserImg.svg" height="50px" width="50px" alt="FundManager" style="margin:2%;">
        </div>
        <label class="settings"> <!-- Settings Button -->
            <img src="pics/iconSettings.svg"height="30px" width="30px" alt="Settings" style="margin:5%;">
        </label>
    </header> <!-- End of Header-->

    <div class="newSidebar"><!-- Sidebar Dropdown --> 
        <ul id="sideBar">
            
            <span class="sbNum">
                <h1 class="sidebarImg">image</h1>
                <p class="digit">01</p>
                <input type="submit" class="sidebarItem" name="myPage" value="DASHBOARD">
            </span>
            <span class="sbNum">
                <h1 class="sidebarImg">image</h1>
                <p class="digit">02</p>
                <input type="submit" class="sidebarItem" name="fundPage" value="FUND">
            </span>
            <span class="sbNum">
                <h1 class="sidebarImg">image</h1>
                <p class="digit">03</p>
                <input type="submit" class="sidebarItem" name="historyPage" value="HISTORY">
            </span>
        </ul>

    </div>
    <div class="settingsMenu"> <!-- Settings Dropdown -->
        <ul class="settingsList">
            <span class="sbNum">
                <h1 class="sidebarImg">image</h1>
                <p class="digit">01</p>
                <input type="submit" class="sidebarItem" name="settingsPage" value="Settings">
            </span>
            <span class="sbNum">
                <h1 class="sidebarImg">image</h1>
                <p class="digit">02</p>
                <input type="submit" name="logout" value="Logout">
            </span>
        </ul>
    </div>

    <div class="container">
        <?php printSQL(); ?>
        <section class="searchBar">
            <input type="text" placeholder="Company name.." name="searchField">
            <input type="submit" name="searchButton" id="sBtn" value="Search">
        </section>
        <section class="portfolioInfo">
            
            <div id="capitalPortfolio">
                <p style='color: rgb(77, 77, 95);'>Portfolio Value ($USD):</p>
                <h2 id="getPortCap"><?php printPorfolioCapital();?></h2>
            </div>
            <div id="capitalTrading">
                <p style='color: rgb(77, 77, 95);'>Trading Capital ($USD):</p>
                <h2 id="getTradeCap"><?php printTradingCapital();?></h2>
            </div>
            <div id="capitalTotal">
                <p style='color: rgb(77, 77, 95);'>Total Capital ($USD):</p>
                <h2 id="getTotalCap"><?php printTotalCapital();?></h2>
            </div>
        </section>
        <div class="errorBox">
            <h1 style="color:var(--error); font-size:2.2em;">Sorry,</h1>
            <h1 style="color:white;">We could not find anything on that search.</h1>
            <h2 style="color:white;">Please try again.</h2>
            <img src="pics/logo.svg" height="40px" width="80px" style="padding: 15px;" alt="FundManager">

        </div>
        <div class="loader"></div>
        <div class="fundBox">

            <div class="fundBoxTitle">
                <div id="name">
                    <p style='color: rgb(77, 77, 95);'>Company</p>
                    <h2 id="companyName" name="currentCompany"><?php printCompanyName() ?></h2>
                    
                </div>
                <div id="logo"></div>
                <div id="ceo"></div>
                <div id="industry"></div>
            </div>


            <div id="exchangeAndTicker"></div>



            <section class="pieAndStats">

                <section class="prices">
                    <h2 id="price"></h1>
                    <h2 id="currentHolding">Current inventory: <span id="getInventory">0</span></h2>
                    <p id="open"></p>
                    <p id="close"></p>
                    <p id="tagAmount">Amount #</p>
                    <p id="tagTotalPrice">Total Price $</p>

                        <input type="number" name="amt" id="amount" min=0 step="1">
                        <input type="number" name="totPrice" id="totalPrice" max="<?php printTradingCapital();?>" step="0.01">
                    <input type="submit" id="buy" name="buybtn"class="btn" value="Buy">
                    <input type="submit" id="sell" name="sellbtn" class="btn" value="Sell">
                    <!--<input type="submit" value="Confirm" id="confirmAction">-->
                </section>

                <section class="sChart">
                    <p id="today">Today</p>
                    <p id="week">Week</p>
                    <p id="month">Month</p>
                    <p id="year">1y</p>
                    <p id="five">5y</p>
                    <canvas id="stockChart1" ><script src="js/stockchart.js"></script>
                </section>

            </section>

        
            <h2 class="dText" style="color: rgb(77, 77, 95);">Description</h2>
            <div class="stockList">

                <p id="description"></p>
                <div class="dImg"></div>
            </div>

        </div>
    </div>

        

    <footer class="footer">Joakim Sjöquist Project</footer>
    <script type="text/javascript" src="js/main.js"></script>


</body>
</html>