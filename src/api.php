<?php 

// include results API (DB API included with it)
require_once("results.php");
// include user API
require_once("user.php");
// include upload class
require_once("upload.php");
// include dispatcher class
require_once("dispatcher.php");

// create API objects
$db = new Database();
$r = new Results();

// declare content type (json)
header('Content-Type: application/json');

if (isset($_POST['new_upload']))
{
	$insert = array("name" => $_POST['new_name'],
					"year" => $_POST['new_year'],
					"quarter" => $_POST['new_quarter'],
					"uploaded_by" => $_GET['uid'],
					"course" => $_POST['new_course']);

	$needle = $_POST['new_file'];
	$file = $_FILES['new_results']['tmp_name'];
	$target = Config::get("staging_dir") . $_FILES['new_results']['name'];

	if (!move_uploaded_file($file, $target))
	{
		header("Location: ../index.php?error=" . error_get_last()["message"]);
		return;
	}

	$upload = new Upload($target);
	$files = $upload->extract(Config::get("staging_dir"));
	$location = $upload->find_file($files);

	if (!$location)
	{
		header("Location: ../index.php?error=Could not find the file with the results table");
		return;
	}

	$new_name = $upload->make_unique_name($insert);
	$insert["dir_name"] = $new_name;
	$new_names = $upload->move_files($files, array(Config::get("results_dir") . $new_name));
	
	if (count($new_names) <= 0)
	{
		header("Location: ../index.php?error=");
		return;
	}

	$location = $upload->find_file($new_names);
	$insert["location"] = str_replace(Config::get("doc_root"), "", $location);
	$r->create($insert);
	header("Location: ../index.php");
}


$api = new Dispatcher($db, $r);

if (isset($_GET['query']))
	$api->dispatch($_GET['query']);
else
	$api->dispatch("init");


?>
