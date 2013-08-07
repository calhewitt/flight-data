<?php

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
}
else if ($airport == "LGW") {
  $baselat = 51.1481;
  $baselng = -0.1903;
  $airportname = "London Gatwick";
}
else if ($airport == "LST") {
  $baselat = 51.8850;
  $baselng = 0.2350;
  $airportname = "London Stanstead";
}
else if ($airport == "LTN") {
  $baselat = 51.8747;
  $baselng = -0.3683;
  $airportname = "London Luton";
}
else if ($airport == "JFK") {
  $baselat = 40.6397;
  $baselng = -73.7789;
  $airportname = "JFK New York";
}
else if ($airport == "AUH") {
  $baselat = 24.4281;
  $baselng = 54.6470;
  $airportname = "Abu Dhabi International";
}
else if ($airport == "ALA") {
  $baselat = 43.3519;
  $baselng = 77.0406;
  $airportname = "Almaty, Kazakhstan";
}
else if ($airport == "PVG") {
  $baselat = 31.1433;
  $baselng = 121.8053;
  $airportname = "Shanghai (Pudong)";
}
else if ($airport == "CDG") {
  $baselat = 49.0128;
  $baselng = 2.5500;
  $airportname = "Charles de Gaulle, Paris";
}
else if ($airport == "SVO") {
  $baselat = 55.9728;
  $baselng = 37.4147;
  $airportname = "Sheremetyevo Airport, Moscow";
}
else if ($airport == "LAX") {
  $baselat = 33.9471;
  $baselng = -118.4082;
  $airportname = "Los Angeles International";
}
else if ($airport == "SYD") {
  $baselat = -33.9461;
  $baselng = 151.1772;
  $airportname = "Sydney Kingsford Smith";
}
else {
  $baselat = 0;
  $baselng = 0;
}

//Open up the file containing coordinates and split it at semicolons

$latlng = file_get_contents("coordinates/".$airport.".txt");
$latlng = explode(";", $latlng);
?>
<!DOCTYPE html>
<html>
<head>
<title>Flights</title>
<link rel = "stylesheet" href = "main.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;key=AIzaSyBcm2W5wCfL29d2ToSLPv1ZUse4Raon3og"></script>
<script src="https://raw.github.com/twitter/typeahead.js/master/dist/typeahead.js"></script>
<script>
var map;

$(document).ready(function() {

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

  $("#selected img, #error img").click(function() {
    $("#selected").fadeOut("fast");
    $("#error").fadeOut("fast");
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
    marker(<?php print $baselat; ?>, <?php print $baselng; ?>, "<?php print $airportname ?>", true);				
		<?php foreach($latlng as $place) {
			$citylatlng = explode(",", $place);
      if(isset($citylatlng[2])) {
        print "marker($citylatlng[0], $citylatlng[1], $citylatlng[2]);";
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
  $("#error").fadeOut("fast", function() {
      $("#selected").fadeIn("fast");  
  });
}
</script>
</head>
<body>
  <div id = "map-canvas"></div>
  <div id = "searchbox">
    <div id = "current">Currently showing air routes from <?php print $airportname ?></div>
    <img src = "airport-big.png">
    <form method = "get" action = "search.php" id = "searchform" autocomplete = "off">
      <input type="text" placeholder="Enter Airport Name or Code" name="terms" id="search" x-webkit-speech onwebkitspeechchange="$('#searchform').submit();">
    </form>
    <div id = "selected">
      <span id = "selected-text"></span>
        <img src = "close.png">
    </div>
    <?php if (isset($_GET['error'])) {
      if ($_GET['error'] == "notfound") {
        print '<div id = "error">
        <span id = "selected-text">We dont&#39;t seem to have data on that airport - try searching for another one.</span>
        <img src = "close.png">
        </div>
        <script>
          window.history.pushState("", "", "/");
        </script>';
      }
    }
    ?>
  </div>
<div id = "footer">
  <a href = "https://github.com/calhewitt/flight-data" target = "_blank">View Source on GitHub</a> &bull;
  <a href = "about.php">About this Project</a> &bull;
  <a href = "help.php">Help</a>
</div>
</body>
</html>