<?php 
class Database{

	static function konek(){ 
		$host="localhost";
		$username="root";
		$pass="";
		$db="rentalmobil";

		$conn = mysqli_connect($host, $username, $pass, $db);
		if($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}
}


 ?>