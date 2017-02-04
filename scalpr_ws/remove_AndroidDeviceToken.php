<?php
    include_once("databasehelper.php");
    Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

    $deviceToken = $_POST['deviceToken'];

   

        $mysqli = getDB();

        $result = removeAndroidDeviceToken($mysqli, $deviceToken);

        echo $result;

        $mysqli->close();

    
?>