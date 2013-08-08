<?php

//See if the search terms entered match the keywords of any airport
//If so, redirect to the home page with the correct parameters
$terms = $_GET['terms'];
$terms = strtolower($terms);
if (strpos('lhr london heathrow uk', $terms) !== false) {
	header("Location: /?airport=LHR&intro=false");
}
else if (strpos('lgw london gatwick uk', $terms) !== false) {
	header("Location: /?airport=LGW&intro=false");
}
else if (strpos('lst london stanstead uk', $terms) !== false) {
	header("Location: /?airport=LST&intro=false");
}
else if (strpos('ltn london luton uk', $terms) !== false) {
	header("Location: /?airport=LTN&intro=false");
}
else if (strpos('jfk kennedy new york usa', $terms) !== false) {
	header("Location: /?airport=JFK&intro=false");
}
else if (strpos('auh abu dhabi united arab emirates', $terms) !== false) {
	header("Location: /?airport=AUH&intro=false");
}
else if (strpos('ala almaty kazakhstan', $terms) !== false) {
	header("Location: /?airport=ALA&intro=false");
}
else if (strpos('pvg pudong shanghai china', $terms) !== false) {
	header("Location: /?airport=PVG&intro=false");
}
else if (strpos('cdg charles de gaulle paris france', $terms) !== false) {
	header("Location: /?airport=CDG&intro=false");
}
else if (strpos('lax los angeles usa', $terms) !== false) {
	header("Location: /?airport=LAX&intro=false");
}
else if (strpos('syd sydney kingsford smith australia', $terms) !== false) {
	header("Location: /?airport=SYD&intro=false");
}
else if (strpos('ebbr brussels belgium', $terms) !== false) {
	header("Location: /?airport=EBBR&intro=false");
}
else if (strpos('eddf frankfurt germany', $terms) !== false) {
	header("Location: /?airport=EDDF&intro=false");
}
else if (strpos('egbb birmingham uk', $terms) !== false) {
	header("Location: /?airport=EGBB&intro=false");
}
else if (strpos('lfbd bordeaux mérignac bordeaux-merignac france', $terms) !== false) {
	header("Location: /?airport=LFBD&intro=false");
}
else if (strpos('utaa ashgabat turkmenistan', $terms) !== false) {
	header("Location: /?airport=UTAA&intro=false");
}
else if (strpos('vtbs suvarnabhumi thailand', $terms) !== false) {
	header("Location: /?airport=VTBS&intro=false");
}
else if ($terms == "") {
	header("Location: /");
}
else header("Location: /?error=notfound");;
?>