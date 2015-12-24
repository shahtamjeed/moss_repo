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
		return str_replace('"', "'", $this->select("results", $values, $cols, $type, $format));
	}


	public function unzip_and_save($file, $values, $save_as="")
	{
		if ($this->unzip($file, $save_as))
			$this->create_db_entry($values);
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


	public function create_db_entry($values)
	{
		if (array_key_exists("quarter", $values))
			$values["quarter"] = $this->handle_quarter($values["quarter"]);

		$cols = implode(",", array_keys($values));
		$vals = "'" . implode("','", $values) . "'";

		$q =  "insert into results ($cols) values ($vals)";
		$this->query($q);
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
var_dump($r->get(array("name" => "lab2")));


?>
