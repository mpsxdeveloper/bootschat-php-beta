<?php

class ConnectionFactory {

    public static function connect() {

        global $config;
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];
        $dbuser = $config['dbuser'];
        $dbpass = $config['dbpass'];
        try {
            $connection = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);                        
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
        return $connection;
    }

    public static function disconnect($conn) {
        $this->connection = $conn;
    }
    
}
