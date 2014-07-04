<?php
set_time_limit(0);

require_once dirname(__FILE__)."/classes/FacebookAuth.php";
require_once dirname(__FILE__)."/classes/FacebookSearch.php";

$query = "football,soccer";

$fa = new FacebookAuth("YOUR_APP_ID","YOUR_APP_SECRET");

$accessToken = $fa->getAccessToken();

$since = 0;

while (true) {
    $fs = new FacebookSearch($accessToken);
    
    print_r($fs->search($query,$since));
    
    $since = time();

    sleep(5*60); // sleep 5 minutes before searching again
}
