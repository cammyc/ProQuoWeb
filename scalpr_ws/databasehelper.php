<?php

class UserProfile
{
	public $userID;
	public $firstName;
	public $lastName;
	public $email;
	public $phoneNumber;
	public $password;
	public $displayPicURL;
	public $facebookID;
	public $googleID;
}

class Attraction
{
	public $attractionID;
	public $creatorID;
	public $venueName;
	public $name;
	public $ticketPrice;
	public $numTickets;
	public $description;
	public $date;
	public $imageURL;
	public $lat;
	public $lon;
	public $timeStamp;
}

class Conversation
{
	public $conversationID;
	public $attractionID;
	public $buyerID;
	public $sellerID;
	public $buyerName;
	public $sellerName;
	public $title;
	public $lastMessage;
	public $creationTimestamp;
	public $attractionImageURL;
	public $isLastMessageRead;
}

class Message
{
	public $messageID;
	public $conversationID;
	public $senderID;
	public $message;
	public $timestamp;
}

class MessageNotification
{
	public $messageID;
	public $message;
	public $convoID;
	public $yourName;
	public $imageURL;
	public $attractionName;
}

class UserDeviceToken
{
	public $userID;
	public $deviceToken;
	//public $deviceType //$deviceType -> 1 = android, 2 = iOS
}

