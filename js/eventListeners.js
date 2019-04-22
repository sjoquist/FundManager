$(document).ready(function () {
    var $currentAmount = parseInt($("#getInventory").text());
    var $tradeCapital = parseFloat($("#getTradeCap").text(), 2);

    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            console.log("pressed enter");
            $("#sBtn").click();
            return false;
        }
    }

    $(".settings").click(function (e){
        $(".settingsMenu").slideToggle();
    });

    $(".logo").click(function (e) {
        $(".newSidebar").slideToggle();

    });


    $("#amount").on("change",function () {
        var $amount = parseInt(this.value, 10);
        var string = $("#price").text();
        var $price = parseFloat(string.slice(2), 2);




        var $total = parseFloat($amount * $price, 2);


        $("#totalPrice").val($total.toFixed(1));
        chAmountAndCap($total, $amount);


    });

    $("#amount").on("change", function () {
        var $amount = parseInt(this.value);
        var string = $("#price").text();
        var $price = parseFloat(string.slice(2), 2);




        var $total = parseFloat($amount * $price, 2);


        $("#totalPrice").val($total.toFixed(1));
        chAmountAndCap($total, $amount);


    });


    $( "#totalPrice" ).on("change",function() {
        var $totalPrice = parseFloat(this.value, 2);
        var string = $("#price").text();
        var $price = parseFloat(string.slice(2), 2);

        console.log("Current Capital: " + $tradeCapital);
        console.log("Current Price: " + $totalPrice);

        var $amount = $totalPrice / $price;
        $("#amount").val($amount.toFixed(1));
        chAmountAndCap($totalPrice, $amount);
        
    });

    $("#totalPrice").keyup( function () {
        var $totalPrice = parseFloat(this.value, 2);
        var string = $("#price").text();
        var $price = parseFloat(string.slice(2), 2);

        console.log("Current Capital: " + $tradeCapital);
        console.log("Current Price: " + $totalPrice);

        var $amount = $totalPrice / $price;

        $("#amount").val($amount.toFixed(1));
        chAmountAndCap($totalPrice,$amount);

    });
    

    function chAmountAndCap($totalPrice, $amount){
        console.log("Amount: " + $amount);
        $amount = $amount.toFixed(1);
        $amount2 = parseInt($amount);
        console.log("Amount2 : " + $amount2);

 
        if ($amount > $currentAmount) {
            $("#sell").css("pointer-events", "none");
            $("#sell").css("background-color", "grey");
        }
        else {
            $("#sell").css("pointer-events", "visible");
            $("#sell").css("background-color", "var(--button-color)");
        }
        if ($totalPrice > $tradeCapital) {
            $("#buy").css("pointer-events", "none");
            $("#buy").css("background-color", "grey");
        }
        else {
            $("#buy").css("pointer-events", "visible");
            $("#buy").css("background-color", "var(--button-color)");
        }
    }

    $("#buy").click(function (e) {
        $(".confirmTradeBg").show();

    });
});


