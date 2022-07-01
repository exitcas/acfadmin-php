<?php
include $_ENV["ROOT"] . "vendor/autoload.php";
$key = new Cloudflare\API\Auth\APIKey($_ENV["MAIL"], $_ENV["TOKEN"]);
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
//print_r($adapter);
$user = new Cloudflare\API\Endpoints\User($adapter);
$zones = new Cloudflare\API\Endpoints\Zones($adapter);
$zoneID = $zones->listZones("irony.cf");
echo $zoneID;