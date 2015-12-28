<?php

$settings = Array();


## DB Variables ################################################################


$settings["servername"] = "127.0.0.1";
$settings["username"] = "root";
$settings["password"] = "";
$settings["db_name"] = "moss";


## App Variables ###############################################################


$settings["app_root"] = "127.0.0.1/";
$settings["doc_root"] = "/Library/WebServer/Documents/moss/";
$settings["static_root"] = "static/";
$settings["results_dir"] = $settings["doc_root"] . "results/";
$settings["staging_dir"] = $settings["doc_root"] . "staging/";


################################################################################
## Config ######################################################################
################################################################################


class Config
{
/*
	Class that abstracts getting/setting configs.
*/

	static public function get($setting)
	{
	/*
		General accessor for config values.

		Parameters
		----------
		$setting : string
			Name of the setting to get.

		Returns
		-------
		mixed (or bool)
			The value of the desired setting, if the setting exsits, otherwise false.
	*/
		global $settings;

		if (array_key_exists($setting, $settings))
			return $settings[$setting];
		else
		{
			return false;
		}
	}


	static public function get_db_info()
	{
	/*
		Accessor for DB settings.

		Parameters
		----------
		None

		Returns
		-------
		array of mixed
			DB setting values (servername, username, password, db_name).
	*/
		global $settings;
		
		return array($settings['servername'], $settings['username'], $settings['password'], $settings['db_name']);
	}
}


################################################################################
################################################################################
################################################################################

?>
