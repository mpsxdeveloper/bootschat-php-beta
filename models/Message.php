<?php

class Message implements JsonSerializable {

    private $id;
    private $room_id;
    private $sender_id;
    private $receiver_id;
    private $datemsg;
    private $msg;
    private $type;
    
    public function jsonSerialize() : mixed {
        $vars = get_object_vars($this);
        return $vars;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getRoom_id() {
        return $this->room_id;
    }

    public function getSender_id() {
        return $this->sender_id;
    }

    public function getReceiver_id() {
        return $this->receiver_id;
    }

    public function getDatemsg() {
        return $this->datemsg;
    }

    public function getMsg() {
        return $this->msg;
    }

    public function getType() {
        return $this->type;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setRoom_id($room_id) {
        $this->room_id = $room_id;
        return $this;
    }

    public function setSender_id($sender_id) {
        $this->sender_id = $sender_id;
        return $this;
    }

    public function setReceiver_id($receiver_id) {
        $this->receiver_id = $receiver_id;
        return $this;
    }

    public function setDatemsg($datemsg) {
        $this->datemsg = $datemsg;
        return $this;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
}
