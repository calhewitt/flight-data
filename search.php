<?php
$terms = $_GET['terms'];
$terms = strtolower($terms);
if (strpos('lhr heathrow london uk', $terms) !== false) {
	header("Location: index.php?airport=LHR");
}
else if (strpos('lgw london gatwick uk', $terms) !== false) {
	header("Location: index.php?airport=LGW");
}
else if (strpos('lst london stanstead uk', $terms) !== false) {
	header("Location: index.php?airport=LST");
}
else if (strpos('ltn london luton uk', $terms) !== false) {
	header("Location: index.php?airport=LTN");
}
else if (strpos('jfk kennedy new york usa', $terms) !== false) {
	header("Location: index.php?airport=JFK");
}
else if (strpos('auh abu dhabi united arab emirates', $terms) !== false) {
	header("Location: index.php?airport=AUH");
}
else if (strpos('ala almaty kazakhstan', $terms) !== false) {
	header("Location: index.php?airport=ALA");
}
else if (strpos('pvg pudong shanghai china', $terms) !== false) {
	header("Location: index.php?airport=PVG");
}
else if (strpos('cdg charles de gaulle paris france', $terms) !== false) {
	header("Location: index.php?airport=CDG");
}
else if (strpos('svo sheremetyevo moscow russia', $terms) !== false) {
	header("Location: index.php?airport=SVO");
}
else if (strpos('lax los angeles usa', $terms) !== false) {
	header("Location: index.php?airport=LAX");
}
else if (strpos('syd sydney kingsford smith australia', $terms) !== false) {
	header("Location: index.php?airport=SYD");
}
else if ($terms == "") {
	header("Location: index.php");
}
else print "The airport you entered could not be found.";
?>