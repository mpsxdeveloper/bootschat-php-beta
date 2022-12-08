<?php

class UserController extends controller {

    public function __construct() {   
    }
	
    public function index() {
        
        $data = array(
            'info' => array()            
	);       
        $this->loadView('home', $data);
        
    }
    
    public function add() {
        
        header("Content-Type: application/json; charset=utf-8");
        $nick = filter_input(INPUT_POST, "nick");
        $email = filter_input(INPUT_POST, "email");
        $csrf_token = filter_input(INPUT_POST, "csrf_token");
        if($csrf_token != $_SESSION["csrf_token"]) {
            echo json_encode(false);
            exit;
        }
        $password = filter_input(INPUT_POST, "password");
        $userDAO = new UserDAO();
        $user = new User();
        $user->setEmail($email);
        $user->setNickname($nick);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        if(!$userDAO->checkEmailInvitation($email)) {
            echo json_encode("uninvited");
            exit;
        }
        else if($userDAO->checkEmail($email)) {
            echo json_encode("already");
            exit;
        }
        else {
            if($userDAO->getUserByNick($nick)) {
                echo json_encode("existing");
            }
            else {
                $status = $userDAO->addnewuser($user);
                if($status) {
                    echo json_encode(true);                
                }
                else {
                    echo json_encode(false);
                }
            }            
        }
        
    }
    
    public function search() {
        
        if(!LoginController::isLogged()) {
            header("Location: ". BASE);
        }
        
        header("Content-Type: application/json; charset=utf-8");
        $nome = filter_input(INPUT_POST, "nome");
        $id = $_SESSION["id"];
        $usuarioDAO = new UsuarioDAO();     
        $lista = $usuarioDAO->pesquisar($id, htmlspecialchars($nome));
        echo json_encode($lista);
        
    }
    
}