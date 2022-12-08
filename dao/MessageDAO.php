<?php

class MessageDAO {
    
    public function sendMessage(Message $message) {        
        
        try {
            $connection = ConnectionFactory::connect();            
            $sql = "INSERT INTO messages 
                (room_id, sender_id, receiver_id, datemsg, msg, type) VALUES 
                (:room_id, :sender_id, :receiver_id, :datemsg, :msg, :type)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $message->getRoom_id());
            $rs->bindValue(":sender_id", $message->getSender_id());
            $rs->bindValue(":receiver_id", $message->getReceiver_id());
            $rs->bindValue(":datemsg", $message->getDatemsg());
            $rs->bindValue(":msg", $message->getMsg());
            $rs->bindValue(":type", $message->getType());
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
    
    public function getMessages($last_time, $room_id, $id) {
        
        $list = array();
        try {
            $connection = ConnectionFactory::connect();            
            $sql = "SELECT DISTINCT
                        m.id,
                        m.sender_id,
                        m.receiver_id,
                        m.msg,
                        m.datemsg,
                        m.type,
                        us.id, 
                        us.nickname AS nicksender,
                        ur.id, 
                        ur.nickname AS nickreceiver
                    FROM
                        messages m
                    LEFT JOIN users as us on m.sender_id = us.id
                    LEFT JOIN users as ur on m.receiver_id = ur.id
                    WHERE
                        (m.datemsg > :datemsg AND m.room_id = :room_id) AND (m.receiver_id = :idx OR m.sender_id = :idy
                        OR m.receiver_id = 0 OR m.receiver_id = :idz AND m.sender_id = :idw)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":datemsg", $last_time);
            $rs->bindValue(":room_id", intval($room_id));
            $rs->bindValue(":idx", $id);
            $rs->bindValue(":idy", $id);
            $rs->bindValue(":idz", $id);
            $rs->bindValue(":idw", $id);
            $rs->execute();       
            if($rs->rowCount() > 0) {
                $list = $rs->fetchAll(PDO::FETCH_ASSOC);
            }
	    }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return $list;
        
    }
    
    public function getLastMessageMinutes($roomID) {

        $list = array();
        try {
            $connection = ConnectionFactory::connect();
            $now = date('Y-m-d H:i:s');
            $sql = "SELECT DISTINCT sender_id, datemsg, u.id
                    FROM messages
                    INNER JOIN users u ON u.id = sender_id 
                    INNER JOIN users_rooms ur ON ur.room_id <> 0
                    WHERE datemsg IN 
                    (SELECT MAX(datemsg) FROM messages WHERE room_id = :room_id GROUP BY sender_id)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $roomID);
            $rs->execute();
            if($rs->rowCount() > 0) {       
                while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $d1 = new DateTime($now);
                    $d2 = new DateTime($row["datemsg"]);
                    $minutes = $d1->diff($d2);
                    if($minutes->i > 5) { 
                        array_push($list, $row["sender_id"]);
                    }
                }
            }             
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return $list;

    }
    
    public function deleteMessages() {      

        try {
            $connection = ConnectionFactory::connect();
            $now = date('Y-m-d H:i:s');
            $before = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($now)));
            var_dump($now);
            var_dump($before);
            $sql = "SELECT id, type, msg FROM messages WHERE datemsg < :before";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":before", $before);
            $rs->execute();            
            if($rs->rowCount() > 0) {                
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $sqldel = "DELETE FROM messages WHERE id = :id";
                    $rsdel = $connection->prepare($sqldel);
                    $rsdel->bindValue(":id", $row->id);
                    $rsdel->execute();
                    if($row->type == "image") {
                        unlink(BASE."media/images/".$row->msg);
                    }
                }
                return true;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }        
        return false;
        
    }
    
}