class Security {
	public static function encrypt($input, $key) {
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$input = Security::pkcs5_pad($input, $size); 
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		mcrypt_generic_init($td, $key, $iv); 
		$data = mcrypt_generic($td, $input); 
		mcrypt_generic_deinit($td); 
		mcrypt_module_close($td); 
		$data = base64_encode($data); 
		return $data; 
	} 
	private static function pkcs5_pad ($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 
	public static function decrypt($sStr, $sKey) {
		$decrypted= mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey, 
			base64_decode($sStr), 
			MCRYPT_MODE_ECB
		);
		$dec_s = strlen($decrypted); 
		$padding = ord($decrypted[$dec_s-1]); 
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	
}


function getDB(){//replace all other uses so that when site goes live I only have to change this function from root,root
  //return new mysqli("localhost", "root", "root", "biketime");
	$mysqli = new mysqli(null, "root", '$c@lpR', "Scalpr", null, "/cloudsql/scalpr-143904:us-central1:scalpr-db");
	$mysqli->set_charset('utf8mb4');
  	return $mysqli;
}

function createAccount($mysqli,$firstName, $lastName, $emailPhone, $password){
	$email = "";
	$phone = "";
	$checkQuery = "";
	$pw = password_hash($password, PASSWORD_DEFAULT);

	if(strpos($emailPhone,"@") !== false){
		$checkQuery = 'SELECT ID FROM Users WHERE Email = ?';
		$email = $emailPhone;
	}else{
		$checkQuery = 'SELECT ID FROM Users WHERE PhoneNumber = ?';
		$phone = $emailPhone;
	}



	$statement = $mysqli->prepare($checkQuery);
	$statement->bind_param("s", $emailPhone);
	$statement->execute();
	$statement->store_result();
	$statement->fetch();
	$row_count = $statement->num_rows;

	if($row_count > 0){
		$statement->close();
		return -1;
	}

	$statement->close();

	$insertQuery = 'INSERT INTO Users VALUES (NULL, ?, ?, ?, ?, ?, NULL, NULL, NULL)';

	$statement = $mysqli->prepare($insertQuery);
	$statement->bind_param("sssss", $firstName, $lastName, $email, $phone, $pw);
	$statement->execute();

  	if($statement){
  		$id = $statement->insert_id;
  		$statement->close();
  		return $id;
  	}else{
    	return 0;
  	}
}

function createAccountFBLogin($mysqli,$firstName, $lastName, $email, $fbID){

	$checkQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Scalpr.Users WHERE FacebookID = ?';

	$statement = $mysqli->prepare($checkQuery);
	$statement->bind_param("s", $fbID);
	$statement->execute();
	$u = new UserProfile();
	$statement->store_result();
	$statement->bind_result($u->userID, $u->firstName, $u->lastName, $u->email, $u->phoneNumber, $u->password, $u->displayPicURL);
	$statement->fetch();

	$row_count = $statement->num_rows;
	$statement->close();

	if($row_count > 0){
		return json_encode($u); //user logged in
	}else{
		if(empty($email)){
			return -3; //tell user to put in email, fb didn't have one
		}else{
			$emailQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Scalpr.Users WHERE Email = ?';
			$statement = $mysqli->prepare($emailQuery);
			$statement->bind_param("s", $email);
			$statement->execute();
			$u = new UserProfile();
			$statement->store_result();
			$statement->bind_result($u->userID, $u->firstName, $u->lastName, $u->email, $u->phoneNumber, $u->password, $u->displayPicURL);
			$statement->fetch();

			$row_count = $statement->num_rows;
			$statement->close();

			if($row_count > 0){
				return -2; //already email address associated with account - link fb account when logged in
			}else{
				$picURL = 'https://graph.facebook.com/'.$fbID.'/picture';
				$insertQuery = 'INSERT INTO Users VALUES (NULL, ?, ?, ?, "", NULL, ?, ?, NULL)';
				$insertStatement = $mysqli->prepare($insertQuery);
				$insertStatement->bind_param("sssss", $firstName, $lastName, $email, $picURL, $fbID);
				$insertStatement->execute();

			  	if($insertStatement){
			  		$id = $insertStatement->insert_id; //only return ID - user data is already on phone if account was just created
			  		$insertStatement->close();
			  		return $id;
			  	}else{
			    	return -1;//db error logging in/creating account
			  	}
			}
		}
	}
}

function createAccountGoogleLogin($mysqli,$firstName, $lastName, $email, $displayPicURL, $googleID){

	$checkQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Scalpr.Users WHERE GoogleID = ?';

	$statement = $mysqli->prepare($checkQuery);
	$statement->bind_param("s", $googleID);
	$statement->execute();
	$u = new UserProfile();
	$statement->store_result();
	$statement->bind_result($u->userID, $u->firstName, $u->lastName, $u->email, $u->phoneNumber, $u->password, $u->displayPicURL);
	$statement->fetch();

	$row_count = $statement->num_rows;
	$statement->close();

	if($row_count > 0){
		return json_encode($u); //user logged in
	}else{
		$emailQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Scalpr.Users WHERE Email = ?';
		$statement = $mysqli->prepare($emailQuery);
		$statement->bind_param("s", $email);
		$statement->execute();
		$u = new UserProfile();
		$statement->store_result();
		$statement->bind_result($u->userID, $u->firstName, $u->lastName, $u->email, $u->phoneNumber, $u->password, $u->displayPicURL);
		$statement->fetch();

		$row_count = $statement->num_rows;
		$statement->close();

		if($row_count > 0){
			return -2; //already email address associated with account - link fb account when logged in
		}else{
			$insertQuery = 'INSERT INTO Users VALUES (NULL, ?, ?, ?, "", NULL, ?, NULL, ?)';
			$insertStatement = $mysqli->prepare($insertQuery);
			$insertStatement->bind_param("sssss", $firstName, $lastName, $email, $displayPicURL, $googleID);
			$insertStatement->execute();

		  	if($insertStatement){
		  		$id = $insertStatement->insert_id; //only return ID - user data is already on phone if account was just created
		  		$insertStatement->close();
		  		return $id;
		  	}else{
		    	return -1;//db error logging in/creating account
		  	}
		}
	}
}

function loginCheck($mysqli,$emailPhone, $password, $retrieveUserInfo){

	$getAccountInfo = ($retrieveUserInfo === 'true') ? true: false;

	$checkQuery = "";

	if(strpos($emailPhone,"@") !== false){
		$checkQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Users WHERE Email = ?';
	}else{
		$checkQuery = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Users WHERE PhoneNumber = ?';
	}

	$statement = $mysqli->prepare($checkQuery);
	$statement->bind_param("s", $emailPhone);
	$statement->execute();

	$result = $statement->get_result();
	$row = $result->fetch_array(MYSQLI_NUM);

	$row_count = mysqli_num_rows($result);


	if($row_count == 0){
		return 0;
	}

	$statement->close();

	$passwordHash = $row[5];
	$ID = $row[0];

	if(password_verify($password, $passwordHash)){
		if($getAccountInfo){

				$u = new UserProfile();
		          $u->userID = $ID;
		          $u->firstName = $row[1];
		          $u->lastName = $row[2];
		          $u->email = $row[3];
		          $u->phoneNumber = $row[4];
		          $u->password = $row[5];
		          $u->displayPicURL = $row[6];

	        return json_encode($u);
      
		}else{
			return $ID;
		}
	}else{
		return 0;
	}	
}

function postAttraction($mysqli, $attraction){
	$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $attraction->date)));
	$timestamp = gmdate("Y-m-d H:i:s");

	$query = 'INSERT INTO Attractions VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)';
	$statement = $mysqli->prepare($query);
	$statement->bind_param("issdisssdds", $attraction->creatorID, $attraction->venueName, $attraction->name, $attraction->ticketPrice, $attraction->numTickets, $attraction->description, $mysqlDateFormat, $attraction->imageURL, $attraction->lat, $attraction->lon, $timestamp);
	$statement->execute();

	if($statement){
		$ID = $statement->insert_id;
		$statement->close();
  		return $ID;
  	}else{
    	return 0;
  	}
}

