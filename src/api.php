<?php 

// include results API (DB API included with it)
require_once("results.php");

// create API objects
$db = new Database();
$r = new Results();

// declare content type (json)
header('Content-Type: application/json');

// handle API requests
if (isset($_GET['start']) && $_GET['start'])
{
	$results = $r->get();
	$quarters = $db->select("quarters");
	$courses = $db->select("courses");
	$content = "{\"results\": $results, \"quarters\": $quarters, \"courses\": $courses}";
	echo $content;
}
else if (isset($_GET['filters']))
{
	$filters = html_entity_decode($_GET['filters']);
	$filters = json_decode($filters, $assoc=true);
	$results = $r->get($filters);
	echo $results;
}

?>
