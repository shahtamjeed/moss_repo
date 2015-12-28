<?php

require_once('db.php');


class Upload extends Database
{

	public function __construct($file=NULL)
	{
		parent::__construct();

		$this->open($file);
	}


	public function extract($save_where, $file=NULL)
	{
		$this->open($file);

		if (isset($this->zip))
		{
			$this->zip->extractTo($save_where);
			return $this->top_level_files($this->zip, $save_where);
		}
	}


	public function move_files($old, $new, $file=NULL)
	{
		$this->open($file);

		if (isset($this->zip))
		{
			foreach ($new as $k => $v)
			{
				if (!rename($old[$k], $v))
					throw new Exception("Upload->rename_files: there was a problem renaming file $v");
			}
		}
		return $new;
	}


	public function find_file($haystack, $needle="index.html")
	{
		$to_explore = (gettype($haystack)) == "array" ? $haystack : array($haystack);

		while (!empty($to_explore))
		{
			$exploring = array_shift($to_explore);

			$dir = new RecursiveDirectoryIterator($exploring);
			foreach ($dir as $f)
			{
				if ($f->isDir() && !in_array($f->getBasename(), array(".", "..")))
					array_push($to_explore, $f->getPath() . "/" . $f->getBasename());
				else if ($f->getBasename() == $needle)
					return $f->getPath() . "/" . $f->getBasename();
			}
		}
		return false;
	}


	public function make_unique_name($insert)
	{
		$c = $this->query("select id from results order by id desc limit 1", "assoc");
		$c = intval($c["id"]) + 1;
		$new_name = implode("-", $insert);
		$new_name = str_replace(" ", "", $new_name);
		$new_name .= "-" . $c;
		return $new_name;
	}


	private function top_level_files($zip=NULL, $parent_dir=NULL)
	{
		$zip = (is_null($zip)) ? $this->zip : $zip;

		if (is_null($zip))
			throw new Exception("Upload->top_level_files: this method requires a zip as input");

		$top_files = array();
		$last_path = NULL;
		for ($i = 0; $i < $zip->numFiles; ++$i)
		{
			$current_path = $zip->getNameIndex($i);
		
			if (is_null($last_path) || !$this->is_parent($last_path, $current_path))
			{
				$to_push = (is_null($parent_dir)) ? $current_path : $parent_dir . $current_path;
				array_push($top_files, $to_push);
				$last_path = $current_path;
			}
		}
		return $top_files;
	}


	private function is_parent($path1, $path2)
	{
		$n = strlen($path1);
		
		return substr($path2, 0, $n) == $path1;
	}


	private function open($file)
	{
		if (!is_null($file))
		{
			$zip = new ZipArchive;

			if ($zip->open($file))
				$this->zip = $zip;
			else
				throw new Exception("Upload->__construct: there was a problem opening file $file");
		}
	}
}


//$u = new Upload("../staging/ics31-f14-lab5.zip");
//$files = $u->extract(Config::get("staging_dir"));
//var_dump($files);

//var_dump(/*if (*/($u->find_file($files)));//)
	//$u->move_files(array("test3"));
?>
