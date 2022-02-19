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

       $msgCaling->caller($topic, $data, $_SERVER['REQUEST_METHOD']);
    }
    
}

class MessageCalling {
 
    public function caller($to_call, $data, $method) {
        if (is_callable([$this, $to_call])) {
            $this->$to_call($method, $data);
        } else {
            echo 'code-404';
        }
    }
 
    public function room($method = 'GET', $data = []) {
        if ($method == 'POST')
            echo json_encode($data['data']);
        else
            echo '0';
    }
 
    private function topics() {
        echo 'room | myroom | home | room_light1 | room_light2 | room_light3 | motor | kitchen_light1';
    }
    
    public function updateMessageHistory($conn, $topic, $data) {
        try {
            $msg = json_encode($data['data']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $sql = "INSERT INTO `messages` (`topic`, `message`, `client_ip`) VALUES ('" . $topic . "', '" . $msg . "', '" . $ip . "')";
            if ($conn->query($sql) === TRUE) {} else {}
        }
        catch(Exception $e) {}
    }
 
}