function updateAttractionDetails($mysqli, $attraction){
	$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $attraction->date)));


	$query = 'UPDATE Attractions SET VenueName = ?, Name = ?, TicketPrice = ?, NumberOfTickets = ?, Description = ?, Date = ?, ImageURL = ? WHERE ID = ? AND CreatorID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("ssdisssii", $attraction->venueName, $attraction->name, $attraction->ticketPrice, $attraction->numTickets, $attraction->description, $mysqlDateFormat, $attraction->imageURL, $attraction->attractionID, $attraction->creatorID);
	$statement->execute();

	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function updateAttractionImageURL($mysqli, $attraction){

	$query = 'UPDATE Attractions SET ImageURL = ? WHERE ID = ? AND CreatorID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("sii", $attraction->imageURL, $attraction->attractionID, $attraction->creatorID);
	$statement->execute();

	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function updateAttractionLocation($mysqli, $attraction){
	$query = 'UPDATE Attractions SET Lat = ?, Lon = ? WHERE ID = ? AND CreatorID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("ddii", $attraction->lat, $attraction->lon, $attraction->attractionID, $attraction->creatorID);
	$statement->execute();

	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function deleteAttraction($mysqli, $creatorID, $attractionID){
	$query = 'UPDATE Attractions SET DeletedByUserID = ? WHERE ID = ? AND CreatorID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("iii",$creatorID, $attractionID, $creatorID);
	$statement->execute();


	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function getAttractionImage($mysqli, $attractionID){

	$query = 'SELECT ImageURL FROM Attractions WHERE ID = ?';
	$statement = $mysqli->prepare($query);

	$statement->bind_param("i", $attractionID);
	$statement->execute();
	$result = $statement->get_result();
	
	$row = $result->fetch_array(MYSQLI_NUM);
	$imageURL = $row[0];
	$statement->close();

	return $imageURL;
}

function getAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $date){
	$minLat = 0;
	$maxLat = 0;

	$minLon = 0;
	$maxLon = 0;

	if($latBoundLeft > $latBoundRight){
		$maxLat = $latBoundLeft;
		$minLat = $latBoundRight;
	}else{
		$maxLat = $latBoundRight;
		$minLat = $latBoundLeft;
	}

	if($lonBoundLeft > $lonBoundRight){
		$maxLon = $lonBoundLeft;
		$minLon = $lonBoundRight;
	}else{
		$maxLon = $lonBoundRight;
		$minLon = $lonBoundLeft;
	}

	//$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $date)));

	$query = 'SELECT ID, CreatorID, VenueName, Name, TicketPrice, NumberOfTickets, Description, Date, ImageURL, Lat, Lon, Timestamp FROM Attractions WHERE (Lat Between ? AND ?) AND (Lon Between ? AND ?) AND (Date >= ?) AND ISNULL(DeletedByUserID)';
	$statement = $mysqli->prepare($query);

	$statement->bind_param("dddds", $minLat,$maxLat,$minLon,$maxLon, $date);
	$statement->execute();
	$result = $statement->get_result();

	$attractions = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$a = new Attraction();
		$a->attractionID = $row[0];
		$a->creatorID = $row[1];
		$a->venueName = $row[2];
		$a->name = $row[3];
		$a->ticketPrice = $row[4];
		$a->numTickets = $row[5];
		$a->description = $row[6];
		$a->date = $row[7];
		$a->imageURL = $row[8];
		$a->lat = $row[9];
		$a->lon = $row[10];
		$a->timestamp = $row[11];

		$attractions[$i] = $a;
		$i++;
	}

	$statement->close();

	return $attractions;
	
}

function getNewAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $date, $IDs, $searchViewQuery){
	$minLat = 0;
	$maxLat = 0;

	$minLon = 0;
	$maxLon = 0;

	if($latBoundLeft > $latBoundRight){
		$maxLat = $latBoundLeft;
		$minLat = $latBoundRight;
	}else{
		$maxLat = $latBoundRight;
		$minLat = $latBoundLeft;
	}

	if($lonBoundLeft > $lonBoundRight){
		$maxLon = $lonBoundLeft;
		$minLon = $lonBoundRight;
	}else{
		$maxLon = $lonBoundRight;
		$minLon = $lonBoundLeft;
	}

	$searchTerm = (!empty($searchViewQuery)) ? ' AND (Name LIKE ? OR VenueName LIKE ?)' : "";

	//$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $date)));


	$idQuery = (sizeof($IDs) > 0) ? 'AND ('.implode(" AND ", $IDs).')' : '';

	$query = 'SELECT ID, CreatorID, VenueName, Name, TicketPrice, NumberOfTickets, Description, Date, ImageURL, Lat, Lon, Timestamp FROM Attractions WHERE (Lat Between ? AND ?) AND (Lon Between ? AND ?) AND (Date >= ?)  AND ISNULL(DeletedByUserID) '.$idQuery.' '.$searchTerm.'';

	$statement = $mysqli->prepare($query);

	if(!empty($searchViewQuery)){
		$param = "%{$searchViewQuery}%";
		$statement->bind_param("ddddsss", $minLat,$maxLat,$minLon,$maxLon, $date, $param, $param);
	}else{
		$statement->bind_param("dddds", $minLat,$maxLat,$minLon,$maxLon, $date);
	}

	$statement->execute();
	$result = $statement->get_result();

	$attractions = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){

		$a = new Attraction();
		$a->attractionID = $row[0];
		$a->creatorID = $row[1];
		$a->venueName = $row[2];
		$a->name = $row[3];
		$a->ticketPrice = $row[4];
		$a->numTickets = $row[5];
		$a->description = $row[6];
		$a->date = $row[7];
		$a->imageURL = $row[8];
		$a->lat = $row[9];
		$a->lon = $row[10];
		$a->timestamp = $row[11];

		$attractions[$i] = $a;
		$i++;
	}

	$statement->close();

	if(sizeof($attractions) < 3){
		if(($maxLat - $minLat) < 1){
			return getNewAttractions($mysqli, $maxLat + 0.5, $minLat - 0.5, $maxLon + 0.5, $minLon - 0.5, $date, $IDs, $searchViewQuery);
		}else{
			return $attractions;
		}
	}else{
		return $attractions;
	}
}


