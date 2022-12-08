<?php

class UserDAO {
    
    public function getUser($id) {
        
        try {
            $user = new User();
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $id);
            $rs->execute();
            if($rs->rowCount() > 0) {
                $row = $rs->fetch(PDO::FETCH_ASSOC);
                $user->setId($row["id"]);
                $user->setNickname($row["nickname"]);
                return $user;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
        
    }
    
    public function getUserByNick($nickname) {
        
        try {
            $user = new User();
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE nickname = :nickname";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":nickname", $nickname);
            $rs->execute();
            if($rs->rowCount() > 0) {                
                return $user;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
    public function addnewuser(User $user) {        
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO users (email, nickname, password) VALUES (:email, :nickname, :password)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $user->getEmail());
            $rs->bindValue(":nickname", $user->getNickname());
            $rs->bindValue(":password", $user->getPassword());
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
    public function login(User $u) {

        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $u->getEmail());
            $rs->execute();
            if($rs->rowCount() > 0) {
                $row = $rs->fetch(PDO::FETCH_OBJ);
                $user = new User();
                if(password_verify($u->getPassword(), $row->password)) {
                    $list = array(
                        'user' => array()
                    );
                    $user->setPassword("");
                    $user->setId($row->id);
                    $user->setEmail($row->email);
                    $user->setNickname($row->nickname);
                    $list["user"] = $user;                    
                    return $list;
                }
                else {
                    return null;
                }
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;

    }
    
    public function checkEmail($email) {

        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT email FROM users WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;   
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;

    }
    
    public function checkEmailInvitation($email) {

        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT email FROM invites WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;   
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;

    }
    
    public function getUsersByRoom($room_id) {        
        
        $list = array();
        try {            
            $connection = ConnectionFactory::connect();
            $sql = "SELECT DISTINCT u.id, u.nickname FROM users u 
                   INNER JOIN users_rooms ur ON ur.user_id = u.id 
                   WHERE ur.room_id = :room_id";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $room_id);
            $rs->execute();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $user = new User();
                    $user->setId($row->id);
                    $user->setNickname($row->nickname);                    
                    array_push($list, $user);
                }
            }            
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return $list;

    }
    
    public function addUserRoom($room_id, $user_id) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO users_rooms (user_id, room_id) VALUES (:room_id, :user_id)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $room_id);
            $rs->bindValue(":user_id", $user_id);
            $rs->execute();
            return true;
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;

    }
    
    public function getEmailFromInvitations($email) {        
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT id FROM invites WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);            
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
}