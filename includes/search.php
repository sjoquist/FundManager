<?php

    function getNameAndTicker(){

    }

    function printCompanyName(){
        echo $_SESSION['stock'];
    }

    function printSQL(){

        if ($_SESSION['SQL'] == null){
            $_SESSION['SQL'] = " ";
        }
        else{
            echo $_SESSION['SQL'];
        }

    }

    function printTicker(){
        
        if($_SESSION['ticker'] == null){
            echo "error";
        }
        else{

            echo $_SESSION["ticker"];
        }



    }


?>
