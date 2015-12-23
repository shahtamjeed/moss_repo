<?php

require_once('config.php');
require_once('db.php');


class Upload extends $db
{

	public function unzip_and_save($file, $db, $values, $save_as="")
	{
		Upload::unzip($file, $save_as);
		Upload::create_db_entry($db, $values);
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


	public function create_db_entry($db, $values)
	{
		if (array_key_exists("quarter", $values))
			$values["quarter"] = Upload::handle_quarter($db, $values["quarter"]);

		$cols = implode(",", array_keys($values));
		$vals = "'" . implode("','", $values) . "'";

		$q =  "insert into results ($cols) values ($vals)";
		$db->query($q);
	}


	private function handle_quarter($db, $quarter)
	{
		switch (gettype($quarter))
		{
			case "string":
				$results = $db->query("select id from quarters where name='$quarter'", $formate="assoc");
				return (int)$results["id"];
			case "integer":
				return $quarter;
			default:
				throw new Exception("Upload::handle_quarter: quarter ($quarter) must be an int or a string");
		}
	}
}

?>
