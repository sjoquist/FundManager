/*var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: options
});*/

var globalPortfolio;
var globalAmount = [];
var globalCompany = [];
var globalValue = [];
var globalColors = [
    'rgba(255, 99, 132,1)',
    'rgba(54, 162, 235,1)',
    'rgba(255, 206, 86,1)',
    'rgba(75, 192, 192,1)',
    'rgba(155, 89, 182,1)',
    'rgba(230, 126, 34,1)',
    'rgba(26, 188, 156,1)',
    'rgba(44, 62, 80,1)'
]

var globalBorder = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(155, 89, 182,1.0)',
    'rgba(230, 126, 34,1.0)',
    'rgba(26, 188, 156,1.0)',
    'rgba(44, 62, 80,1.0)'
]
function calculatePortfolio(userArray){
            
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
            
            globalCompany.push(ticker);
            globalAmount.push(amount);

            console.log("ticker global:" + globalCompany);
            console.log("amount global: " + globalAmount);
            var price2 = BASEURL + "/stock/" + ticker + "/price";
            console.log(price2);
            var price2XML   = new XMLHttpRequest();
            // GET/POST, URL, async
            price2XML.open("GET", price2, false);
            price2XML.onreadystatechange = function () {

                if (this.readyState == 4 && this.status == 200) {
                    var unparsedResponse = price2XML.responseText; // Skapa en variabel med texten vi får som response.
                    var parsedData = JSON.parse(unparsedResponse);
                    console.log(parsedData);
                    console.log("Total: " + parsedData * amount);
                    var value = parsedData * amount;
                    globalValue.push(value.toFixed(2));
                    portfolioValue += (parsedData*amount);
                }

                

            }
            price2XML.send();

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

    portfolioValue = portfolioValue.toFixed(2);
    globalPortfolio = portfolioValue;
   // alert("In chart.js: " + globalPortfolio);
    $.ajax({
        url: window.location.href,
        data: {sendPort: portfolioValue},
        method:'POST'
    })
    updateStockList();
    writeChart();
} 

function updateStockList(){
    console.log("Stocklist Info");
    console.log("portfolio " + globalPortfolio);
    console.log("value " + globalValue);
    console.log("amount " + globalAmount);
    console.log("name " + globalCompany);
    var list = [];
    for (var j = 0; j < globalCompany.length; j++){
        console.log("Division: " + (globalValue[j]/ globalPortfolio));
        var listPercentage = (globalValue[j]/ globalPortfolio);
        listPercentage = (listPercentage*100).toFixed(2);
        console.log(listPercentage);
        list.push({
            'ticker': globalCompany[j], 
            'value': globalValue[j], 
            'amount': globalAmount[j],
            'percentage': listPercentage,
            'color' : globalColors[j]
        });
    } 
    
    list = sortByKey(list,'value');
    console.log(list[0].percentage);
    for(var i=0; i<list.length; i++){
        var bg = list[i].color.slice(0, list[i].color.length-2)+ "0.5)";
        console.log(bg);
        $("#stockList").append("<li class='listName' style='text-shadow:var(--box-shadow); background-color:"+ list[i].color+";'>" + list[i].ticker+ "</li>");
        $("#stockList").append("<li class='listPercentage' style='text-shadow:var(--box-shadow); background-color:"+ bg+";'>$"+  list[i].value + " ( " + list[i].percentage+ "% )</li>");
        $("#stockRows").append(
        "<div class='stockListLarge'>"+
            "<div class='sll_Name' style='border-right:5px solid "+list[i].color+ ";'>" + list[i].ticker +"</div>"+
            "<div class='sll_Percentage' style='width:"+ list[i].percentage +"%; background-color:" +list[i].color + ";'>" + list[i].percentage + "%</div>"+
            "<div class='sll_Value'> $"+list[i].value+"</div>"+
        "</div>");
    }

}

function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return y-x;
    });
}

function writeChart(){


    var data ;

    var options;

    var ctx = document.getElementById('myPieChart').getContext('2d');

    Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.elements.center) {
                //Get ctx from string
                var ctx = chart.chart.ctx;

                //Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var color = centerConfig.color || '#000';
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                //Start with a base font of 30px
                ctx.font = "40px " + fontStyle;

                //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(newFontSize, elementHeight);

                //Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;

                //Draw text in center
                ctx.fillText(txt, centerX, centerY);
            }
        }
    });


    var total = globalPortfolio;

    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: globalCompany,
            datasets: [{
                label: '# of Stocks',
                data: globalValue, 
                backgroundColor: globalColors,
                borderColor: globalBorder,
                    borderWidth: 1
            }]
        },
        /* ORIGINAL
        options: {
            responsive: true,
            cutoutPercentage: 70
        }
        */
        options: {
            responsive: true,
            cutoutPercentage: 60,
            elements: {
                center: {
                    text: "$" + total,
                    color: '', //Default black
                    fontStyle: 'Acme Gothic', //Default Arial
                    sidePadding: 20 //Default 20 (as a percentage)
                }
            }
        }
    });

}