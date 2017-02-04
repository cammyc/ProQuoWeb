<?php
    include_once("databasehelper.php");
    Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

    $userID = $_POST['userID'];
    $conversationID = $_POST['conversationID'];

    $mysqli = getDB();

    echo userLeaveConversation($mysqli,$conversationID, $userID);

    $mysqli->close();
    
?>