function getUserAttractions($mysqli, $userID, $currentDate){

	$query = 'SELECT ID, CreatorID, VenueName, Name, TicketPrice, NumberOfTickets, Description, Date, ImageURL, Lat, Lon, Timestamp FROM Attractions WHERE CreatorID = ? AND Date >= ?  AND ISNULL(DeletedByUserID) Order By Date';
	$statement = $mysqli->prepare($query);

	$statement->bind_param("is", $userID, $currentDate);
	$statement->execute();
	$result = $statement->get_result();

	$attractions = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$a = new Attraction();
		$a->attractionID = $row[0];
		$a->creatorID = $row[1];
		$a->venueName = $row[2];
		$a->name = $row[3];
		$a->ticketPrice = $row[4];
		$a->numTickets = $row[5];
		$a->description = $row[6];
		$a->date = $row[7];
		$a->imageURL = $row[8];
		$a->lat = $row[9];
		$a->lon = $row[10];
		$a->timestamp = $row[11];

		$attractions[$i] = $a;
		$i++;
	}

	$statement->close();

	return $attractions;
}


function getUserDetails($mysqli, $userID){

	$query = 'SELECT ID, FirstName, LastName, Email, PhoneNumber, Password, DisplayPicURL FROM Users WHERE ID = ?';

	$statement = $mysqli->prepare($query);

	$statement->bind_param("i", $userID);
	$statement->execute();
	$result = $statement->get_result();

	if($statement){
		$statement->close();
		$row = $row = $result->fetch_array(MYSQLI_NUM);

		$u = new UserProfile();
	      	$u->userID = $row[0];
	      	$u->firstName = $row[1];
	      	$u->lastName = $row[2];
	      	$u->email = $row[3];
	      	$u->phoneNumber = $row[4];
	      	$u->password = $row[5];
	      	$u->displayPicURL = $row[6];

		return $u;
	}else{
		return null;
	}

	
}

function getUserContactInfo($mysqli, $userID){

	$query = 'SELECT ID, FirstName, LastName, Email, PhoneNumber FROM Users WHERE ID = ?';

	$statement = $mysqli->prepare($query);

	$statement->bind_param("i", $userID);
	$statement->execute();
	$result = $statement->get_result();

	if($statement){
		$statement->close();
		$row = $row = $result->fetch_array(MYSQLI_NUM);

		$u = new UserProfile();
	      	$u->userID = $row[0];
	      	$u->firstName = $row[1];
	      	$u->lastName = $row[2];
	      	$u->email = $row[3];
	      	$u->phoneNumber = $row[4];

		return $u;
	}else{
		return 0;
	}
}


