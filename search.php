<?php

//See if the search terms entered match the keywords of any airport
//If so, redirect to the home page with the correct parameters
$terms = $_GET['terms'];
$terms = strtolower($terms);
if ($terms == "" or !isset($terms)) {
	header("Location: /?ariport=LHR&intro=false");
}
$found = 0;

$filesarray = scandir("coordinates");
$length =  count($filesarray) - 2;
$count = 0;
$found = 0;

foreach ($filesarray as $file) {
    if ('.' === $file) continue;
    if ('..' === $file) continue;
    $xml = simplexml_load_file("coordinates/".$file);
    $keywords = $xml->Keywords;
    $keywords = explode(" ", $keywords);
    foreach ($keywords as $keyword) {
    	if (strpos($terms, $keyword) !== false) {
			header("Location: /?intro=false&airport=".substr($file, 0, -4));
            $found = 1;
		}
	}
    $count++;
    if ($count == 17 and $found == 0) {
        header("Location: /?error=notfound&intro=false");
    }
}

?>