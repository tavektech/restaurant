<?php

    $database= new mysqli("localhost","root","","game");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>