function UpdateUserContactInfo($mysqli, $user){
	$query = 'SELECT Email,PhoneNumber FROM Users WHERE (Email = ? OR PhoneNumber = ?) AND ID != ?';


	if (empty($user->email)){
		$query = 'SELECT Email,PhoneNumber FROM Users WHERE PhoneNumber = ? AND ID != ?';
		$statement = $mysqli->prepare($query);
		$statement->bind_param("si", $user->phoneNumber, $user->userID);
	}else if(empty($user->phoneNumber)){
		$query = 'SELECT Email,PhoneNumber FROM Users WHERE Email = ? AND ID != ?';
		$statement = $mysqli->prepare($query);
		$statement->bind_param("si", $user->email, $user->userID);
	}else{
		$statement = $mysqli->prepare($query);
		$statement->bind_param("ssi", $user->email, $user->phoneNumber, $user->userID);
	}
	$statement->execute();
	$statement->store_result();
	$statement->bind_result($email, $phoneNumber);
	$statement->fetch();

	$row_count = $statement->num_rows;

	if($row_count > 0){
		$returnVal = (strcasecmp($email, $user->email) == 0) ? -1 : -2;
		return $returnVal;
	}

	$statement->close();

	$query = 'UPDATE Users SET FirstName = ?, LastName = ?, PhoneNumber = ?, Email = ? WHERE ID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("ssssi", $user->firstName, $user->lastName, $user->phoneNumber, $user->email, $user->userID);
	$statement->execute();

	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function UpdateUserPassword($mysqli, $userID, $password){

	$query = 'UPDATE Users SET Password = ? WHERE ID = ?';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("si", password_hash($password, PASSWORD_DEFAULT), $userID);
	$statement->execute();

	if($statement){
		$statement->close();
  		return 1;
  	}else{
    	return 0;
  	}
}

function CreateConversation($mysqli, $attractionID, $buyerID, $attractionName){
	$convoQuery = 'SELECT ID, IFNULL(UserIDDeleted, -1) FROM Conversations WHERE AttractionID = ? AND BuyerID = ?';

	$statement = $mysqli->prepare($convoQuery);
	$statement->bind_param("ii", $attractionID, $buyerID);
	$statement->execute();
	$statement->store_result();
	$statement->bind_result($ID, $wasDeleted);
	$statement->fetch();
	$row_count = $statement->num_rows;

	if($row_count > 0){
		if($wasDeleted != -1){//if != -1 then user left so set back to normal
			$undeleteQuery = 'UPDATE Conversations SET UserIDDeleted = NULL WHERE AttractionID = ? AND BuyerID = ?';
			$statement = $mysqli->prepare($undeleteQuery);
			$statement->bind_param("ii", $attractionID, $buyerID);
			$statement->execute();
		}
		return $ID;
	}else{
		$statement->close();

		//below is for the title, I feel like there is a much better way of doing this... I already have attraction and venue name in app, really only need seller name.
		$titleQuery = 'SELECT Attractions.Name as AttractionName, Attractions.VenueName, CONCAT(U.FirstName, " ", U.LastName) as SellerName FROM Scalpr.Attractions LEFT JOIN Scalpr.Users as U ON Attractions.CreatorID = U.ID WHERE Attractions.ID = ?';
		//$titleQuery = 'SELECT CONCAT(U.FirstName, " ", U.LastName) as SellerName FROM Scalpr.Attractions LEFT JOIN Scalpr.Users as U ON Attractions.CreatorID = U.ID WHERE Attractions.ID = ?';
		$statement = $mysqli->prepare($titleQuery);
		$statement->bind_param("i", $attractionID);
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($attractionName, $venueName, $sellerName);
		$statement->fetch();
		$statement->close();

		//Now create convo in DB
		$timestamp = gmdate("Y-m-d H:i:s");
		$title =  $attractionName." at ".$venueName." - ".$sellerName;
		//$title = $attractionName;

		$query = 'INSERT INTO Conversations VALUES(NULL, ?, ?, ?, NULL, ?)';
		$statement = $mysqli->prepare($query);
		$statement->bind_param("iiss", $attractionID, $buyerID, $title, $timestamp);
		$statement->execute();

		if($statement){
			return $statement->insert_id;
			//send message func -> "Hey, are your x at y tickets still for sale?" or make dialog in app prompt text
	  	}else{
	    	return -1;
	  	}
	}

}

function getUserConversations($mysqli, $userID){//currentDate is not needed
	$query = 'SELECT Conversations.ID as ID, AttractionID, BuyerID, U2.ID as SellerID, concat(U1.FirstName, " ", U1.LastName) as BuyerName, concat(U2.FirstName, " ", U2.LastName) as SellerName, Title,
				Message.ID as LastMessageID, Message.SenderID as LastMessageSenderID, Message.Message as LastMessageText, IFNULL(Message.TimeStamp, Conversations.Timestamp) as LastMessageTimeStamp, Conversations.TimeStamp as CreationTimeStamp, A.ImageURL as AttractionImageURL,
				CASE 
				WHEN Message.SenderID = ? THEN TRUE
				WHEN IFNULL((SELECT LastReadMessage.MessageID FROM Scalpr.LastReadMessage WHERE ConversationID = Conversations.ID AND UserID = ? ORDER BY LastReadMessage.MessageID DESC LIMIT 1), -1) != Message.ID THEN FALSE
			    ELSE TRUE
			    END as isLastMessageRead
				FROM Scalpr.Conversations
				LEFT JOIN Scalpr.Messages as Message on Message.ConversationID = Conversations.ID AND Message.ID = (SELECT ID FROM Scalpr.Messages WHERE Messages.ConversationID = Conversations.ID ORDER BY ID DESC LIMIT 1 )
				LEFT JOIN Scalpr.Users as U1 on U1.ID = Conversations.BuyerID
				LEFT JOIN Scalpr.Attractions as A on A.ID = Conversations.AttractionID
				LEFT JOIN Scalpr.Users as U2 on U2.ID = A.CreatorID
				WHERE (Conversations.BuyerID = ? OR A.CreatorID = ?) AND ISNULL(Conversations.UserIDDeleted) 
				ORDER BY LastMessageTimeStamp DESC';

	$statement = $mysqli->prepare($query);

	$statement->bind_param("iiii", $userID, $userID, $userID, $userID);
	$statement->execute();
	$result = $statement->get_result();

	$conversations = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$c = new Conversation();
		$c->conversationID = $row[0];
		$c->attractionID = $row[1];
		$c->buyerID = $row[2];
		$c->sellerID = $row[3];
		$c->buyerName = $row[4];
		$c->sellerName = $row[5];
		$c->title = $row[6];

		$m = new Message();
		$m->messageID = $row[7];
		$m->conversationID = $row[0];
		$m->senderID = $row[8];
		$m->message = $row[9];
		$m->timestamp = $row[10];

		$c->lastMessage = $m;
		$c->creationTimestamp = $row[11];
		$c->attractionImageURL = $row[12];
		$c->isLastMessageRead = $row[13];

		$conversations[$i] = $c;
		$i++;
	}

	echo json_encode($conversations);
}

