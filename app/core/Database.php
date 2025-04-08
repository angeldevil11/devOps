<?php 
class Database{
	private $server_name = HOST_DB;
	private $db_name	= NAME_DB;
	private $user_name = USER_DB;
	private $password = PASS_DB;

	private $con;
	
	public function __construct(){
		// check database connection
		$this->con = $this->db_connection($this->server_name, $this->user_name, $this->password, $this->db_name);
		if($this->con==false){
			// status connection failed.
		}else{
			// status connected successfully.
		}
	}

	private function db_connection($srvr_nm, $usr_nm, $psswrd, $db_nm){
		// create connection
		$conn = new mysqli($srvr_nm, $usr_nm, $psswrd, $db_nm);
		// check connection
		if ($conn->connect_error) {
			//die("Connection failed: " . $conn->connect_error);
			return false;
		}
		return $conn;
	}

	public function query($sql){
		$result = $this->con->query($sql);
		
		return $result;
	}

	public function db_close(){
		$this->con->close();
	}
}
?>
