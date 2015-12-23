<?php 
	require_once("src/config.php");
	require_once("src/webauth.php");
	
	$static_root = Config::get("static_root");
?>

<html>
	<head>
		<!-- include angular.js -->
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<!-- include foundation stylesheet -->
		<link type="text/css" rel="stylesheet" href="<?php echo $static_root; ?>foundation/css/foundation.css "/>
		<!-- include custom stylesheet -->
		<link type="text/css" rel="stylesheet" href="<?php echo $static_root; ?>foundation/css/app.css "/>
 	</head>

	<body>
		<!-- begin nav bar -->
		<nav class="top-bar" data-topbar role="navigation">
			<ul class="title-area">
				<li class="name">
					<h1><a href="/partner">Detective</a></h1>
				</li>
			</ul>

			<section class="top-bar-section">
				<ul class="right">
				</ul>
			</section>
		</nav>
		<!-- end nav bar -->

		<?php // require_once($url); ?>

		<!-- js includes (for foundation)-->
		<script src="<?php echo $static_root; ?>foundation/js/vendor/jquery.js"></script>
		<script src="<?php echo $static_root; ?>foundation/js/vendor/fastclick.js"></script>
		<script src="<?php echo $static_root; ?>foundation/js/foundation.min.js"></script>
		<!-- start foundation -->
		<script>
			$(document).foundation();
		</script>

	</body>
</html>
