<?php
    include_once("databasehelper.php");
    $deviceToken = $_POST['deviceToken'];

   

        $mysqli = getDB();

        $result = removeAndroidDeviceToken($mysqli, $deviceToken);

        echo $result;

        $mysqli->close();

    
?>