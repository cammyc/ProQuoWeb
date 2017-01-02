<?php
    include_once("databasehelper.php");

    $userID = $_POST['userID'];
    $conversationID = $_POST['conversationID'];

    // Required field names
    $required = array('userID', 'conversationID');

    // Loop over field names, make sure each one exists and is not empty
    $error = false;
    foreach($required as $field) {
      if (empty($_POST[$field])) {
        $error = true;
      }
    }

    if ($error) {
      echo -1;//i think error is here
    } else {
        $mysqli = getDB();

        echo userLeaveConversation($mysqli,$conversationID, $userID);

        $mysqli->close();
    }

    
?>