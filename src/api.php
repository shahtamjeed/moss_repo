<?php 

// include results API (DB API included with it)
require_once("results.php");
// include upload class
require_once("upload.php");

// create API objects
$db = new Database();
$r = new Results();

// declare content type (json)
header('Content-Type: application/json');

// handle API requests
if (isset($_GET['filters']))
{
	$filters = html_entity_decode($_GET['filters']);
	$filters = json_decode($filters, $assoc=true);
	$results = $r->get($filters);
	echo $results;
}
else if (isset($_POST['new_upload']))
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

	if ($location != false)
	{
		$new_name = $upload->make_unique_name($insert);
		$insert["dir_name"] = $new_name;
		$new_names = $upload->move_files($files, array(Config::get("results_dir") . $new_name));
		
		if (count($new_names) > 0)
		{
			$location = $upload->find_file($new_names);
			$insert["location"] = str_replace(Config::get("doc_root"), "", $location);
			$r->create($insert);
			header("Location: ../index.php");
		}
	}
}
else 
{
	$results = $r->get();
	$quarters = $db->select("quarters");
	$courses = $db->select("courses");
	$content = "{\"results\": $results, \"quarters\": $quarters, \"courses\": $courses}";
	echo $content;
}

?>
