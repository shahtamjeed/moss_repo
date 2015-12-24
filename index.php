<?php 
	require_once("src/config.php");
	require_once("src/results.php");
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
					<h1><a href="/">Detective</a></h1>
				</li>
			</ul>

			<section class="top-bar-section">
				<ul class="right">
				</ul>
			</section>
		</nav>
		<!-- end nav bar -->

		<!-- get data -->
		<?php 
			$r = new Results();
			$results = $r->get();
		?>

		<script>
			var results = <?php echo $results; ?>;
			console.log(results);
		</script>

		<!-- display it -->
		<div ng-app="" ng-init="results=<?php echo $results; ?>" class="row fullwidth">

			<br>

			<div class="small-12 panel">
				<table>
					<tr>
						<th class="text-center">Name</th>
						<th class="text-center">Quarter</th>
						<th class="text-center">Year</th>
						<th class="text-center">Upload Date</th>
						<th class="text-center">Uploaded By</th>
					</tr>

					<tr ng-repeat="r in results">
						<td class="text-center"><a href="{{ r.location }}">{{ r.name }}</a></td>
						<td class="text-center">{{ r.quarter }}</td>
						<td class="text-center">{{ r.year }}</td>
						<td class="text-center">{{ r.created }}</td>
						<td class="text-center">{{ r.uploaded_by }}</td>
					</tr>
				</table>
			</div>

		</div>

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
