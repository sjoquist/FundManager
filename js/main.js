"use strict";

var BASEURL = "https://api.iextrading.com/1.0";
var infoArray;
var globalTimeout = null;
var globalSearch;
var $globalTicker;
var yearArray = [];
var $maxValue = 0;
var $currentPrice = 0;

function readInput(){
    var input = document.getElementById("searchField").value;
    return input;
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

$(document).ready(function () {
    $(".loader").show();
    $(".errorBox").hide();
    $(".fundBox").hide();
    var $companyName = $("#companyName").text();
    console.log($companyName);
    searchCompany($companyName);


    


});
/*
document.getElementById("searchField").addEventListener("keyup", function(){
    if (globalTimeout != null) {
        clearTimeout(globalTimeout);
    }
    globalTimeout = setTimeout(function () {
        globalTimeout = null;
        searchCompany(document.getElementById("searchField").value);
    }, 200); 
    console.log(globalSearch);
});

*/


function calculatePortfolio(userArray){
        
} 



function firstCharUpperCase(string) {
    string = string.toLowerCase();
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function searchCompany(searchInput){
    var searchCompanyName = "https://api.iextrading.com/1.0/ref-data/symbols";
    var searchXML = new XMLHttpRequest();
    var $ticker, $companyName;
    searchXML.open("GET", searchCompanyName, true);

    searchInput =  firstCharUpperCase(searchInput);

    if(searchInput == "Google"){
        searchInput ="Alphabet";
    }

    searchXML.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = searchXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);
            for (var i = 0; i < parsedData.length; i++) {

                if (parsedData[i].name.indexOf(searchInput) > -1) {
                    $ticker = parsedData[i].symbol;
                    $companyName = parsedData[i].name;
                    break;
                }
                
                
                
            }

            if(($ticker||$companyName)==undefined){
                $(".loader").hide();
                $(".errorBox").show();
                
            }
            else{
                //$("#companyName").html($companyName);  
                console.log("Ticker to be sent to ajax : " + $ticker);
            
                $.ajax({
                    url: window.location.href,
                    data: { sendTicker: $ticker},
                    method:'POST',
                });
                $.ajax({
                    url: window.location.href,
                    data: { sendCompany: $companyName },
                    method: 'POST',
                });

                                

                searchTicker($ticker);
            }
            
            
        }
    };
    searchXML.send();



}




/*
document.getElementById("searchButton").addEventListener("click", function(){
   

    if (globalSearch==undefined){
        console.log("No result from search");
    }
    else{

        searchTicker(globalSearch);
        
    }

});


*/




