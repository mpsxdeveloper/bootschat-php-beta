<?php

class AjaxController extends controller {

    private User $loggedUser;
    
    public function __construct() {        
        if(!LoginController::isLogged()) {
            header("Location: ".BASE);
        }
        else {
            $id = $_SESSION["id"];
            $userDAO = new UserDAO();
            $this->loggedUser = $userDAO->getUser($id);
        }
    }
	
    public function send_message() {
        
        $info = array('error' => '0', 'datemsg'=>'', 'type'=>'');	
        $message = new Message();        
        $room_id = $_SESSION["room_id"];
        $receiver_id = filter_input(INPUT_POST, "receiver_id");
        $datemsg = date('Y-m-d H:i:s');
        $msg = filter_input(INPUT_POST, "msg");
        $type = filter_input(INPUT_POST, "type");
        $sender_id = $_SESSION["id"];        
        $info["type"] = $type;
        $info["datemsg"] = $datemsg;
        
        $message->setRoom_id($room_id);
        $message->setReceiver_id($receiver_id);
        $message->setSender_id($sender_id);
        $message->setDatemsg($datemsg);
        $message->setMsg($msg);
        $message->setType($type);
        $messageDAO = new MessageDAO();
        if($messageDAO->sendMessage($message)) {
            echo json_encode($info);
        }        
        
    }
    
    private function send_goodbye($sender_id, $room_id) {
        
        $info = array('error' => '0', 'datemsg'=>'', 'type'=>'');	
        $message = new Message();        
        $room_id = $_SESSION["room_id"];
        $receiver_id = 0;
        $datemsg = date('Y-m-d H:i:s');
        $msg = "Saiu da sala por inatividade...";
        $type = "bye";        
        $info["type"] = $type;
        $info["datemsg"] = $datemsg;
        
        $message->setRoom_id($room_id);
        $message->setReceiver_id($receiver_id);
        $message->setSender_id($sender_id);
        $message->setDatemsg($datemsg);
        $message->setMsg($msg);
        $message->setType($type);
        $messageDAO = new MessageDAO();
        if($messageDAO->sendMessage($message)) {
            echo json_encode($info);
        }
        
    }
    
    public function send_photo() {
		
        $info = array('error' => '0');
        $receiver_id = filter_input(INPUT_POST, "receiver_id");
        $sender_id = $_SESSION["id"];
        $room_id = $_SESSION["room_id"];
	    $message = new Message();
        $message->setSender_id($sender_id);
        $message->setReceiver_id($receiver_id);
        $message->setRoom_id($room_id);
        $message->setType("image");
        $datemsg = date('Y-m-d H:i:s');
        $message->setDatemsg($datemsg);

	    $allowed = array('image/jpeg', 'image/jpg', 'image/png');
	    if(!empty($_FILES['photo']['tmp_name'])) {
            if(in_array($_FILES['photo']['type'], $allowed)) {
                $newname = md5(time().rand(0,9999));
                if($_FILES['photo']['type'] == 'image/png') {
                    $newname .= '.png';
                }
                else {
                    $newname .= '.jpg';
                }
            move_uploaded_file($_FILES['photo']['tmp_name'], 'media/uploads/'.$newname);
            $message->setMsg($newname);
            $messageDAO = new MessageDAO();
            $messageDAO->sendMessage($message);
            }
            else {
                $info['error'] = '1';		
            }
        }
        else {
            $info['error'] = '1';
	    }
        echo json_encode($info);
        exit;

    }
    
    public function get_messages() {        
        
        $array = array(
            'status' => '1', 
            'msgs' => array(), 
            'last_time' => date('Y-m-d H:i:s')
        );
        $id = $_SESSION["id"];
        $messageDAO = new MessageDAO();
	    set_time_limit(60);
	    $ult_msg = date('Y-m-d H:i:s');
        $last_time = filter_input(INPUT_GET, "last_time");
        
        if($last_time != null || trim($last_time) != "") {
            $ult_msg = $last_time;
	    }        
        
	    while(true) {
            session_write_close();
            $msgs = $messageDAO->getMessages($ult_msg, $_SESSION["room_id"], $id);
            if(count($msgs) > 0) {
                $array['msgs'] = $msgs;
		        $array['last_time'] = date('Y-m-d H:i:s');
		        break;
            }
            else {
                sleep(2);
		        continue;
            }
        }
        echo json_encode($array);
	    exit;

    }
    
    public function get_minutes() {
        $room_id = filter_input(INPUT_GET, "room_id");
        $messagesDAO = new MessageDAO();        
        $users = $messagesDAO->getLastMessageMinutes($room_id);
        echo json_encode($users);
    }
    
    public function delete_messages() {
        $messageDAO = new MessageDAO();
        echo json_encode($messageDAO->deleteMessages());
    }
    
    public function disconnect_users() {
        $roomDAO = new RoomDAO();
        $rooms = $roomDAO->listRooms();
        $roomController = new RoomController();
        foreach($rooms as $room) {
            $room["id"];
            $messageDAO = new MessageDAO();
            $usersID = $messageDAO->getLastMessageMinutes($room["id"]);
            foreach($usersID as $userID) {                
                $this->send_message($userID, $room["id"]);                
                $roomController->exit($room["id"], 1);
            }
        }
    }
    
}
