<?php

class LoginController extends controller {

    public function __construct() {
    }
    
    public static function isLogged() {
        if(isset($_SESSION["id"]) && isset($_SESSION["nickname"])) {
            return true;
        }
        return false;
    }
    
    public function login() {
        
        header("Content-Type: application/json; charset=utf-8");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $csrf_token = filter_input(INPUT_POST, "csrf_token");        
        if(isset($csrf_token)) {
            if($csrf_token != $_SESSION["csrf_token"]) {
                echo json_encode(false);
                exit;
            }
        }
        else {
            echo json_encode(false);
            exit;
        }
        $session_token = md5(filter_input(INPUT_SERVER, "REMOTE_ADDR").filter_input(INPUT_SERVER, "HTTP_USER_AGENT"));
        if($_SESSION["owner"] != $session_token) {
            echo json_encode(false);
            exit;
        }
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $userDAO = new UserDAO();
        $list = $userDAO->login($user);
        if($list != null) {
            $this->setSession(
                   $list["user"]->getId(),
                   $list["user"]->getNickname()
            );
            echo json_encode(true);
        }
        else {
            echo json_encode(false);
        }        
    }
    
    private function setSession($id, $nickname) {
        $_SESSION["id"] = $id;
        $_SESSION["nickname"] = $nickname;   
    }
	
    public function logout() {        
        
        $_SESSION['room_id'] = "";
        unset($_SESSION['room_id']);
        $_SESSION['id'] = "";
        unset($_SESSION['id']);
        $_SESSION["nickname"] = "";
        unset($_SESSION['nickname']);

        header("Location: ".BASE);
    }

}