function getConversationMessages($mysqli, $conversationID){
	$query = 'SELECT ID, ConversationID, SenderID, Message, TimeStamp FROM Messages WHERE ConversationID = ? ORDER BY ID DESC';
	$statement = $mysqli->prepare($query);

	$statement->bind_param("i", $conversationID);
	$statement->execute();
	$result = $statement->get_result();

	$messages = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$m = new Message();
		$m->messageID = $row[0];
		$m->conversationID = $row[1];
		$m->senderID = $row[2];
		$m->message = $row[3];
		$m->timestamp = $row[4];

		$messages[$i] = $m;
		$i++;
	}

	return $messages;
}

function sendConversationMessage($mysqli, $conversationID, $senderID, $message){
	$timestamp = gmdate("Y-m-d H:i:s");

	$query = 'INSERT INTO Messages VALUES(NULL, ?, ?, ?, ?)';
	$statement = $mysqli->prepare($query);
	$statement->bind_param("iiss", $conversationID, $senderID, $message, $timestamp);
	$statement->execute();

	if($statement){
		return $statement->insert_id;
		//send message func -> "Hey, are your x at y tickets still for sale?" or make dialog in app prompt text
  	}else{
    	return -1;
  	}
}

function getNewConversationMessages($mysqli, $conversationID, $userID){//use AND SENDERID != x, only problem is then if in convo on multiple device, user wont see own message
	$query = 'SELECT ID, ConversationID, SenderID, Message, TimeStamp FROM Messages WHERE ConversationID = ? AND SenderID != ? AND 
	ID > IFNULL((SELECT LastReadMessage.MessageID FROM Scalpr.LastReadMessage WHERE ConversationID = Messages.ConversationID AND UserID = ? ORDER BY LastReadMessage.MessageID DESC LIMIT 1), 0) 
	ORDER BY ID DESC'; //ID is more reliable in the event two messages have same timestamp
	$statement = $mysqli->prepare($query);

	$statement->bind_param("iii", $conversationID, $userID, $userID);
	$statement->execute();
	$result = $statement->get_result();

	$messages = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$m = new Message();
		$m->messageID = $row[0];
		$m->conversationID = $row[1];
		$m->senderID = $row[2];
		$m->message = $row[3];
		$m->timestamp = $row[4];

		$messages[$i] = $m;
		$i++;
	}

	return $messages;
}

