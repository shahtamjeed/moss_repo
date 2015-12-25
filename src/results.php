<?php

require_once('config.php');
require_once('db.php');


class Results extends Database
{

	function __construct($file=NULL, $values=NULL, $save_as="", $format="json")
	{
		parent::__construct();
		
		if (!is_null($file) && !is_null($values) && !is_null($save_as))
			$this->unzip_and_save($file, $values, $save_as);

		else if (!is_null($file))
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


	public function unzip_and_save($file, $values, $save_as="")
	{
		if ($this->unzip($file, $save_as))
			$this->create($values);
		else
			throw new Exception("Results->unzip_and_save: there was a problems unzipping file $file");
	}


	public function unzip($file, $save_as="")
	{
		$zip = new ZipArchive;

		if ($zip->open($file))
		{
			$zip->extractTo(Config::get("results_dir") . $save_as);
			$zip->close();
			return true;
		}
		
		return false;
	}


	public function create($values)
	{
		$this->insert("results", $values);
	}


	private function handle_quarter($quarter)
	{
		switch (gettype($quarter))
		{
			case "string":
				$results = $this->query("select id from quarters where name='$quarter'", $formate="assoc");
				return (int)$results["id"];
			case "integer":
				return $quarter;
			default:
				throw new Exception("Results->handle_quarter: quarter ($quarter) must be an int or a string");
		}
	}
}


$r = new Results();
//var_dump($r->get());

//var_dump($r->get(array("year" => "2015", "quarter" => "1")));

?>
