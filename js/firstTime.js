$("#setCapital").click(function (){
    

    $("#tradingCapital").text($("strartCapitalInput"));

   
});



$(document).ready(function(){



    var hidden = localStorage.getItem('status');

    $(".sll_Percentage").click(function(e){
        //$(".stockListLarge").append($newDiv);
        e.preventDefault();
        //$(".stats").toggle();
        $(this).parent().find(".stats").slideToggle();

    });
});
