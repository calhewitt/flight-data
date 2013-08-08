<?php

//See if the search terms entered match the keywords of any airport
//If so, redirect to the home page with the correct parameters
$terms = $_GET['terms'];
$terms = strtolower($terms);
if (strpos('lhr london heathrow uk', $terms) !== false) {
	header("Location: /?airport=LHR");
}
else if (strpos('lgw london gatwick uk', $terms) !== false) {
	header("Location: /?airport=LGW");
}
else if (strpos('lst london stanstead uk', $terms) !== false) {
	header("Location: /?airport=LST");
}
else if (strpos('ltn london luton uk', $terms) !== false) {
	header("Location: /?airport=LTN");
}
else if (strpos('jfk kennedy new york usa', $terms) !== false) {
	header("Location: /?airport=JFK");
}
else if (strpos('auh abu dhabi united arab emirates', $terms) !== false) {
	header("Location: /?airport=AUH");
}
else if (strpos('ala almaty kazakhstan', $terms) !== false) {
	header("Location: /?airport=ALA");
}
else if (strpos('pvg pudong shanghai china', $terms) !== false) {
	header("Location: /?airport=PVG");
}
else if (strpos('cdg charles de gaulle paris france', $terms) !== false) {
	header("Location: /?airport=CDG");
}
else if (strpos('lax los angeles usa', $terms) !== false) {
	header("Location: /?airport=LAX");
}
else if (strpos('syd sydney kingsford smith australia', $terms) !== false) {
	header("Location: /?airport=SYD");
}
else if (strpos('ebbr brussels belgium', $terms) !== false) {
	header("Location: /?airport=EBBR");
}
else if (strpos('eddf frankfurt germany', $terms) !== false) {
	header("Location: /?airport=EDDF");
}
else if (strpos('egbb birmingham uk', $terms) !== false) {
	header("Location: /?airport=EGBB");
}
else if (strpos('lfbd bordeaux mérignac bordeaux-merignac france', $terms) !== false) {
	header("Location: /?airport=LFBD");
}
else if (strpos('utaa ashgabat turkmenistan', $terms) !== false) {
	header("Location: /?airport=UTAA");
}
else if (strpos('vtbs suvarnabhumi thailand', $terms) !== false) {
	header("Location: /?airport=VTBS");
}
else if ($terms == "") {
	header("Location: /");
}
else header("Location: /?error=notfound");;
?>