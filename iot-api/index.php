<?php

require('./config.php');
require('./auth.php');

$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
$pathParts = explode("/", $path);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

switch ($pathParts[0]){
    case "message":
        message($pathParts, $conn);
        break;
}

function message($paths, $conn) {
    require('./message.php');
    messageQueue($paths, $conn);
}
