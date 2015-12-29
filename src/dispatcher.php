<?php 

require_once("user.php");


class Dispatcher 
{

	public function __construct($db, $r)
	{
		$this->db = $db;
		$this->r = $r;
	}


	public function dispatch($query_type)
	{
		switch ($query_type)
		{
			case "filter_results":
				$this->filter_results();
				break;
			case "delete_result":
				$this->delete_result();
				break;
			case "delete_user":
				$this->delete_user();
				break;
			case "load_admin":
				$this->load_admin();
				break;
			case "new_user":
				$this->new_user();
				break;
			case "init":
				$this->init();
				break;
		}
	}


	private function init()
	{
		$results = $this->r->get();
		$quarters = $this->db->select("quarters");
		$courses = $this->db->select("courses");
		$content = "{\"results\": $results, \"quarters\": $quarters, \"courses\": $courses}";
		echo $content;
	}


	private function filter_results()
	{
		$filters = json_decode(file_get_contents('php://input'), $assoc=true);
		$results = $this->r->get($filters);
		echo $results;
	}


	private function delete_result()
	{
		$uid = $_GET['admin'];
		$u = new User($uid, "id");

		if (!$u->is_admin())
		{
			return;
		}

		$location = $_GET['delete'];
		$dir = $this->r->get(array("location" => $location), "*", "and", "assoc")["dir_name"];

		if ($this->r->delete_dir(Config::get("results_dir") . $dir))
			$this->r->delete_entry(array("location" => $location));
	}


	private function delete_user()
	{
		$uid = $_GET['admin'];
		$u = new User($uid, "id");

		if (!$u->is_admin())
		{
			return;
		}

		$user = $_GET['delete'];
		$u->delete_user(array("ucinetid" => $user));
	}


	private function load_admin()
	{
		$uid = $_GET['admin'];
		$u = new User($uid, "id");

		if (!$u->is_admin())
		{
			return;
		}

		$users = new User();
		$user_list = $users->get_users();
		$user_types = $this->db->select("user_types");
		$content = "{\"users\": $user_list, \"user_types\": $user_types}";
		echo $content;
	}


	private function new_user()
	{
		$uid = $_GET['admin'];
		$u = new User($uid, "id");

		if (!$u->is_admin())
		{
			return;
		}
		$new_user = html_entity_decode($_GET['new_user']);
		$new_user = json_decode($new_user, $assoc=true);
		$new_u = new User();
		$new_u->create_user($new_user);
	}
}


?>
