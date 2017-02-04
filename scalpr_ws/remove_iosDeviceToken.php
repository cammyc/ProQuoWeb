<?php
    include_once("databasehelper.php");
    Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

    $deviceToken = $_POST['deviceToken'];

   

        $mysqli = getDB();

        $result = removeIOSDeviceToken($mysqli, $deviceToken);

        echo $result;

        $mysqli->close();

    
?>