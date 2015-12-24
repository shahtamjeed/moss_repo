<?php

	$z = new ZipArchive;
	$z->open("test.zip");

	$last_path = NULL;
	for ($i = 0; $i < $z->numFiles; ++$i)
	{
		$current_path = $z->getNameIndex($i);
	
		if (is_null($last_path) || !is_parent($last_path, $current_path))
		{
			echo $current_path . "\r\n";
			$last_path = $current_path;
		}
	}


	function is_parent($path1, $path2)
	{
		$n = strlen($path1);
		
		return substr($path2, 0, $n) == $path1;
	}
?>
