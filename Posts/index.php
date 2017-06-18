<?php
include_once("../scalpr_ws/databasehelper.php");

$mysqli = getDB();
$attractionID = $_GET["id"];

$attraction = getSingleAttraction($mysqli, $attractionID);

$color = ($attraction->postType == 1) ? "#2ecc71" : "#3498db";
$requesterOrSeller = ($attraction->postType == 2) ? "request" : "sell";
$requestedOrSold = ($attraction->postType == 2) ? "REQUESTED" : "SOLD";


$mysqli->close();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
  	<title><?php echo $attraction->name.' at '.$attraction->venueName; ?></title>
  	<!-- Open Graph data -->
  	<?php
  		$numTixAndPrice = "Number of Tickets: ".$attraction->numTickets." • Ticket Price: $".$attraction->ticketPrice."/Ticket";
  		$description = (!empty($attraction->description)) ? $attraction->description." • ".$numTixAndPrice : $numTixAndPrice;

  	?>

	<meta name="description" content="<?php  echo htmlspecialchars($description, ENT_QUOTES); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($attraction->name, ENT_QUOTES).','.htmlspecialchars($attraction->venueName, ENT_QUOTES); ?>,belive,ticket,event,buy,sell,request,attraction,concert,sports,stadium,venue,song, proquo">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  	<!-- Open Graph Data -->
    <meta property="og:title" content="<?php echo htmlspecialchars($attraction->name, ENT_QUOTES).' at '.htmlspecialchars($attraction->venueName, ENT_QUOTES); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://belivetickets.com/Posts/index.php?id=<?php echo $attractionID; ?>" />
    <meta property="og:image" content="<?php  echo htmlspecialchars($attraction->imageURL, ENT_QUOTES); ?>" />
    <meta property="og:description" content="<?php  echo htmlspecialchars($description, ENT_QUOTES); ?>" />
    <meta property="og:site_name" content="BeLive" />
    <meta property="og:locale" content="en_US" />

    <link rel="canonical" href="http://belivetickets.com/Posts/index.php?id=<?php echo $attractionID; ?>" />

     <!-- Favicons -->
        <link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet"/>
        <link rel="shortcut icon" href="./favicon.ico">
        <link rel="apple-touch-icon" href="../images/favicon/icon-128.png">
        <link rel="icon" href="../images/favicon/icon-32.png" sizes="32x32">
        <link rel="shortcut icon" sizes="196x196" href="../images/favicon/icon-256.png">
        <link rel="icon" type="image/png" href="../images/favicon/icon-256.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="../images/favicon/icon-128.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="../images/favicon/icon-32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="../images/favicon/icon-16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="../images/favicon/icon-128.png" sizes="128x128" />
        <link rel="mask-icon" href="../images/favicon/BeLive.svg">

  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <style>

    	html {
    		width: 100%;
    		height: 100%;
    		font-family: helvetica;
    	}

    	body {
    		margin: 0;
    		padding: 0;
    		width: 100%;
    		height: 100%;
    	}

    	#map {
        	height: 100%;
        	width: 100%;
    	}

    	img[src="<?php echo $attraction->imageURL; ?>"]{
	    	/*border-radius:50%;
	    	width: 50px;
	    	height: 50px;*/
	    	position: relative;
			border-radius: 50%;
			width: 100%;
			height: auto;
			padding-top: 100%;
			background: white;
	  	}

	  	#requestSell{
	  		z-index: 5;
	  		position: absolute;
		    width: 250px;
		    padding-left: 10px;
		    padding-right: 10px;
	  		left: 50%;
	  		margin-left: -135px;
	  		bottom: 20px;
	  		box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 1px 5px 0 rgba(0,0,0,0.12), 0 3px 1px -2px rgba(0,0,0,0.2);
	  		background: <?php echo $color; ?>;
	  	}

	  	#requestSellText{
	  		color: #fff;
	  		font-weight: bold;
	  		text-align: center;
	  	}



	  	/****************** INFOWINDOW Start **********************/




	  	#content{
	  		width: 350px;
	  		height: auto;
	  	}

	  	#dateContainer{
	  		background: <?php echo $color; ?>;
	  	}

	  	#dateText{
	  		padding: 10px;
		    text-align: center;
		    color: #FFF;
		    margin: 0;
		    font-size: 16pt;
	  	}

	  	#bodyContent{

	  	}

	  	#attractionName{
	  		font-size: 15pt;
    		text-align: center;
	  	}

	  	#venueName{
	  		text-align: center;
	  	}

	  	#numTickets{
	  		text-align: center;
	  	}

	  	#price{
	  		text-align: center;
	  	}

	  	#description{
	  		text-align: center;
	  	}

	  	#downloadDiv{
	  		position: relative;
	  		width: 280px;
	  		left: 50%;
	  		margin-left: -140px;
	  	}

		  	.badge-link img{
	    		height: 40px;
	    	}

	    	#googlePlayBadge{
	    		float: left;
	    		left: 0;
	    	}

	    	#iOSBadge{
	    		float: right;
	    		right: 0;
	    	}

	  	.roundedImage {
		    height: 100px;
		    width: 100px;
		    -webkit-border-radius: 50%;
		    -moz-border-radius: 50%;
		    -ms-border-radius: 50%;
		    -o-border-radius: 50%;
		    border-radius: 50%;
		    background:url("<?php echo addslashes($attraction->imageURL); ?>") center no-repeat;
		    background-size:cover;
		}

		.gm-style-iw {
		   width: 350px !important;
		   top: 15px !important; /*// move the infowindow 15px down*/
		   left: 25px !important;
		   background-color: #fff;
		   box-shadow: 0 1px 6px rgba(106, 106, 106, 0.6);
		   /*border: 1px solid rgba(72, 181, 233, 0.6);*/
		   border-radius: 2px 2px 0 0;
		}


		/**************** MOBILE ******************/

		@media screen and (max-width: 480px) {
		    #content{
		  		width: 274px;
		  		height: auto;
	  		}

	  		.gm-style-iw {
			   width: 274px !important;
			   top: 15px !important; /*// move the infowindow 15px down*/
			   left: 25px !important;
			   background-color: #fff;
			   box-shadow: 0 1px 6px rgba(106, 106, 106, 0.6);
			   /*border: 1px solid rgba(72, 181, 233, 0.6);*/
			   border-radius: 2px 2px 0 0;
			}

			#downloadDiv{
		  		position: relative;
		  		width: 250px;
		  		left: 50%;
		  		margin-left: -125px;
		  	}

			  	.badge-link img{
		    		height: 35px;
		    	}
		}

    </style>
  </head>
  <body>
    <div id="map"></div>

    <div id="requestSell">
    	
    	<p id="requestSellText">This ticket is being <?php echo $requestedOrSold; ?></p>

    </div>

    <script>
      function initMap() {
        var coords = {lat: <?php  echo $attraction->lat; ?>, lng: <?php  echo $attraction->lon; ?>};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: coords
        });

        var description =  '<?php echo (!empty($attraction->description)) ? '<p id="description">'.trim(preg_replace('/\s+/', ' ', htmlspecialchars($attraction->description))).'</p>' : ""; ?>';  


        var contentString = '<div id="content">'+
							      '<div id="dateContainer">'+
							      	'<h1 id="dateText"><?php echo date_format(date_create($attraction->date), 'F jS, Y'); ?></h1>'+
							      '</div>'+
							      '<div id="bodyContent">'+
								      '<h1 id="attractionName"><?php echo addslashes($attraction->name); ?></h1>'+
								      '<p id="venueName"><?php echo addslashes($attraction->venueName); ?></p>'+
								      '<center><div class="roundedImage"></div></center>'+
								      '<p id="numTickets">Tickets: <b><?php echo addslashes($attraction->numTickets); ?></b></p>'+
								      '<p id="price">Price: <b>$<?php echo addslashes($attraction->ticketPrice); ?>/Ticket</b></p>'+
								      description +
								      '<p id="price"><b>Want to contact the <?php echo $requesterOrSeller; ?>er?</b></p>'+
								      '<div id="downloadDiv">'+
									      '<a class="badge-link" id="googlePlayBadge" href="https://play.google.com/store/apps/details?id=com.scalpr.scalpr&hl=en"><img src="../images/google-play-badge.svg" alt=""/>'+
									      '<a class="badge-link" id="iOSBadge" href="https://itunes.apple.com/us/app/proquo/id1171316729?ls=1&mt=8"><img src="../images/app-store-badge.svg" alt=""></a>'+ 
								      '</div>'+
							      '</div>'+
						      '</div>';

		var infowindow = new google.maps.InfoWindow({
		    content: contentString
		});

		google.maps.event.addListener(infowindow, 'domready', function() {

		   // Reference to the DIV which receives the contents of the infowindow using jQuery
		   var iwOuter = $('.gm-style-iw');
		   iwOuter.css({left: "25px"});

		   /* The DIV we want to change is above the .gm-style-iw DIV.
		    * So, we use jQuery and create a iwBackground variable,
		    * and took advantage of the existing reference to .gm-style-iw for the previous DIV with .prev().
		    */
		   var iwBackground = iwOuter.prev();

		   // Remove the background shadow DIV
		   iwBackground.children(':nth-child(2)').css({'display' : 'none'});

		   // Remove the white background DIV
		   iwBackground.children(':nth-child(4)').css({'display' : 'none'});

		 //   // Moves the shadow of the arrow 76px to the left margin 
			// iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 150px !important;'});

			// // Moves the arrow 76px to the left margin 
			// iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 150px !important;'});

			iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

			// Taking advantage of the already established reference to
			// div .gm-style-iw with iwOuter variable.
			// You must set a new variable iwCloseBtn.
			// Using the .next() method of JQuery you reference the following div to .gm-style-iw.
			// Is this div that groups the close button elements.
			var iwCloseBtn = iwOuter.next();

			// Apply the desired effect to the close button
			iwCloseBtn.css({
			  opacity: '1', // by default the close button has an opacity of 0.7
			  right: '13px', top: '3px', // button repositioning
			  border: '7px solid <?php echo $color; ?>', // increasing button border and new color
			  'border-radius': '13px', // circular effect
			  'box-shadow': '0 0 5px #3990B9' // 3D effect to highlight the button
			  });

			// The API automatically applies 0.7 opacity to the button after the mouseout event.
			// This function reverses this event to the desired value.
			iwCloseBtn.mouseout(function(){
			  $(this).css({opacity: '1'});
			});


		});

        var marker = new google.maps.Marker({
          position: coords,
          map: map,
          icon: { url: "<?php  echo $attraction->imageURL; ?>",
       			  scaledSize: new google.maps.Size(50,50),
    			  origin: new google.maps.Point(0, 0)
       			},
          animation: google.maps.Animation.DROP,
	      title: "<?php echo addslashes($attraction->name); ?>",
	      optimized: false
        });

        marker.addListener('click', function() {
		    infowindow.open(map, marker);
		});

		infowindow.open(map, marker);

      }
    </script>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTdWruMNgtn_OBwVZtPXeTXwhroo2KIyE&callback=initMap">
    </script>
  </body>
</html>