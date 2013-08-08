<?php

//Load different code if the app is being embeded
$stylesheetcode = '<link rel = "stylesheet" href = "main.css">';
if(isset($_GET['embed'])) {
  if ($_GET['embed'] == "true") {
      $stylesheetcode = '<link rel = "stylesheet" href = "nocontrols.css">';
  }
}

$airport = "LHR";
if (isset($_GET['airport'])) {
  $airport = $_GET['airport'];
}

//If the code given is not valid, check if it is a name/city/country

if (file_exists("coordinates/".$airport.".txt") == false) {
  header("Location: search.php?terms=".$airport);
}

//Set the name, longditude and latitude of the base airport based on its code

if ($airport == "LHR") {
  $baselat = 51.4775;
  $baselng = -0.4614;
  $airportname = "London Heathrow";
  $city = "London";
}
else if ($airport == "LGW") {
  $baselat = 51.1481;
  $baselng = -0.1903;
  $airportname = "London Gatwick";
  $city = "London";
}
else if ($airport == "LST") {
  $baselat = 51.8850;
  $baselng = 0.2350;
  $airportname = "London Stanstead";
  $city = "London";
}
else if ($airport == "LTN") {
  $baselat = 51.8747;
  $baselng = -0.3683;
  $airportname = "London Luton";
  $city = "London";
}
else if ($airport == "JFK") {
  $baselat = 40.6397;
  $baselng = -73.7789;
  $airportname = "JFK New York";
  $city = "New York";
}
else if ($airport == "AUH") {
  $baselat = 24.4281;
  $baselng = 54.6470;
  $airportname = "Abu Dhabi International";
  $city = "Abu Dhabi";
}
else if ($airport == "ALA") {
  $baselat = 43.3519;
  $baselng = 77.0406;
  $airportname = "Almaty, Kazakhstan";
  $city = "Almaty";
}
else if ($airport == "PVG") {
  $baselat = 31.1433;
  $baselng = 121.8053;
  $airportname = "Shanghai (Pudong)";
  $city = "Shanghai";
}
else if ($airport == "CDG") {
  $baselat = 49.0128;
  $baselng = 2.5500;
  $airportname = "Charles de Gaulle, Paris";
  $city = "Paris";
}
else if ($airport == "LAX") {
  $baselat = 33.9471;
  $baselng = -118.4082;
  $airportname = "Los Angeles International";
  $city = "Los Angeles";
}
else if ($airport == "SYD") {
  $baselat = -33.9461;
  $baselng = 151.1772;
  $airportname = "Sydney Kingsford Smith";
  $city = "Sydney";
}
else if ($airport == "EBBR") {
  $baselat = 50.9014;
  $baselng = 4.4844;
  $airportname = "Brussels";
  $city = "Brussels";
}
else if ($airport == "EDDF") {
  $baselat = 50.0264;
  $baselng = 8.5431;
  $airportname = "Frankfurt-am-main";
  $city = "Frankfurt";
}
else if ($airport == "EGBB") {
  $baselat = 52.4800;
  $baselng = -1.9100;
  $airportname = "Birmingham Internaitional";
  $city = "Birmingham";
}
else if ($airport == "LFBD") {
  $baselat = 44.8283;
  $baselng = -0.7156;
  $airportname = "Bordeaux-Mérignac";
  $city = "Bordeaux";
}
else if ($airport == "UTAA") {
  $baselat = 37.9667;
  $baselng = 58.3333;
  $airportname = "Ashgabat International";
  $city = "Ashgabat";
}
else if ($airport == "VTBS") {
  $baselat = 13.6925;
  $baselng = 100.7509;
  $airportname = "Suvarnabhumi Airport";
  $city = "Bangkok";
}
else {
  $baselat = 0;
  $baselng = 0;
}

//Open up the file containing coordinates and split it at semicolons

$latlng = file_get_contents("coordinates/".$airport.".txt");

$latlng = substr($latlng, 3, -2);
$latlng = explode(";", $latlng);
?>
<!DOCTYPE html>
<html>
<head>
<title>Flights</title>
<meta charset=utf-8>
<?php print $stylesheetcode; ?>
<link rel = "stylesheet" href = "chardin/chardin.css">
<link rel = "icon" href = "airport-red.png">
<script src = "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
<script src = "https://maps.googleapis.com/maps/api/js?sensor=false&amp;key=AIzaSyBcm2W5wCfL29d2ToSLPv1ZUse4Raon3og"></script>
<script src = "typeahead.js"></script>
<script src = "chardin/chardin.min.js"></script>
<script>
var map;
var selected;

$(document).ready(function() {

  //If the user is at the start of a session, show the intro

  <?php if (!isset($_GET['intro'])) {
    print "intro();";
  }?>

  //Set up autocomplete for the main search box
  $('#search').typeahead([
    {
      name: 'airports',
      local: <?php print file_get_contents("autocomplete.json"); ?>
    }
  ]);

  //Override tpyeahead's functionality so the search form can be submitted by pressing the enter key
  $('*').keypress(function (e) {
  if (e.which == 13) {
    $('#searchform').submit();
      return false;
    }
  });

  //Make sure any alert boxes are closed when the autocomplete drop down is shown
  $("#search").focus(function() {
    $("#selected").fadeOut("fast");
    $("#error").fadeOut("fast");
  });

  $("#mask").click(function() {
     closeMask();
  }); 

  $("#selected-text").click(function() {
    window.location = "/search.php?terms=" + selected;
  });   

  $("#wikipedia-link").click(function() {
    window.open("http://en.wikipedia.org/wiki/" + selected);
  }); 

  $("#close-intro").click(function() {
    closeMask();
  });   

});

