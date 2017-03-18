<?php
        header('Content-Type: application/json');
        
        define('DB_HOST', '127.0.0.1');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', 'root123');
        define('DB_NAME', 'harassment');
        
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if(!$mysqli){
            die("Connection failed: " . $mysqli->error);
        }
        
        $query = sprintf("SELECT racial, sexual, religious, workplace, other from harassmentType ORDER BY type_id");
        
        $result = $mysqli -> query($query);
        
        $data = array();
        foreach($result as $row){
            $data[] = $row
        }
        
        $result -> close();
        
        $mysqli -> close();
        
        print json_encode($data);
?>