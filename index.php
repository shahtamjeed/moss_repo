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
					<h1><a href="/"></a></h1>
				</li>
			</ul>

			<section class="top-bar-section">
				<ul class="right">
				</ul>
			</section>
		</nav>
		<!-- end nav bar -->

		<!-- init angular app -->
		<div ng-app="app" ng-controller="getData" class="row fullwidth">

			<br>

			<div class="small-12 panel">
				<table>
					<tr>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='name'; reverseSort = !reverseSort">
								Name
          					</a>
						</th>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='course'; reverseSort = !reverseSort">
								Course
          					</a>
						</th>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='quarter'; reverseSort = !reverseSort">
								Quarter
          					</a>
						</th>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='year'; reverseSort = !reverseSort">
								Year
          					</a>
						</th>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='created'; reverseSort = !reverseSort">
								Uploaded Date
          					</a>
						</th>
						<th class="text-center">
        					<a href="#" ng-click="orderByField='uploaded_by'; reverseSort = !reverseSort">
								Uploaded By
          					</a>
						</th>
					</tr>

					<tr ng-repeat="r in results|orderBy:orderByField:reverseSort">
						<td class="text-center"><a href="{{ r.location }}">{{ r.name }}</a></td>
						<td class="text-center">{{ r.course }}</td>
						<td class="text-center">{{ r.quarter }}</td>
						<td class="text-center">{{ r.year }}</td>
						<td class="text-center">{{ r.created }}</td>
						<td class="text-center">{{ r.ucinetid }}</td>
					</tr>
				</table>
			</div>

		</div>

		<!-- include angular app -->
		<script src="<?php echo $static_root; ?>js/app.js"></script>
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
