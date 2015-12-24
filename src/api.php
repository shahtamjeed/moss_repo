<?php 

require_once("results.php");

$r = new Results();
$results = $r->get();

header('Content-Type: application/json');

echo $results;

?>