function initialize() {
    //Initially set up map
    var mapOptions = {
      center: new google.maps.LatLng(35, 0.4614),
      zoom: 3,
		  disableDefaultUI: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

    //Place orange icon on base airport
    marker(<?php print $baselat; ?>, <?php print $baselng; ?>, "<?php print $city; ?>", true);				
		<?php foreach($latlng as $place) {
			$citylatlng = explode(",", $place);
      if(isset($citylatlng[2])) {
        print 'marker('.$citylatlng[0].', '.$citylatlng[1].', "'.$citylatlng[2].'", false);';
      }
      else {
        print "marker($citylatlng[0], $citylatlng[1]);";
      }			
		}
		?>
}
google.maps.event.addDomListener(window, 'load', initialize);

//Function to add markers to the map
function marker(lat, lng, title, red) {
  if (title == undefined) {
    title = "No name specified";
  }

  //If the airport is the base, use the orange icon instead of the default
	if (red == true) {
    var image = new google.maps.MarkerImage("airport-red.png",
        null, 
        new google.maps.Point(0,0),
        new google.maps.Point(10, 10)
    );
  }
  else {
    var image = new google.maps.MarkerImage("airport.png",
        null, 
        new google.maps.Point(0,0),
        new google.maps.Point(10, 10)
    );
  }
	var marker = new google.maps.Marker({
		map:map,
		draggable:false,
		position: new google.maps.LatLng(lat, lng),
		icon: image,
    title: title,
	});

  //Draw a line between the marker position and the base airport
	var flightPlanCoordinates = [
      new google.maps.LatLng(<?php print $baselat ?>, <?php print $baselng ?>),
      new google.maps.LatLng(lat, lng)
  	];
  	var flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 1
  	});

  	flightPath.setMap(map);

    //Handle markers being clicked
    google.maps.event.addListener(marker, 'click', function() {
      setCurrent(title);
    });
}

//Show box below search bar and fill it with the clicked airport's name
function setCurrent(title) {
  $("#selected-text").text(title);
  $("#search").blur();
  $("#error").fadeOut("fast");
  $("#selected").fadeIn("fast"); 
  selected = title; 
}

function closeDialogs() {
  $("#selected").fadeOut("fast");
  $("#error").fadeOut("fast");
}

function help() {
  $("body").chardinJs('toggle'); 
}

function embed() {
  $("#mask").fadeIn();
  $("#embed").fadeIn();
  $("#embed textarea").select();
}

function intro() {
  $("#mask").show();
  $("#intro").show();
  $("#searchbox").hide();
  $("#footer").hide();
  $("#map-canvas").css("bottom", "0px");
}

function closeMask() {
  $("#mask").fadeOut();
  $("#embed").fadeOut();
  $("#intro").fadeOut();
  $("#map-canvas").animate({
    bottom: "30px",
  }, 500, function() {
    $("#footer").fadeIn("slow");
    $("#searchbox").fadeIn("slow");
  });

}
</script>
</head>
<body>
  <div id = "map-canvas"></div>
  <div id = "searchbox" data-intro="Type in the name of an airport, city or country and press enter to see all of the flight routes from it." data-position="right" >
    <div id = "current">Currently showing air routes from <?php print $airportname ?></div>
    <img src = "airport-big.png">
    <form method = "get" action = "search.php" id = "searchform" autocomplete = "off">
      <input type="text" placeholder="Enter Airport Name or Code" name="terms" id="search" x-webkit-speech onwebkitspeechchange="$('#searchform').submit();">
    </form>
    <div id = "selected">
      <span id = "selected-text"></span>
      <div id = "wikipedia-link"></div>
      <img src = "close.png" onclick = "closeDialogs();">
    </div>
    <?php if (isset($_GET['error'])) {
      if ($_GET['error'] == "notfound") {
        print '<div id = "error">
        <span id = "selected-text">We dont&#39;t seem to have data on that airport - try searching for another one.</span>
        <img src = "close.png" onclick = "closeDialogs();">
        </div>
        <script>
          window.history.pushState("", "", "/");
        </script>';
      }
    }
    ?>
  </div>
<div id = "mask"></div>
<div id = "embed">
You can embed this map into your own website by copying the code below. Change the width and height to any value you like.
<textarea>&lt;iframe src = "http://mightyflight.tk/?embed=true&amp;airport=<?php print $airport; ?>" width = "800" height = "600">&lt;/iframe></textarea>
</div>
<div id = "intro"><div id = "intro-title">MightyFLight</div>
Is an open data visualisation of the places you can fly to from any airport in the world. Press launch and start exploring.
<div id = "close-intro">launch</div>
</div>
<div id = "footer">
  <span id = "git" data-intro="This project is open source! Have a look on GitHub." data-position="top">
  <a href = "https://github.com/calhewitt/flight-data" target = "_blank">View Source on GitHub</a></span> &bull;
  <a href = "#" onclick = "embed();">Embed</a> &bull;
  <a href = "#" onclick = "help();">Help</a>
</div>
</body>
</html>