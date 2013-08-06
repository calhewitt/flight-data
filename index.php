<?php
$airport = "LHR";
if (isset($_GET['airport'])) {
  $airport = $_GET['airport'];
}
if ($airport == "LHR") {
  $baselat = 51.4775;
  $baselng = 0.4614;
}
else if ($airport == "JFK") {
  $baselat = 40.6397;
  $baselng = -73.7789;
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
        marker(<?php print $baselat ?>, <?php print $baselng ?>);				
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
<div id = "map-canvas"></div><!--
<div id = "searchbox">
<img src = "airport-big.png">
<form>
  <input type = "text" placeholder = "Enter Airport Name or Code">
</form>
</div>-->
</body>
</html>