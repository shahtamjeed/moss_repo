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
		$q = $this->select_query("results", $values, $cols, $type);
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


	public function delete_entry($values=array(), $type="and", $format="json")
	{
		return $this->delete("results", $values, "", $type, $format);
	}


	public function delete_dir($dir) 
	{
		system('rm -rf ' . escapeshellarg($dir), $retval);
		if ($retval != 0) 
		{
			$msg =  "Results->delete_dir: there was a problem deleting $dir; ";
			$msg .= error_get_last()["message"];
			throw new Exception($msg);
		}
		return true;
	}
}


//$r = new Results();
//$r->delete_entry(array("id" => "27"));


?>
