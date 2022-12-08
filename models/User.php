<?php

class User implements JsonSerializable {

    private $id;
    private $email;
    private $nickname;
    private $password;
    
    public function jsonSerialize() : mixed {
        $vars = get_object_vars($this);
        return $vars;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
}