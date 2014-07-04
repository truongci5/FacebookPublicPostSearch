<?php

require_once dirname(__FILE__)."/classes/FacebookAuth.php";
require_once dirname(__FILE__)."/classes/FacebookSearch.php";


$fa = new FacebookAuth("YOUR_APP_ID","YOUR_APP_SECRET");

$accessToken = $fa->getAccessToken();

$fs = new FacebookSearch($accessToken);

print_r($fs->search("football,soccer"));