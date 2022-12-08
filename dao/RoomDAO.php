<?php

class RoomDAO {
    
    public function listRooms() {
        
        $rooms = array();
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT r.id, r.description, 
                   (SELECT COUNT(ur.room_id) FROM users_rooms ur 
                   WHERE ur.room_id = r.id) AS total FROM rooms r ORDER BY r.id";
            $rs = $connection->query($sql);
            $rs->execute();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $room = array(
                        "id" => $row["id"],
                        "description" => $row["description"],
                        "total" => $row["total"]
                    );
                    array_push($rooms, $room);
                }
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return $rooms;
        
    }
    
    public function addUsersRooms($user_id, $room_id) {
    
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO users_rooms (user_id, room_id) VALUES (:user_id, :room_id)";
            $rs = $connection->prepare($sql);
            $rs->bindParam(":user_id", $user_id);
            $rs->bindParam(":room_id", $room_id);
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
    
    public function deleteUsersRooms($user_id, $room_id) {
    
        try {
            $connection = ConnectionFactory::connect();
            $sql = "DELETE FROM users_rooms WHERE user_id = :user_id AND room_id = :room_id";
            $rs = $connection->prepare($sql);
            $rs->bindParam(":user_id", $user_id);
            $rs->bindParam(":room_id", $room_id);
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
    
}