function searchTicker(tickerSymbol){
    console.log("SearchingonTicker");
    
    var searchCompany = BASEURL + "/stock/" + tickerSymbol + "/company";
    var openClose = BASEURL + "/stock/" + tickerSymbol + "/ohlc";
   // var logotype = "https://storage.googleapis.com/iex/api/logos/" + tickerSymbol + ".png";
    var logotype= BASEURL + "/stock/" + tickerSymbol +"/logo";
    var price = BASEURL + "/stock/" + tickerSymbol + "/price";
    console.log(searchCompany);
    console.log(tickerSymbol);


    var searchXML = new XMLHttpRequest();
    


    // GET/POST, URL, async
    searchXML.open("GET", searchCompany, true);

    //När lanXML har laddat färdigt.
    searchXML.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = searchXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);

            $("#companyName").html(parsedData.companyName);
            $("#industry").html("<p style='color: rgb(77, 77, 95);'>Industry</p>" + "<h2>" + parsedData.industry + "</h2>" );
            $("#description").html(parsedData.description);
            $("#ceo").html("<p style='color: rgb(77, 77, 95);'>CEO</p>" + "<h2>" + parsedData.CEO + "</h2>");
            $("#exchangeAndTicker").html("<p>" + parsedData.exchange + ": <span id='tickerSymbol'>" + parsedData.symbol + "</span> </p>");
            

        }


    };
    // Måste skicka för att fungera.
    searchXML.send();

    
    var opencloseXML = new XMLHttpRequest();

    // GET/POST, URL, async
    opencloseXML.open("GET", openClose, true);

    //När lanXML har laddat färdigt.
    opencloseXML.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = opencloseXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);

            $("#open").html("Open: $" + parsedData.open.price);
            $("#close").html("Close: $" + parsedData.close.price); 


        }


    };

    // Måste skicka för att fungera.
    opencloseXML.send();
    
 
    var priceXML   = new XMLHttpRequest();

    // GET/POST, URL, async
    priceXML.open("GET", price, true);
    priceXML.onreadystatechange = function () {
    
        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = priceXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);
            $currentPrice += parsedData;
            $("#price").html("$ " + parsedData);
            
        }
        
        $.ajax({
            url: window.location.href,
            data: {sendPrice: parsedData},
            method:'POST'
        })
    }
    priceXML.send();
    
    chartXML(tickerSymbol);
    // HTTP Request LOGOTYPE.
    var logoXML = new XMLHttpRequest();
    logoXML.open("GET", logotype, true);

    logoXML.onreadystatechange = function () {
        
        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = logoXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);
            var logo = parsedData.url;
            //$('<img />').attr('src', "" + logo + "").width('113px').height('113px').id("logo").appendTo($('.dImg'));       // ADD THE IMAGE TO DIV.
            //$('#logo').css("border-radius", "10px");

            var e = $('<img src="'+logo+'" style=" border-radius:10px; max-height:90%; margin:auto;">');
            $("#logo").append(e);

        }
    }
    logoXML.send();
    $(".loader").hide();
    $(".fundBox").show();
}

function chartXML(tickerSymbol){
    var yearly = BASEURL + "/stock/" + tickerSymbol + "/chart/1y";
    var yearlyXML = new XMLHttpRequest();

    // GET/POST, URL, async
    yearlyXML.open("GET", yearly, true);

    //När lanXML har laddat färdigt.
    yearlyXML.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {
            var unparsedResponse = yearlyXML.responseText; // Skapa en variabel med texten vi får som response.
            var parsedData = JSON.parse(unparsedResponse);
            var year = 2018;
            var month = 5;
            var date = "-0" + month + "-";

            for (var i = 0; i < parsedData.length; i++) {


                if (parsedData[i].date.indexOf(date) > -1) {

                    console.log("Date: " + parsedData[i].date + ", Close: " + parsedData[i].close);
                    yearArray.push(parsedData[i].close);

                    checkMaxValue(parsedData[i].close);

                    console.log("MATCHED");
                    month++;
                    if (month > 9) {
                        date = "-" + month + "-";
                    }
                    else {

                        date = "-0" + month + "-";
                    }
                    if (month == 12) {
                        month = 1;
                    }

                }

            }


        }
        createStats();
    };
    yearlyXML.send();
}

function checkMaxValue($value){
    if ($value>$maxValue){
        $maxValue = parseInt($value,10)*2;
        
    }
}

function createStats(){

    var ctx = document.getElementById('stockChart1').getContext('2d');
    
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['June', 'July', 'August','September','October','November','December','January','February','March','April','Today'],
            datasets: [{
                label: ['Price $'],
                data: [
                    yearArray[0],yearArray[1], yearArray[2], yearArray[3],
                    yearArray[4], yearArray[5], yearArray[6], 
                    yearArray[7], yearArray[8], yearArray[9], 
                    yearArray[10], $currentPrice
                    ],
                
                backgroundColor: [
                    'rgba(26, 188, 156,0.1)',
                ],
                borderColor: [
                    'rgba(26, 188, 156,1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            showLines: true,
            
            elements: {
                line: {
                    tension: 0
                }
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: true,
                        color: "#c2dffdaf"
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: true,
                        color: "#c2dffdaf"
                    }
                }]
            }
        }
    });

}    


