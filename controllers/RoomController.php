<?php

class RoomController extends controller {

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
	
    public function index() {        
        $data = array(
            'info' => array(),
            'warning'=>''
	);
        $warning = filter_input(INPUT_GET, "w");
        $roomDAO = new RoomDAO();
        $list = $roomDAO->listRooms();
        $data["info"] = $list;
        $data["warning"] = $warning;
        $this->loadView('rooms', $data);  
    }
    
    public function show($roomID) {
        
        $data = array(
            'info' => array(),
            'roomId'=>'',            
        );        
        
        $roomDAO = new RoomDAO();
        
        if(isset($_SESSION["room_id"]) && $_SESSION["room_id"] == $roomID) {   // User already entered a room
            $roomDAO->deleteUsersRooms($this->loggedUser->getId(), $roomID);
            $this->exit($roomID, 0);
        }
        else {           
            $_SESSION["room_id"] = $roomID;
            $data["info"] = $_SESSION["room_id"];
            $data["roomId"] = $roomID;
            $roomDAO = new RoomDAO();
            $entered = $roomDAO->addUsersRooms($this->loggedUser->getId(), $roomID);
            if($entered) {
                $this->loadView('room', $data);
            }
        }
        
    }
    
    public function list($id) {
        $data = array();
        $userDAO = new UserDAO();
        $data["info"] = $userDAO->getUsersByRoom($id);
        echo json_encode($data);
    }
    
    public function exit($roomID, $warning) {
        
        $roomDAO = new RoomDAO();        
        $roomDAO->deleteUsersRooms($this->loggedUser->getId(), $roomID);
        $_SESSION['room_id'] = "";
        unset($_SESSION['room_id']);
        header("Location: ".BASE."room/index/?w=".$warning);
        
    }
    
}