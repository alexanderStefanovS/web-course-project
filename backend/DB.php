<?php

class DB {
	private $conn;
	
	public function __construct() 
	{
		$dbHost = "localhost";
		$dbName = "web_course_project_db";
		$userName = "root";
		$password = "";
		$this->conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $userName, $password,
			[
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			]);
	}
	public function getConnection() 
	{
		return $this->conn;
	}
}

?>