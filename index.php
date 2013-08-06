<?php
$airport = "LHR";
if (isset($_GET['airport'])) {
  $airport = $_GET['airport'];
}
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
else if ($airport == "STN") {
  $baselat = 51.8850;
  $baselng = 0.2350;
  $airportname = "London Stanstead";
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
<script>
var map;

function initialize() {
		geocoder = new google.maps.Geocoder();
        var mapOptions = {
          center: new google.maps.LatLng(35, 0.4614),
          zoom: 3,
		  disableDefaultUI: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
        marker(<?php print $baselat; ?>, <?php print $baselng; ?>);				
		<?php foreach($latlng as $place) {
			$citylatlng = explode(",", $place);
			print "marker($citylatlng[0], $citylatlng[1]);";
		}
		?>
}
google.maps.event.addDomListener(window, 'load', initialize);


function marker(lat, lng) {
	var image = new google.maps.MarkerImage("airport.png",
        null, 
        new google.maps.Point(0,0),
        new google.maps.Point(10, 10)
    );
	var marker = new google.maps.Marker({
		map:map,
		draggable:false,
		position: new google.maps.LatLng(lat, lng),
		icon: image
	});

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
}
</script>
</head>
<body>
<div id = "map-canvas"></div>
<div id = "searchbox">
<div id = "current">Currently showing air routes from <?php print $airportname ?></div>
<img src = "airport-big.png">
<form method = "get" action = "search.php">
  <input type = "text" placeholder = "Enter Airport Name or Code" name = "terms" autocomplete = "off">
</form>
</div>
<div id = "footer">
<a href = "https://github.com/calhewitt/flight-data" target = "_blank">View Source on GitHub</a> &bull;
<a href = "about.php">About this Project</a> &bull;
<a href = "help.php">Help</a>
</div>
</body>
</html>