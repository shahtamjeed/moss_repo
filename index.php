<?php 
	require_once("src/config.php");
	require_once("src/results.php");
	require_once("src/user.php");
	require_once("src/webauth.php");
	
	$static_root = Config::get("static_root");
	$user = new User("sherronb");
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
					<h1><a href="/">Moss Archive</a></h1>
				</li>
			</ul>

			<section class="top-bar-section">
				<ul class="left">
					<?php if ($user->is_admin()) { ?>
						<li class="active"><a href="#">Admin</a></li>
					<?php } ?>
				</ul>
				<ul class="right">
				</ul>
			</section>
		</nav>
		<!-- end nav bar -->

		<!-- init angular app -->
		<div ng-app="app" ng-controller="getData">

			<br>

			<!-- filter form -->
			<div class="row fullwidth">
				<form>
					<div class="small-2 columns">
						<h2>Filters:</h2>
					</div>
					<div class="small-2 columns">
						<span class="label">Course</span>
						<select name="course" ng-model="filters.course" ng-change="filter(filters)">
							<option ng-repeat="course in courses" value="{{ course.id }}">
								{{ course.course_name | uppercase }}
							</option>
						</select>
					</div>
					<div class="small-2 columns">
						<span class="label">Quarter</span>
						<select name="quarter" ng-model="filters.quarter" ng-change="filter(filters)">
							<option ng-repeat="quarter in quarters" value="{{ quarter.id }}">
								{{ quarter.quarter_name | capitalize }}
							</option>
						</select>
					</div>
					<div class="small-2 columns">
						<span class="label">Year</span>
						<input name="year" type="number" ng-model="filters.year"
						ng-change="filter(filters)" value="<?php echo date('Y'); ?>">
					</div>
					<div class="small-2 columns">
						<span class="label">Uploaded By</span>
						<input name="uploaded_by" type="text" ng-model="filters.uploaded_by"
						ng-change="filter(filters)" value="<?php echo $user->get('ucinetid'); ?>">
					</div>
					<div class="small-2 columns">
						<input class="button expand" type="submit" value="Reset" 
						ng-click="reset()">
					</div>
				</form>
			</div>

			<!-- results table -->
			<div class="row fullwidth">
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
									Upload Date
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
							<td class="text-center">{{ r.course_name | uppercase }}</td>
							<td class="text-center">{{ r.quarter_name | capitalize }}</td>
							<td class="text-center">{{ r.year }}</td>
							<td class="text-center">{{ r.created }}</td>
							<td class="text-center">{{ r.ucinetid }}</td>
						</tr>
						<tr hidden>
							<td>
								<input name="new_name" type="text">
							</td>
							<td>
								<select name="new_course">
									<option ng-repeat="course in courses" value="{{ course.id }}">
										{{ course.course_name | uppercase }}
									</option>
								</select>
							</td>
							<td>
								<select name="new_quarter">
									<option ng-repeat="quarter in quarters" value="{{ quarter.id }}">
										{{ quarter.quarter_name | capitalize }}
									</option>
								</select>
							</td>
							<td>
								<input name="new_year" type="number">
							</td>
							<td class="text-center">
								<?php echo Date("Y-m-d H:i:s"); ?>
							</td>
							<td class="text-center">
								<?php echo $user->get("ucinetid"); ?>
							</td>
						</tr>
					</table>
				</div>
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
