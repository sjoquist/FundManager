<?php

    if(isset($_POST["sendPort"])){
        $_SESSION['portfolioCapital'] = $_POST['sendPort'];
    }

    if (isset($_POST["setCapital"])){
        //setCapital();
        
    }
    function setCapital(){
        $email = $_SESSION['email'];
            $_SESSION['tradingCapital'] = ($_POST["startCapitalInput"]);
            $_SESSION['portfolioCapital'] = 0;
            
            $validDouble = array("1","2","3","4","5","6","7","8","9","0",',','.');
            $msg = $_SESSION['totalCapital'];
            print_r($validDouble);
            $_SESSION['totalCapital'] = $_SESSION['tradingCapital'];
            $startCap = $_SESSION['totalCapital'];
            $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
            $dbConnected = mysqli_select_db($conn, 'fundmanager');  
            $sql = "UPDATE `users` SET `tradeCapital`='$startCap', `isVerified`= 1 , `firstCapital`='$startCap' WHERE `email` = '$email'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
            mysqli_close($conn);
            $_SESSION['isVerified'] = true; 
            $_SESSION['loggedIn'] = true; 
            header('Location: http://localhost/Project%20FundManager/index.php');
    } 

    function getCapital(){
        $email = $_SESSION['email'];
        $conn = mysqli_connect("localhost", "root", "") or die("Error connecting to mysql.");
        $dbConnected = mysqli_select_db($conn, 'fundmanager');  
        $sql = "SELECT *  FROM `users` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
        while($row = mysqli_fetch_assoc($result)){
            $_SESSION['firstCapital'] = $row['firstCapital'];  
            $_SESSION['tradingCapital'] = $row['tradeCapital'];    
            $_SESSION['portfolioCapital'] = $row['portfolioCapital'];
        }
        mysqli_close($conn);
        $_SESSION['totalCapital'] =  $_SESSION['tradingCapital'] +  $_SESSION['portfolioCapital'];
        
    }

    function getUserStocks(){

    }

    function printErrorMessage($string){
        echo $string;
    }
    
    function printTradingCapital(){

        if(strlen($_SESSION['tradingCapital'])>9){
            echo  "<label style='font-size:0.7em;'>" .  $_SESSION['tradingCapital'] . "</label>";
            
        }
        else{
            echo "<label>" . $_SESSION['tradingCapital'] . "</label>";
            
        }

    }
    function printPorfolioCapital(){
        if(strlen($_SESSION['portfolioCapital'])>7){
            echo  "<label style='font-size:0.7em;'>" . $_SESSION['portfolioCapital'] . "</label>";
        }
        else{
            echo "<label>" . $_SESSION['portfolioCapital'] . "</label>";
        }
    }

    function toDouble($string){
        return (double)$string;
    }

    function printTotalCapital(){
        $_SESSION['totalCapital'] = $_SESSION['portfolioCapital'] + $_SESSION['tradingCapital'];
        
        if(strlen($_SESSION['totalCapital'])>7){
            echo  "<label style='font-size:0.7em;'>" . $_SESSION['totalCapital'] . "</label>";
        }
        else{
            echo "<label>" . $_SESSION['totalCapital'] . "</label>";
        }


    }

    function printFirstName(){
        $name =   "<h2>" . $_SESSION['FirstName']. "</h2>";
        echo $name;
    }

    function printLastName(){
        $name = "<h3>" . $_SESSION['LastName'] . "</h3>";
        echo $name;
    }







?>