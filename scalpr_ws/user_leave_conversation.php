<?php
    include_once("databasehelper.php");

    $userID = $_POST['userID'];
    $conversationID = $_POST['conversationID'];

    $mysqli = getDB();

    echo userLeaveConversation($mysqli,$conversationID, $userID);

    $mysqli->close();
    
?>