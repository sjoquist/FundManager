<?php
    function buyStock($ticker,$amount){
        // increase to DB
        // put into history.
        // decTradeCapital($total);
    }
    function sellStock($ticker,$amount){
        // decrease from DB
        // put into history.
        // incTradeCapital($total);
    }
    function calcPortCapital(){
        // total = 0;
        // read each line
        // total += (amount * currentvalue);
    }

    function incTradeCapital($total){
        // Go into DB and increase TradeCap.
    }
    function decTradeCapital($total){
        // Go into DB and decrease TradeCap.
    }

?>