function checkNewMessages($mysqli, $userID, $appVersion, $deviceType){
	//$deviceType -> 1 = android, 2 = iOS

	//return $appVersion." - ".$deviceType." - ".getMinAndroidVersion();

	if($deviceType == 1){
		if($appVersion < getMinAndroidVersion()){
			return 0;
		}
	}else if($deviceType == 2){
		if($appVersion < getMiniOSVersion()){
			return 0;
		}
	}

	$messagesToCheckQuery = 'SELECT 
							CASE WHEN (SELECT MessageID FROM Scalpr.LastReadMessage as LRM WHERE LRM.ConversationID = LNM.ConversationID AND LRM.UserID = LNM.UserID ORDER BY MessageID DESC LIMIT 1) > IFNULL(LNM.MessageID,0) THEN (SELECT MessageID FROM Scalpr.LastReadMessage as LRM WHERE LRM.ConversationID = LNM.ConversationID AND LRM.UserID = LNM.UserID ORDER BY MessageID DESC LIMIT 1)
							ELSE IFNULL(LNM.MessageID,0) END AS MsgID, 
							Conversations.ID as ConvoID FROM Scalpr.Conversations
							LEFT JOIN Scalpr.LastNotifiedMessage as LNM on LNM.ID = (SELECT ID FROM Scalpr.LastNotifiedMessage WHERE LastNotifiedMessage.ConversationID = Conversations.ID AND LastNotifiedMessage.UserID = ? ORDER BY MessageID DESC LIMIT 1) 
							LEFT JOIN Scalpr.Attractions as A ON A.ID = Conversations.AttractionID
                            WHERE (LNM.UserID = ? OR ISNULL(LNM.UserID)) AND (A.CreatorID = ? OR Conversations.BuyerID = ?) AND ISNULL(Conversations.UserIDDeleted)';
                            //this query also uses the LastReadMessages table so that a message that is read, but not notified, isn't notified again.
                            //AND LNM.UserID = ? because we only want the user requesting the last notified message

	$statement = $mysqli->prepare($messagesToCheckQuery);

	$statement->bind_param("iiii", $userID, $userID, $userID, $userID);
	$statement->execute();
	$result = $statement->get_result();

	$explodeArray = array(); //should be of form (Messages.ID > 3 AND ConversationID = 2) OR (Messages.ID > 583 AND ConversationID = 5) OR (Messages.ID > 518 AND ConversationID = 6) where numbers are value from query above
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$explodeArray[$i] = "(Messages.ID > ".$row[0]." AND Messages.ConversationID = ".$row[1].")";
		$i++;
	}

	$statement->close();

	if($i > 0){
		$query = 'SELECT Messages.ID as MessageID, Messages.Message, C.ID as ConvoID, A.ImageURL as ImageURL, 
				CASE WHEN C.BuyerID = ? THEN (SELECT Concat(FirstName, " ", LastName) FROM Scalpr.Users WHERE Users.ID = A.CreatorID LIMIT 1) 
				ELSE (SELECT Concat(FirstName, " ", LastName) FROM Scalpr.Users WHERE Users.ID = C.BuyerID LIMIT 1)
				END as otherPerson,
				A.Name as AttractionName
				FROM Scalpr.Messages 
				LEFT JOIN Scalpr.Conversations as C ON C.ID = Messages.ConversationID 
				LEFT JOIN Scalpr.Attractions as A on A.ID = C.AttractionID 
				WHERE ('.implode(" OR ", $explodeArray).') AND ISNULL(C.UserIDDeleted) AND Messages.SenderID != ? 
				ORDER BY ConvoID, Messages.ID DESC';


			$statement = $mysqli->prepare($query);
			$statement->bind_param("ii",$userID, $userID);
			$statement->execute(); //THIS IS AN ERROR
			$result = $statement->get_result();

			$mesNotArray = array();
			$i = 0;

			// $trackerCounter = 0;
			// $trackerArray = array()
			// $lastConversationID = -1;

			while($row = $result->fetch_array(MYSQLI_NUM)){
				$mesNot = new MessageNotification();
				$mesNot->messageID = $row[0];
				$mesNot->message = $row[1];
				$mesNot->convoID = $row[2];
				$mesNot->imageURL = $row[3];
				$mesNot->yourName = $row[4];
				$mesNot->attractionName = $row[5];

				// if($lastConversationID != $mesNot->convoID){
				// 	$lastConversationID = $mesNot->convoID;
				// 	$trackerCounter++;
				// }

				// $trackerArray[$trackerCounter] = $mesNot; //overwriting should occur here
				$mesNotArray[$i] = $mesNot;
				$i++;
			}	

			if($i > 0){
				//not going to update LastNotifiedMessage table here in case user doesn't receive notification - will have users phone make another request once received
				return $mesNotArray;
			}else{
				return 0;
			}
		}else{
			return 0;
		}

	
} 

function updateLastReadMessage($mysqli, $messageID, $conversationID, $userID){
	$timestamp = gmdate("Y-m-d H:i:s");
	$query = 'INSERT INTO LastReadMessage VALUES (NULL, ?, ?, ?, ?)';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("iiis", $messageID, $conversationID, $userID, $timestamp);
	$statement->execute();

  	if($statement){
  		$id = $statement->insert_id;
  		$statement->close();
  		return $id;
  	}else{
    	return 0;
  	}
}

function updateLastNotifiedMessage($mysqli, $messageID, $conversationID, $userID){
	$timestamp = gmdate("Y-m-d H:i:s");
	$query = 'INSERT INTO LastNotifiedMessage VALUES (NULL, ?, ?, ?, ?)';

	$statement = $mysqli->prepare($query);
	$statement->bind_param("iiis", $messageID, $conversationID, $userID, $timestamp);
	$statement->execute();

  	if($statement){
  		$id = $statement->insert_id;
  		$statement->close();
  		//return $messageID."-".$conversationID."-".$userID."-".$timestamp;
  		$id;
  	}else{
    	return 0;
  	}
}

function getMinAndroidVersion(){//may eventually have to sepeate this for notifications if want user to get notifications when update isn't concerning messages
	return 0.1;
}

function getMiniOSVersion(){
	return 1.0;
}



function userLeaveConversation($mysqli, $conversationID, $userID){
	$deleteQuery = 'UPDATE Conversations SET UserIDDeleted = ? WHERE ID = ?';
	$statement = $mysqli->prepare($deleteQuery);
	$statement->bind_param("ii", $userID, $conversationID);
	$statement->execute();

	if($statement){
  		return 1;
  	}else{
    	return -1; //db error
  	}
}

