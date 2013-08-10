<?php

//Load different code if the app is being embeded
$stylesheetcode = '<link rel = "stylesheet" href = "main.css">';
$embed = false;
if(isset($_GET['embed'])) {
  if ($_GET['embed'] == "true") {
      $stylesheetcode = '<link rel = "stylesheet" href = "nocontrols.css">';
      $embed = true;
  }
}

$airport = "LHR";
if (isset($_GET['airport'])) {
  $airport = $_GET['airport'];
}

//If the code given is not valid, check if it is a name/city/country

if (file_exists("coordinates/".$airport.".xml") == false) {
  header("Location: search.php?terms=".$airport);
}

//Open up the xml file of the airport specified and get out the data

$xml = simplexml_load_file("coordinates/".$airport.".xml");
$latlng = $xml->Destinations;
$baselat = $xml->BaseLat;
$baselng = $xml->BaseLng;
$airportname = $xml->AirportName;
$city = $xml->CityName;

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

  window.history.pushState("", "", "/?airport=<?php print $airport; ?>");

  //If the user is at the start of a session, show the intro

  <?php if (!isset($_GET['intro']) and $embed == false) {
    print "intro();";
  }
  if (isset($_GET['selected'])) {
    print "setCurrent('".$_GET['selected']."')";
  }
  ?>

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

  $("#walpha-link").click(function() {
    window.open("http://www.wolframalpha.com/input/?i=" + selected);
  }); 

  $("#close-intro").click(function() {
    closeMask();
  });   

});

function initialize() {
    //Initially set up map
    var mapOptions = {
      center: new google.maps.LatLng(35, <?php print $baselng; ?>),
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
function setCurrent(title, instant) {
  $("#selected-text").text(title);
  $("#search").blur();
  if (instant == true) {
    $("#error").hide();
    $("#selected").show();
  }
  else {
    $("#error").fadeOut("fast");
    $("#selected").fadeIn("fast"); 
  }
  selected = title; 
  window.history.pushState("", "", "/?airport=<?php print $airport; ?>&selected=" + title);
}

function closeDialogs() {
  $("#selected").fadeOut("fast");
  $("#error").fadeOut("fast");
  window.history.pushState("", "", "/?airport=<?php print $airport; ?>");
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
      <div id = "walpha-link"></div>
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