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
		<!-- init angular app -->
		<div ng-app="app" ng-controller="mainController">

			<!-- begin nav bar -->
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area">
					<li class="name">
						<h1><a href="/moss">Moss Archive</a></h1>
					</li>
				</ul>

				<section class="top-bar-section">
					<ul class="left">
						<?php if ($user->is_admin()) { ?>
							<li class="active"><a href="#" ng-click="loadAdmin(<?php echo $user->get("id"); ?>, true)">Admin</a></li>
						<?php } ?>
						<li><a href="#" data-reveal-id="upload_modal">Upload</a></li>
					</ul>
					<ul class="right">
					</ul>
				</section>
			</nav>
			<!-- end nav bar -->

			<br>

			<!-- filter form -->
			<div id="filter_form" class="row fullwidth">
				<form>
					<div class="small-2 columns">
						<h2>Filters:</h2>
					</div>
					<div class="small-2 columns">
						<span class="label">Name</span>
						<input name="name" type="text" ng-model="filters.name" ng-change="filter(filters)">
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
						<input class="button expand" type="submit" value="Reset" 
						ng-click="reset()">
					</div>
				</form>
			</div>
			<!-- end filter form -->

			<!-- results table (UI for standard users) -->
			<div id="standard_ui" class="row fullwidth">
				<div class="small-12 panel">
					<table id="results_table">
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
							<?php if ($user->is_admin()) { ?>
								<th class="text-center">
									Delete
								</th>
							<?php } ?>
						</tr>

						<tr ng-repeat="r in results|orderBy:orderByField:reverseSort">
							<td class="text-center"><a href="{{ r.location }}">{{ r.name }}</a></td>
							<td class="text-center">{{ r.course_name | uppercase }}</td>
							<td class="text-center">{{ r.quarter_name | capitalize }}</td>
							<td class="text-center">{{ r.year }}</td>
							<td class="text-center">{{ r.created }}</td>
							<td class="text-center">{{ r.ucinetid }}</td>
							<?php if ($user->is_admin()) { ?>
								<td class="text-center">
									<span class="label alert" ng-click="delete(r.location, <?php echo $user->get("id"); ?>, false)">
										<a href="#">Delete</a>
									</span>
								</td>
							<?php } ?>
						</tr>
					</table>
				</div>
			</div>
			<!-- end results table -->

			<?php if ($user->is_admin()) { ?>
				<!-- users table (UI for admin users) -->
				<div id="admin_ui" class="row fullwidth" hidden>
					<div class="small-12 panel">
						<table id="users_table">
							<tr>
								<th class="text-center">
									<a href="#" ng-click="orderByField='firstname'; reverseSort = !reverseSort">
										Firstname
									</a>
								</th>
								<th class="text-center">
									<a href="#" ng-click="orderByField='lastname'; reverseSort = !reverseSort">
										Lastname
									</a>
								</th>
								<th class="text-center">
									<a href="#" ng-click="orderByField='email'; reverseSort = !reverseSort">
										 Email
									</a>
								</th>
								<th class="text-center">
									<a href="#" ng-click="orderByField='ucinetid'; reverseSort = !reverseSort">
										UCInetID
									</a>
								</th>
								<th class="text-center">
									<a href="#" ng-click="orderByField='user_type'; reverseSort = !reverseSort">
										User Type
									</a>
								</th>
								<th class="text-center">
									Delete
								</th>
							</tr>

							<tr ng-repeat="u in users|orderBy:orderByField:reverseSort">
								<td class="text-center">{{ u.firstname | capitalize }}</td>
								<td class="text-center">{{ u.lastname | capitalize }}</td>
								<td class="text-center">{{ u.email }}</td>
								<td class="text-center">{{ u.ucinetid }}</td>
								<td class="text-center">{{ u.type_name | capitalize }}</td>
								<td class="text-center">
									<span class="label alert" ng-click="delete(u.ucinetid, <?php echo $user->get("id"); ?>, true)">
										<a href="#">Delete</a>
									</span>
								</td>
							</tr>
							<tr id="add_user_row" ng-show="showUserAddRow">
								<td class="text-center">
									<input type="text" ng-model="new_user.firstname">
								</td>
								<td class="text-center">
									<input type="text" ng-model="new_user.lastname">
								</td>
								<td class="text-center">
									<input type="text" ng-model="new_user.email">
								</td>
								<td class="text-center">
									<input type="text" ng-model="new_user.ucinetid">
								</td>
								<td class="text-center">
									<select ng-model="new_user.user_type">
										<option ng-repeat="ut in user_types" value="{{ ut.id }}">
											{{ ut.type_name | capitalize }}
										</option>
									</select>
								</td>
								<td class="text-center">
									<span class="label" ng-click="saveUser(new_user, <?php echo $user->get("id"); ?>)">
										<a href="#">Save</a>
									</span>
								</td>
							</tr>
						</table>
						<div class="row">
							<div class="small-1 small-centered columns">
								<a href="#" ng-click="showUserAddRow = !showUserAddRow">
									<img class="add_icon" src="static/img/plus-icon.png">
								</a>
							</div>
						</div>
					</div>
				</div>
				<!-- end users table -->
			<?php } ?>

			<!-- upload modal -->
			<div id="upload_modal" class="reveal-modal medium" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
				<h2 class="text-center">Upload New Results</h2>
				<form method="POST" action="src/api?query=upload_result&uid=<?php echo $user->get("id"); ?>" enctype="multipart/form-data">
					<div class="row">
						<div class="small-6 columns">
							<div class="row">
								<span class="label">Name</span>
								<input name="new_name" type="text" required>
								<span class="label">Year</span>
								<input name="new_year" type="number" value="<?php echo Date('Y'); ?>" required>
								<span class="label">Name of Results File</span>
								<input name="new_file" type="text" value="index.html" required>
							</div>
						</div>
						<div class="small-6 columns">
							<span class="label">Quarter</span>
							<select name="new_quarter" required>
								<option ng-repeat="quarter in quarters" value="{{ quarter.id }}">
									{{ quarter.quarter_name | capitalize }}
								</option>
							</select>
							<span class="label">Course</span>
							<select name="new_course" required>
								<option ng-repeat="course in courses" value="{{ course.id }}">
									{{ course.course_name | uppercase }}
								</option>
							</select>
							<span class="label">Results Zip</span>
							<input name="new_results" type="file" required>
						</div>
						<br>
						</div>
					<div class="row">
						<div class="small-6 small-centered columns">
							<input class="button expand" name="new_upload" type="submit" value="Upload">
						</div>
					<div>
				</form>
				<a class="close-reveal-modal" aria-label="Close">&#215;</a>
				<div class="row">
					<div class="small-12 columns">
						<span class="label warning">Note</span>
						<h6>
							Zip files should contain ONE folder, which should contain
							files that contain the results of the plagiarism detection test you wish 
							to store. Among those files should be a 'main' file that links to the results
							of the plagiarism test.  For MOSS results, this file will be called 'index.html',
							and it will be stored in the 'results' subdirectory of the MOSS download.
						</h6>
					</div>
				</div>
			</div>
			<!-- end upload modal -->

		</div>
		<!-- end angular app -->

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
