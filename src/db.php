<?php

require_once('config.php');


class Database
{

	function __construct()
	{
		$args = func_get_args();

		if (count($args) == 0)
			$args = Config::get_db_info();
		else if (count($args) < 4)
			throw new Exception("Database->__construct: constructor takes 0 or 4 arguments");

		$this->servername = $args[0];
		$this->username = $args[1];
		$this->password = $args[2];
		$this->db_name = $args[3];
		$this->conn = NULL;

		$this->connect();
		$this->select_db();
	}


	function __destruct()
	{
		if (!is_null($this->conn))
			$this->conn->close();
	}


	public function connect($servername=NULL, $username=NULL, $password=NULL)
	{
		$servername = (is_null($servername)) ? $this->servername : $servername;
		$username = (is_null($username)) ? $this->username : $username;
		$password = (is_null($password)) ? $this->password : $password;

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error)
			throw new Exception("Database->connect: error connect to DB (" . $conn->connect_error . ")");

		$this->conn = $conn;
		return $conn;
	}


	public function disconnect()
	{
		if (is_null($this->conn))
			throw new Exception("Database->disconnect: there is no active database connection");

		$this->conn->close();

		if ($this->conn->error)
		{
			$msg =  "Database->disconnect: there was a problem selecting DB ";
			$msg = $msg . $db_name . " (" . $this->conn->error . ")";

			throw new Exception($msg);
		}
		
		$this->conn = NULL;
	}


	public function select_db($db_name=Null)
	{
		if (is_null($this->conn))
			throw new Exception("Database->select_db: there is no active database connection");

		$db_name = (is_null($db_name)) ? $this->db_name : $db_name;

		$this->conn->select_db($db_name);
	
		if ($this->conn->error)
		{
			$msg =  "Database->select_db: there was a problem selecting DB ";
			$msg = $msg . $db_name . " (" . $this->conn->error . ")";

			throw new Exception($msg);
		}
	}


	public function query($q, $format="array")
	{
		if (is_null($this->conn))
			throw new Exception("Database->query: there is no active database connection");

		$result = $this->conn->query($q);

		if ($this->conn->error)
		{
			$msg =  "Database->query: there was a problem executing the query '";
			$msg .= $q . "' (" . $this->conn->error . ")";

			throw new Exception($msg);
		}

		switch ($format)
		{
			case "array":
				return (gettype($result) == "boolean") ? $result : $result->fetch_all();
			case "assoc":
				return (gettype($result) == "boolean") ? $result : $result->fetch_assoc();
			case "json":
				return (gettype($result) == "boolean") ? $result : json_encode($this->json($result));
			default:
				return $result;
		}
	}


	public function select($table, $values=array(), $cols="*", $type="and", $format="json")
	{
		if (!isset($table))
			throw new Exception("Database->select: select require a value for table");

		$cols = (gettype($cols) == "array") ? implode(",", $cols) : $cols;
		$q = "select $cols from $table";

		if (count($values) > 0)
		{
			$vals = array();
			foreach ($values as $k => $v)
				array_push($vals, "$k='$v'");

			$vals = implode(" $type ", $vals);
			$q .= " where $vals";
		}

		return $this->query($q, $format);
	}


	private function json($data)
	{
		$result = array();

		while (($row = $data->fetch_assoc()) !== NULL)
			array_push($result, $row);

		return $result;
	}


	public function set_servername($servername)
	{
		$this->servername = $servername;
		return $this->servername;
	}


	public function set_username($username)
	{
		$this->username = $username;
		return $this->username;
	}


	public function set_password($password)
	{
		$this->password = $password;
		return $this->password;
	}


	public function set_db_name($db_name)
	{
		$this->db_name = $db_name;
		return $this->db_name;
	}


	public function query_prep($values)
	{
		$cols = implode(",", array_keys($values));
		$vals = "'" . implode("','", $values) . "'";

		return array($cols, $vals);
	}
}


?>
