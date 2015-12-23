<?php

require_once('db.php');


class User extends Database
{
	function __construct($id_val, $id_key="ucinetid")
	{
		parent::__construct();

		$this->id_val = $id_val;
		$this->id_key = $id_key;

		$this->user_data = $this->query("select * from users where $id_key='$id_val'", $format="assoc");
	}


	public function get($col)
	{
		if (!isset($this))
			throw new Exception("User->get: get cannot be used as a static method");

		if (count($this->user_data) > 0 && array_key_exists($col, $this->user_data))
			return $this->user_data[$col];
		else
			return false;
	}


	public function authenticate($db=NULL, $id_val=NULL, $id_key="ucinetid")
	{
		if (isset($this))
			return count($this->user_data) > 1;

		if (is_null($db) || is_null($id_val) || is_null($id_key))
			throw new Exception("User::authenticate: this method takes three arguments when called as a static method");

		$result = $db->query("select id from users where $id_key='$id_val'");
		return count($result) == 1;
	}


	public function is_admin($db=NULL, $id_val=NULL, $id_key="ucinetid")
	{
		if (isset($this))
			return (int)$this->get("user_type") == 2;

		if (is_null($db) || is_null($id_val) || is_null($id_key))
			throw new Exception("User::is_admin: this method takes three arguments when called as a static method");

		$result = $db->query("select * from users where $id_key='$id_val'", $format="assoc");

		if (User::authenticate($db, $id_val, $id_key))
			return (int)$result["user_type"] == 2;
		else
			return false;
	}


	public function create_user()
	{
		
	}
}


?>
