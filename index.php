<?php
    session_start();
    $userArray = [];
    $_SESSION['ticker'] = $_POST['sendTicker'];
    
    require_once("includes/sidebar.php");
    require_once("includes/search.php");
    //  require_once ("includes/authUser.php");
    if($_SESSION['loggedIn'] == FALSE)
    {
        header('Location: http://localhost/Project%20FundManager/login.php');
    }
    $email = $_SESSION['email'];


    if(isset ($_POST['sendTicker'] )){
        $_SESSION['ticker'] = $_POST['sendTicker'];
    }
    if(isset ($_POST['sendCompany'])){
        $_SESSION['company'] = $_POST['sendCompany'];
    }

    require_once ("includes/fetchUserInfo.php");
    updatePortfolioCapital($_SESSION['portfolioCapital']);
    
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
        header("Location: http://localhost/Project%20FundManager/stock.php?search=$searchInput");
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
    
    function updatePortfolioCapital($portfolioValue){
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');
        //  $_SESSION['SQL'] = $amount . " " . $ticker ;
        $email = $_SESSION['email'];

        $sql = "UPDATE `users` SET `portfolioCapital`='$portfolioValue' WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


        mysqli_close($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" media="screen and (min-width: 1000px)" href="css/mod/main.css">
    <link rel="stylesheet" media="screen and (max-width: 1000px)" href="css/mod/smallMain.css">
    <link rel="stylesheet" href="https://use.typekit.net/tay6xsi.css">
    <link href="pics/favicon.ico" rel="shortcut icon">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/firstTime.js"></script>
    <script type="text/javascript" src="js/eventListeners.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <title>My Page - FundManager</title>
    <form action="index.php" method="post">
    
</head>
<body>
<?php 
        calculateStock();
    ?>


    <div class="backgroundImage"></div>

    
        <div class="newSidebar"><!-- Sidebar Dropdown --> 
            <ul id="sideBar">
                <input type="submit" name="myPage" value="My Page" style="background-color:#333658;  color:white;">
                <input type="submit" name="fundPage" value="My Fund">
                <input type="submit" name="historyPage" value="History">
            </ul>
    
        </div>

        <div class="settingsMenu"> <!-- Settings Dropdown -->
            <ul class="settingsList">
                <input type="submit" name="settingsPage" value="Settings">
                <input type="submit" name="logout" value="Logout">
            </ul>
        </div>

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



        <div class="container">
        <?php printSQL(); ?>
            <section class="searchBar">
                <input type="text" placeholder="Company name.." name="searchField">
                 <input type="submit" name="searchButton" id="sBtn" value="Search">
            </section>
            <section class="portfolioInfo">

                <h2 id="capitalPortfolio">Portfolio Value: $<?php printPorfolioCapital();?>
                    <span class="percPort">

                    </span>
                </h2>
                <h2 id="capitalTrading">Trading Capital: $<?php printTradingCapital();?>

                </h2>
                <h2 id="capitalTotal">Total Capital: $<?php printTotalCapital();?>
                </h2>
            </section>

            <div class="loader"></div>

            <div class="fundBox">
                <div class="fundBoxTitle">
                </div>
                <section class="pieAndStats">

                    <div class="pieChart">
                        <canvas id="myPieChart" width="100px" height="100px"></canvas>
                        <script src="js/chart.js"></script>
                    </div>
                    <div class="stockListDiv"> 
                        <ul id="stockList">
                            
                            
                        </ul>
                    </div>

                </section>
                <?php printErrorMessage($_SESSION['error']); ?>
                <section style="padding:5% 0% 5% 0%;" >
                <div class="" id="stockRows">
                    </div>
                   
                <script>
                    calculatePortfolio(<?php echo json_encode($userArray); ?>); 
                </script>
                <!--
                    <div class="stockListLarge">
                        <div class="sll_Name"style="border-right:5px solid rgba(75, 192, 192, 0.8);">H&M</div>
                        <div class="sll_Percentage" id="total3" style="width:5%; background-color:rgba(75, 192, 192, 1);">5%</div>
                        <div class="sll_Value">$1000</div>
                        <div class="stats" style="display:none;">Stats</div>
                        <div class="stats" style="display:none;"><h1 style="padding:5%;">GRAPH</h1></div>
                        <div class="stats" style="display:none;">Stats</div>
                    </div>                        
                -->
                </section>
                
            </div>
        <div class="griditem"></div>
        
    </div>
    <div class="footer">Joakim Sjöquist Project</div>
    <script type="text/javascript" src="js/main.js"></script>



</body>
</html>