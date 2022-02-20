<?php

function messageQueue($paths, $conn) {
    $data = null;
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $data = $_GET;
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = $_POST;
    }
    if(isset($paths[1])) {
       $topic = $paths[1];
       $msgCaling = new MessageCalling();

       if ($_SERVER['REQUEST_METHOD'] == 'POST'){
          $msgCaling->updateMessageHistory($conn, $topic, $data);
       }

       $msgCaling->caller($topic, $data, $_SERVER['REQUEST_METHOD'], $conn);
    }
    
}

class MessageCalling {
 
    public function caller($to_call, $data, $method, $conn = null) {
        if (is_callable([$this, $to_call])) {
            $this->$to_call($method, $data, $conn);
        } else {
            echo 'code-404';
        }
    }
 
    public function room($method = 'GET', $data = []) {
        if ($method == 'POST'){
            $newData = [
                ["date" => "2022-02-01 01:00:05", "temperature" => 45],
                ["date" => "2022-02-01 01:00:10", "temperature" => 44],
                ["date" => "2022-02-01 01:00:15", "temperature" => 43],
                ["date" => "2022-02-01 01:00:20", "temperature" => 42],
                ["date" => "2022-02-01 01:00:25", "temperature" => 40],
                ["date" => "2022-02-01 01:00:30", "temperature" => 39],
                ["date" => "2022-02-01 01:00:35", "temperature" => 42],
                ];
            echo json_encode($newData);
        }  else {
            echo '0';
        }  
    }
    
    public function message($method = 'GET', $data = [], $conn) {
        $sql = "SELECT * FROM messages order by `create_at` desc limit 14";
        $result = $conn->query($sql);
        $newData = [];
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            array_push($newData, $row);
          }
        
        }
        echo json_encode($newData);
    }
 
    private function topics() {
        echo 'room | myroom | home | room_light1 | room_light2 | room_light3 | motor | kitchen_light1';
    }
    
    public function updateMessageHistory($conn, $topic, $data) {
        try {
            if (!$data['data'])
                return;

            $msg = json_encode($data['data']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $sql = "INSERT INTO `messages` (`topic`, `message`, `client_ip`) VALUES ('" . $topic . "', '" . $msg . "', '" . $ip . "')";
            if ($conn->query($sql) === TRUE) {} else {}
        }
        catch(Exception $e) {}
    }
 
}