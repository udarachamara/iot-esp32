<?php

$servername = $config['DB_HOST'];
$username = $config['DB_USER'];
$password = $config['DB_PASSWORD'];
$dbname = $config['DB_NAME'];

$apiKey = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$reqestHeaders = apache_request_headers();


$sql = "SELECT `key`, `value` FROM iot_config where disabled = 0 and `key` = 'api_key'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $apiKey = $row["value"];
  }

}

if (isset($reqestHeaders['x-api-key'])) {
    if ($reqestHeaders['x-api-key'] != $apiKey) {
      echo "code-403";
      exit;
    }
} else {
   echo "code-403";
   exit; 
}

?>
