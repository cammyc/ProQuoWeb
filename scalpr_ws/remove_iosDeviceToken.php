<?php
    include_once("databasehelper.php");
    $deviceToken = $_POST['deviceToken'];

   

        $mysqli = getDB();

        $result = removeIOSDeviceToken($mysqli, $deviceToken);

        echo $result;

        $mysqli->close();

    
?>