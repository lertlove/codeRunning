<?php

	if (isset($_GET['q'])) {
		
		$q = $_GET['q'];

	    echo "input = ".$q."<br>";
	    echo "suggestion = ".doGoogleSpellingWithAnchor($q);  //returns suggestion

	}else{
	    // no input
	    echo "please input q parameter";
	}
 
function doGoogleSpellingWithEmphasize($q) {
 
    // grab google page with search
    $web_page = file_get_contents( "http://www.google.it/search?q=" . urlencode($q)."&hl=en" );

    // put anchors tag in an array
    /*preg_match_all('#<a([^>]*)?>(.*)</a>#Us', $web_page, $a_array);*/
    preg_match_all('#<b>(.*)</b>#Us', $web_page, $a_array);
    var_dump($a_array[0]);
    if(count($a_array[0])>0){
    	$a_link = $a_array[0][0];
    	return $a_link;
    }
 
    return $q;
}

function doGoogleSpellingWithAnchor($q) {
 
    // grab google page with search
    $web_page = file_get_contents( "http://www.google.it/search?q=" . urlencode($q)."&hl=en" );

    // put anchors tag in an array
    preg_match_all('#<a([^>]*)?>(.*)</a>#Us', $web_page, $a_array);
    
    for($j=0;$j<count($a_array[0]);$j++) {
    	
    	$a_link = $a_array[0][$j];

        // find link with spell suggestion and return it
        if(stristr($a_link,"spell")) {
            $a_link = strip_tags($a_link);

        	return $a_link;
        }
    }
 
    return $q;
}