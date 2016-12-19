<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('public'); ?>/css/main.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('public'); ?>/css/animate.css">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('public'); ?>/images/favicon.ico">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<!-- Angular JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>  
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular-route.min.js"></script>
		<!-- MY App -->
		<script src="<?php echo base_url('public'); ?>/js/app/packages/dirPagination.js"></script>
		<script src="<?php echo base_url('public'); ?>/js/app/routes.js"></script>
		<script src="<?php echo base_url('public'); ?>/js/app/services/myServices.js"></script>
		<script src="<?php echo base_url('public'); ?>/js/app/helper/myHelper.js"></script>
		<!-- App Controller -->
		<script src="<?php echo base_url('public'); ?>/js/app/controllers/CalendarController.js"></script>
		<script src="<?php echo base_url('public'); ?>/js/app/controllers/HomeController.js"></script>
		<script src="<?php echo base_url('public'); ?>/js/app/controllers/ClientController.js"></script>
		<script src="//anglibs.github.io/angular-location-update/angular-location-update.min.js"></script>
		<script type="text/javascript">

		function cycleMessage()
		{

			var index = 0;
		    var messages = document.getElementById('message-board').children;

		    setInterval(function () {
		        // Get the next index.  If at end, restart to the beginning.
		        var prev = index;
		        messages[index].classList = 'messages animated slideOutRight';
		       
		        index++;
		        if(index >= 3)
		        {
		        	index = 0;
		        }
		        // Show the next image.
		        messages[index].classList ='messages animated slideInLeft show';
		    }, 7000);
		}

		function cycleBack()
		{
			var index = 0;
			var images = document.getElementById('backgroundImages').children;

			if(document.getElementById('message-board'))
			{
				cycleMessage();
			}
		    
		    setInterval(function () {
		        // Get the next index.  If at end, restart to the beginning.
		        var prev = index;
		       
		        images[index].classList.remove('show');
		        index++;
		        if(index >= 3)
		        {
		        	index = 0;
		        }
		        // Show the next image.
		     
		        images[index].classList.add('show');
		        // Hide the previous image.
		    }, 7000);
		};

		</script>
		
		<title>On-Track Banking</title>
	</head>
	<body ng-app="main-App" onload="cycleBack();">
	<div id="backgroundImages">
		<div class="background-image toggle-image first-image show"></div>
		<div class="background-image toggle-image second-image"></div>
		<div class="background-image toggle-image third-image"></div>
	</div>
		<header>
			<div id="subheader2">
				<a href="#/" id="home"><img src="<?php echo base_url('public'); ?>/images/titleLogo.png" alt="On-Track Banking"></a>
				<p>New Accounts: 555-5555</p>
				<p>Service: 555-555</p>
				<nav id="menu-header">
					<div class="menu-top" id="register-nav"><a href="#/register">REGISTER</a></div>
					<div class="menu-top" id="about-nav"><a href="#/aboutus">ABOUT US</a></div>
					<div class="menu-top" id="login-nav"><a href="#/login">LOG IN</a></div>
				</nav>
			</div>
			<div id="mainheader" class="subheader">
				<a href="#/" id="home-alt" class="home-pic"><img src="<?php echo base_url('public'); ?>/images/newLogo.png" alt="On-Track Banking"></a>
				<p>New Accounts: 555-5555</p>
				<p>Service: 555-555</p>
				<div class="menu-top" id="register-main"><a href="#/register">REGISTER</a></div>
				<div class="menu-top" id="about-main"><a href="#/aboutus">ABOUT US</a></div>
				<div class="menu-top" id="login-main"><a href="#/login">LOG IN</a></div>
			</div>
		</header>

		<div id="container">
			<div id="content">
				<ng-view></ng-view>

		
			
