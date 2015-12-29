<?php

require_once('db.php');


class User extends Database
{

	function __construct($id_val=NULL, $id_key="ucinetid")
	{
		parent::__construct();

		$this->user_data = NULL;

		if (!is_null($id_val))
		{
			$this->user_data = $this->select("users", array($id_key => $id_val), "*", "and", $format="assoc");
			$this->user_data = (is_null($this->user_data)) ? array() : $this->user_data;
		}
	}


	public function get($col)
	{
		if (!isset($this))
			throw new Exception("User->get: get cannot be used as a static method");

		else if (is_null($this->user_data))
			throw new Exception("User->get: get requires user data");

		if (count($this->user_data) > 0 && array_key_exists($col, $this->user_data))
			return $this->user_data[$col];
		else
			return false;
	}


	public function authenticate($db=NULL, $id_val=NULL, $id_key="ucinetid")
	{
		if (isset($this))
		{
			if (is_null($this->user_data))
				throw new Exception("User->authenticate: authenticate requires user data");

			return count($this->user_data) > 1;
		}

		if (is_null($db) || is_null($id_val) || is_null($id_key))
			throw new Exception("User::authenticate: this method takes three arguments when called as a static method");

		$result = $db->query("select id from users where $id_key='$id_val'");
		return count($result) == 1;
	}


	public function is_admin($db=NULL, $id_val=NULL, $id_key="ucinetid")
	{
		if (isset($this))
		{
			if (is_null($this->user_data))
				throw new Exception("User->is_admin: is_admin requires user data");

			return (int)$this->get("user_type") == 2;
		}

		if (is_null($db) || is_null($id_val) || is_null($id_key))
			throw new Exception("User::is_admin: this method takes three arguments when called as a static method");

		$result = $db->query("select * from users where $id_key='$id_val'", $format="assoc");

		if (User::authenticate($db, $id_val, $id_key))
			return (int)$result["user_type"] == 2;
		else
			return false;
	}


	public function create_user($values, $id_key="ucinetid", $init=true)
	{
		if (!is_null($this->user_data) && count($this->user_data) > 0)
			throw new Exception("User->create_user: cannot create user that already exists");

		if (array_key_exists($id_key, $values))
			$id_val = $values[$id_key];
		else
			throw new Exception("User->create_user: id_key ($id_key) must be a key in values");

		$this->insert("users", $values);

		if ($init)
			$this->user_data = $this->select("users", array($id_key => $id_val), "*", "and", "assoc");
	}


	public function delete_user($values=array(), $type="and", $format="json")
	{
		return $this->delete("users", $values, "", $type, $format);
	}


	public function get_users($values=array(), $cols="*", $type="AND", $format="json")
	{
		if (!is_null($this->user_data) && count($this->user_data) > 0)
			throw new Exception("User->get_users: cannot get users if user data is present");

		$q = $this->select_query("users", $values, $cols, $type);
		$join =  "join (user_types) on users.user_type=user_types.id";

		if (count($values) > 0)
		{
			$q = explode("where", $q);
			$q = $q[0] . $join . " where" . $q[1];
		}
		else
			$q .= " $join";

		return $this->query($q, $format);
	}
}


$values = array('firstname' => 'david', 'lastname' => 'kay', 'email' => 
				'kay@ics.uci.edu', 'ucinetid' => 'kay', 'user_type' => 2);

//$u = new User();
//$u->get('lastname');
//$u->is_admin();
//$u->authenticate();

//$u = new User('sherronb');
//echo $u->get('lastname') . "<br>";
//echo $u->is_admin() . "<br>";
//echo $u->authenticate() . "<br>";

//$db = new Database();
//User::get('lastname');
//echo User::is_admin($db, 'kay');
//echo User::authenticate($db, 'kay');

//$u = new User();
//$u->get('lastname');
//$u->is_admin();
//$u->authenticate();

//$u->create_user($values);
//echo $u->get('lastname');
//echo $u->is_admin();
//echo $u->authenticate();

?>
