<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ProQuo</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendor/device-mockups/device-mockups.min.css">

    <!-- Theme CSS -->
    <link href="css/new-age.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- google analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-91903725-1', 'auto');
      ga('send', 'pageview');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1281037498628845'); // Insert your pixel ID here.
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1281037498628845&ev=PageView&noscript=1"
    /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->


</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav navbar-left">
                   
                    <a href="#"><img style="height: 50px;" src="img/logoTemp.png"/></a>
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#features">Features</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                    <li>
                        <a href="forgotPassword.html">Forgot Password</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <section id="download" class="download bg-primary text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="section-heading">The easiest way to buy and sell tickets</h2>
                    <p>Available now on iOS and Android</p>
                    <div class="badges">
                        <a class="badge-link" id="googlePlayBadge" href="https://play.google.com/store/apps/details?id=com.scalpr.scalpr&hl=en"><img src="img/google-play-badge.svg" alt=""></a>
                        <a class="badge-link" id="iOSBadge" href="#"><img src="img/app-store-badge.svg" alt=""></a>
                    </div>
                    <br>
                    <center><a class="btn btn-outline btn-xl page-scroll" href="#liveTickets">View Live Tickets Near You</a></center>

                </div>
            </div>
        </div>
    </section>

    <section id="liveTickets" style="padding: 0;">
        <html>
        <head>
                <style>
                    #map { 
                        position: relative;
                        height: 600px;
                        width: 100%;
                    }

                    #markerLayer img {
                    width: 50px !important;
                    height: 50px !important;
                    border-radius: 50%;
                  }

                  #content{
                    text-align: center;
                  }

                  #firstHeading {
                    height: 40px;
                    width: 100%;
                    color: #2ecc71;
                  }

                  #attrName{
                    font-weight: bold;
                    margin-bottom: 5px;
                  }

                  #attrVenue{
                    margin-bottom: 5px;
                  }

                  #attrNumTickets{
                    margin-bottom: 5px;
                  }

                  #interested{
                    color: #2ecc71;
                    margin-bottom: 5px;
                  }

                  .image-cropper {
                        width: 125px;
                        height: 125px;
                        position: relative;
                        overflow: hidden;
                        border-radius: 50%;
                        margin-left: auto;
                        margin-right: auto;
                    }

                  #infoWindowAttrImg{
                    display: inline;
                    margin: 0 auto;
                    height: 100%;
                    width: auto;
                  }

                  #googleBadge, #appleBadge{
                    max-width: 140px;
                    width: 140px;
                  }
                </style>
        </head>
        <body>
            <div id="map"></div>
                <script>
                    var markersArray = [];
                    var hasCenteredCount = 0;

                    function initMap() {
                        var uluru = {lat: 40.0150, lng: -105.2705};  // LOCATION HERE
                        var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 13,
                                minZoom: 12,
                                scrollwheel: false, //DISABLE MAP SCROLLWHEEL
                                center: uluru
                            });


                    $.getJSON("http://ipinfo.io", function(ipinfo){
                        var latLong = ipinfo.loc.split(",");

                        var pos = new google.maps.LatLng(latLong[0],
                            latLong[1]);

                        var infowindow = new google.maps.InfoWindow({
                                content: 'Your General Location'
                            });
                        
                        var marker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            animation: google.maps.Animation.DROP
                        });

                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow.open(map, this);
                        });

                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close();
                        });


                        map.setCenter(pos);
                    });
                    

                    var myoverlay = new google.maps.OverlayView();
                      myoverlay.draw = function () {
                        //this assigns an id to the markerlayer Pane, so it can be referenced by CSS
                        this.getPanes().markerLayer.id='markerLayer'; 
                      };
                      myoverlay.setMap(map);

                    google.maps.event.addDomListener(map, 'bounds_changed', function() {
                        if(hasCenteredCount < 2){
                            showMarkers(map);
                        }
                    });

                    google.maps.event.addDomListener(map, 'dragend', function() {
                        showMarkers(map);
                    });

                    google.maps.event.addDomListener(map, 'zoom_changed', function() {
                        showMarkers(map);
                    });

                    }

                    function showMarkers(map){
                        clearMarkers();
                        var bounds = map.getBounds();

                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth()+1; //January is 0!

                            var yyyy = today.getFullYear();
                            if(dd<10){
                                dd='0'+dd;
                            } 
                            if(mm<10){
                                mm='0'+mm;
                            } 
                            var today = yyyy+"-"+mm+"-"+dd;

                            var args = {
                                latBoundLeft: bounds.getSouthWest().lat(),
                                latBoundRight: bounds.getNorthEast().lat(),
                                lonBoundLeft: bounds.getSouthWest().lng(),
                                lonBoundRight: bounds.getNorthEast().lng(),
                                currentDate: today
                            };

                            //console.log(args);

                            var getAttractionsRequest = getAttractions(args);

                            getAttractionsRequest.done(function(result) {
                                //console.log(result);
                                var attractions = JSON.parse(result);
                                
                                for(var i = 0; i < attractions.length; i++){
                                    var attraction = attractions[i];

                                   

                                    var icon = {
                                        url: attraction["imageURL"], // url
                                        scaledSize: new google.maps.Size(50,50),
                                        origin: new google.maps.Point(0,0) // origin
                                    };

                                    var marker = new google.maps.Marker({
                                        position: {lat: parseFloat(attraction["lat"]), lng: parseFloat(attraction["lon"])},
                                        map: map,
                                        icon: icon,
                                        optimized: false
                                    });

                                    if(hasCenteredCount < 2){
                                       map.setCenter({lat: parseFloat(attraction["lat"]), lng: parseFloat(attraction["lon"])});
                                       hasCenteredCount += 1;
                                    }

                                    addInfoWindow(marker, attraction);
                                }
                                    

                                }).fail(function (jqXHR, textStatus, errorThrown){
                                    
                                });
                    }


                    function clearMarkers() {
                      for (var i = 0; i < markersArray.length; i++ ) {
                        markersArray[i].setMap(null);
                      }
                      markersArray.length = 0;
                    }

                    function addInfoWindow(marker, attraction) {

                        var description = (attraction["description"] != "") ? '<p>'+attraction["description"]+"</p>" : "";

                        var date = new Date(attraction["date"]);

                        var monthNames = [
                          "Jan", "Feb", "Mar",
                          "Apr", "May", "Jun", "Jul",
                          "Aug", "Sept", "Oct",
                          "Nov", "Dec"
                        ];

                        var day = date.getDate();
                        var monthIndex = date.getMonth();
                        var year = date.getFullYear();

                        var contentString = '<div id="content">'+
                            '<h1 id="firstHeading" class="firstHeading">'+monthNames[monthIndex] + ' ' + day + ', ' + year+'</h1>'+
                            '<div id="bodyContent">'+
                            '<p id="attrName">'+attraction["name"]+"</p>"+
                            '<p id="attrVenue">'+attraction["venueName"]+"</p>"+
                            '<div class="image-cropper"><img id="infoWindowAttrImg" src="'+attraction["imageURL"]+'"/></div>'+
                            '<p id="attrNumTickets">Tickets: <b>$'+attraction["numTickets"]+"</b></p>"+
                            '<p>Price: <b>$'+attraction["ticketPrice"]+"/Ticket</b></p>"+
                            description +
                            '<p id="interested">Interested in contacting the seller?</p>'+
                            '<div><a class="badge-link" href="https://play.google.com/store/apps/details?id=com.scalpr.scalpr&hl=en"><img id="googleBadge" src="img/google-play-badge.svg" alt=""></a><a class="badge-link" href="#"><img id="appleBadge" src="img/app-store-badge.svg" alt=""></a></div>'+
                            '</div>'+
                            '</div>';

                        var infowindow = new google.maps.InfoWindow({
                          content: contentString
                        });

                        marker.addListener('click', function() {
                            infowindow.open(marker.get('map'), marker);

                            //facebook analytics
                            fbq('trackCustom', 'attraction_click', {
                                attractionID: attraction["attractionID"],
                                attractionName: attraction["name"]
                            });

                        });

                        markersArray.push(marker);
                    }


                    function getAttractions(args){

                        return $.ajax({
                            url: "scalpr_ws/get_attractions.php",
                            data: args,
                            type: "POST",
                            success: function(response) {
                                result = response;
                            }
                        });

                    }

                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAofWSHIjQOsTzKw_2fbSs-gNE5mp1sIBQ&callback=initMap">
                </script>
        </body>
        </html>   
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-heading">
                        <h2>Get Started Today</h2>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="device-container">
                        <div class="device-mockup iphone6_plus portrait white">
                            <div class="device">
                                <div class="screen">
                                    <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                                    <img src="img/demo-screen-2.png" class="img-responsive" alt=""> </div>
                                <div class="button">
                                    <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="icon-globe text-primary"></i>
                                    <h3>Worldwide Service</h3>
                                    <p class="text-muted">Buy and sell tickets anywhere in the world.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="icon-call-in text-primary"></i>
                                    <h3>Simple Connections</h3>
                                    <p class="text-muted">Contact other ProQuo users with our in app messaging.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="icon-music-tone-alt text-primary"></i>
                                    <h3>Be In The Know</h3>
                                    <p class="text-muted">Use ProQuo to see what events are going on near you.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="icon-like text-primary"></i>
                                    <h3>Free To Use</h3>
                                    <p class="text-muted">Enough said.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

   <!-- <section class="cta">
    <div class="cta-content">
        <div class="container">
            
        </div>
    </div>
    <div class="overlay"></div>
    </section> -->

    <section id="contact" class="contact bg-primary">
        <div class="container">
            <p style="color: white; font-size: 16pt; ">
                ProQuo is a map-based ticket commerce app for buying and selling tickets to nearby events such as concerts, sporting events, or anything that requires a ticket for admission. With ProQuo there’s no need to pay full price, or possibly more, for a ticket you forgot to purchase and you don’t have to lose all your money when you can’t make it to an event.
                <br><br>
                ProQuo makes it easy to buy by centering a map on your location then showing you all the tickets that are being sold around you. Tickets are posted by location and if you see a ticket you are interested in it’s as simple as touching the post then contacting the seller.
                <br><br>
                If you are looking to sell all you have to do is touch the button on the bottom right corner of the home screen and enter all your ticket details. Once entered, chose the location where you want the ticket to appear and you’re done. Interested buyers will be able to message you through the app.
            </p>
            <hr>
            <br>
            <h3>Wanna chat? Email us at support@proquo.com or check out our Facebook with the link below!</h3>
            <ul class="list-inline list-social">
                <li class="social-facebook">
                    <a href="https://www.facebook.com/proquoapp"><i class="fa fa-facebook"></i></a>
                </li>
            </ul>
        </div>
    </section>

    <footer>
        <div class="container">
            <ul class="list-inline">
                <li>
                    <a href="help/policies/privacy_policy.html">Privacy</a>
                </li>
                <li>
                    <a href="help/policies/terms_of_service.html">Terms</a>
                </li>
            </ul>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/new-age.min.js"></script>

    <script type="text/javascript">

    // facebook analytics below **************
        
    $("#iOSBadge").click(function(){
        fbq('trackCustom', 'download_app', {
        platform: 'iOS'
        });
    });

    $("#googlePlayBadge").click(function(){
        fbq('trackCustom', 'download_app', {
        platform: 'googlePlay'
        });
    });

    // facebook analytics end ******************

    </script>

</body>

</html>
