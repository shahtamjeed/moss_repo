<?php

require_once('config.php');
require_once('db.php');


class Results extends Database
{

	function __construct($file=NULL, $values=NULL, $save_as="", $format="json")
	{
		parent::__construct();
		
		if (!is_null($file))
		{
			$this->data = $this->query($file, $format);
		}
	}


	public function get($values=array(), $cols="*", $type="AND", $format="json")
	{
		$q = $this->select_query("results", $values, $cols, $type, $format);
		$join =  "join (quarters,users,courses) on results.quarter=quarters.id and users.id=results.uploaded_by";
		$join .= " and results.course=courses.id";

		if (count($values) > 0)
		{
			$q = explode("where", $q);
			$q = $q[0] . $join . " where" . $q[1];
		}
		else
			$q .= " $join";

		return $this->query($q, $format);
	}


	public function create($values)
	{
		$this->insert("results", $values);
	}
}


$r = new Results();

//var_dump($r->get());
//var_dump($r->get(array("year" => "2015", "quarter" => "1")));

?>
