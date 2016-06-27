<?php
class DBController {
	private $host = "localhost";
	private $user = "root";
	private $password = "";
	private $database = "phppot_examples";
	
	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->selectDB($conn);
		}
	}
	
	function __destruct() {
		mysql_close();
	}
	
	function connectDB() {
		$conn = mysql_connect($this->host,$this->user,$this->password);
		return $conn;
	}
	
	function selectDB($conn) {
		mysql_select_db($this->database,$conn);
	}
	
	function getUserByOAuthId($oauth_user_id) {
		$query = "SELECT * FROM members WHERE oauth_user_id = '" . $oauth_user_id . "'";
		$result = mysql_query($query);
		if(!empty($result)) {
			$existing_member = mysql_fetch_assoc($result);
			return $existing_member;
		}
	}
	
	function insertOAuthUser($userData) {
		$query = "INSERT INTO members (member_name, member_email, oauth_user_id, oauth_user_page, oauth_user_photo) VALUES ('" . $userData->name . "','" . $userData->email . "','" . $userData->id . "','" . $userData->link . "','" . $userData->picture . "')";
		$result = mysql_query($query);
	}
}
?>