function updateIOSDeviceToken($mysqli, $userID, $deviceToken){

	$checkQuery = "SELECT ID FROM IOSDeviceTokens WHERE Token = ?";
	$checkStatement = $mysqli->prepare($checkQuery);
	$checkStatement->bind_param("s", $deviceToken);
	$checkStatement->execute();
	$checkStatement->store_result();
	$checkStatement->fetch();
	$row_count = $checkStatement->num_rows;

	if($row_count > 0){
		$updateQuery = "UPDATE IOSDeviceTokens SET UserID = ? WHERE Token = ?";
		$statement = $mysqli->prepare($updateQuery);
		$statement->bind_param("is", $userID, $deviceToken);
		$statement->execute();

		if($statement){
	  		return 1;
	  	}else{
	  		return -1;
	  	}
	}else{

		$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $attraction->date)));
		$timestamp = gmdate("Y-m-d H:i:s");


		$insertQuery = "INSERT INTO IOSDeviceTokens VALUES (NULL, ?, ?, ?)";
		$statement = $mysqli->prepare($insertQuery);
		$statement->bind_param("iss", $userID, $deviceToken, $timeStamp);
		$statement->execute();

		if($statement){
	  		return 1;
	  	}else{
	  		return -1;
	  	}
	}
	
}

function removeIOSDeviceToken($mysqli, $deviceToken){
	$updateQuery = "DELETE FROM IOSDeviceTokens WHERE Token = ?";
	$statement = $mysqli->prepare($updateQuery);
	$statement->bind_param("s", $deviceToken);
	$statement->execute();

	if($statement){
  		return 1;
  	}else{
  		return -1;
  	}
}

function retrieveAllIOSUserDevicesTokens($mysqli){
	$updateQuery = "SELECT UserID, Token FROM IOSDeviceTokens";
	$statement = $mysqli->prepare($updateQuery);
	$statement->execute();
	$result = $statement->get_result();

	$userDeviceTokens = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$udt = new UserDeviceToken();
		$udt->userID = $row[0];
		$udt->deviceToken = $row[1];

		$userDeviceTokens[$i] = $udt;
		$i++;
	}

	return $userDeviceTokens;
}

function updateAndroidDeviceToken($mysqli, $userID, $deviceToken){

	$checkQuery = "SELECT ID FROM AndroidDeviceTokens WHERE Token = ?";
	$checkStatement = $mysqli->prepare($checkQuery);
	$checkStatement->bind_param("s", $deviceToken);
	$checkStatement->execute();
	$checkStatement->store_result();
	$checkStatement->fetch();
	$row_count = $checkStatement->num_rows;

	if($row_count > 0){
		$updateQuery = "UPDATE AndroidDeviceTokens SET UserID = ? WHERE Token = ?";
		$statement = $mysqli->prepare($updateQuery);
		$statement->bind_param("is", $userID, $deviceToken);
		$statement->execute();

		if($statement){
	  		return 1;
	  	}else{
	  		return -1;
	  	}
	}else{

		$mysqlDateFormat = date('Y-m-d', strtotime(str_replace('-', '/', $attraction->date)));
		$timestamp = gmdate("Y-m-d H:i:s");


		$insertQuery = "INSERT INTO AndroidDeviceTokens VALUES (NULL, ?, ?, ?)";
		$statement = $mysqli->prepare($insertQuery);
		$statement->bind_param("iss", $userID, $deviceToken, $timeStamp);
		$statement->execute();

		if($statement){
	  		return 1;
	  	}else{
	  		return -1;
	  	}
	}
	
}

function removeAndroidDeviceToken($mysqli, $deviceToken){
	$updateQuery = "DELETE FROM AndroidDeviceTokens WHERE Token = ?";
	$statement = $mysqli->prepare($updateQuery);
	$statement->bind_param("s", $deviceToken);
	$statement->execute();

	if($statement){
  		return 1;
  	}else{
  		return -1;
  	}
}

function retrieveAllAndroidUserDevicesTokens($mysqli){
	$updateQuery = "SELECT UserID, Token FROM AndroidDeviceTokens";
	$statement = $mysqli->prepare($updateQuery);
	$statement->execute();
	$result = $statement->get_result();

	$userDeviceTokens = array();
	$i = 0;

	while($row = $result->fetch_array(MYSQLI_NUM)){
		$udt = new UserDeviceToken();
		$udt->userID = $row[0];
		$udt->deviceToken = $row[1];

		$userDeviceTokens[$i] = $udt;
		$i++;
	}

	return $userDeviceTokens;
}

	$isValid = false;
	// /if(!empty())
	$value = $_SERVER['HTTP_SCALPRVERIFICATION'];
	$key = '$c@lPrK3Y1236547';
	$val = Security::decrypt($value, $key);
	if(strcmp($val, "WheresTheClosestKanyeTicketAt?") == 0){
		$isValid = true;
	}

	if (!$isValid){
		echo 0;
		exit(0);
	}

?>