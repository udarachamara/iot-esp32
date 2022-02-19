<?php

require('./config.php');
require('./auth.php');

$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
$pathParts = explode("/", $path);


switch ($pathParts[0]){
    case "message":
        message($pathParts, $conn);
        break;
}

function message($paths, $conn) {
    require('./message.php');
    messageQueue($paths, $conn);
}
