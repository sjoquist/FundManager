var BASEURL = "https://api.iextrading.com/1.0";


var searchTicker = "APPL";

var searchCompany = BASEURL+"/stock/"+searchTicker+"/company";


console.log(searchCompany);

var searchCompany = new XMLHttpRequest();



// GET/POST, URL, async
searchCompany.open("GET", lanURL, true);

//När lanXML har laddat färdigt.
searchCompany.onload = function () {


    var ourData = searchCompany.responseText; // Skapa en variabel med texten vi får som response.

    console.log(ourData);





};
// Måste skicka för att fungera.
searchCompany.send();