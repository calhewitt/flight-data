<?php
$terms = $_GET['terms'];
if ($terms == "LHR" or strpos($terms,'LHR') !== false or strpos($terms,'Heathrow') !== false or strpos($terms,'London') !== false or strpos($terms,'UK') !== false) {
	header("Location: index.php?airport=LHR");
}
else if ($terms == "JFK" or strpos($terms,'JFK') !== false or strpos($terms,'Kennedy') !== false or strpos($terms,'New York') !== false or strpos($terms,'USA') !== false) {
	header("Location: index.php?airport=JFK");
}
else if ($terms == "AUH" or strpos($terms,'AUH') !== false or strpos($terms,'Abu Dhabi') !== false or strpos($terms,'United Arab Emirates') !== false) {
	header("Location: index.php?airport=AUH");
}
else if ($terms == "ALA" or strpos($terms,'ALA') !== false or strpos($terms,'Almaty') !== false or strpos($terms,'Kazakhstan') !== false) {
	header("Location: index.php?airport=ALA");
}
else if ($terms == "") {
	header("Location: index.php");
}
else print "The airport you entered could not be found.";
?>