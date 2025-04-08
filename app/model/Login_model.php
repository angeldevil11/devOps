<?php  
class Login_model{
	private $db;

	public function __construct(){
		// create object from database class
		$this->db = new Database;

		// check status
		if($this->db == false){
			echo "<script>console.log('Connection failed.' );</script>";
		}else{
			echo "<script>console.log('Connected successfully.' );</script>";
		}
	}

	public function check_login($data){
        $email = $data['email'];
		$password = $data['password'];
		$role = $_POST['select-role'];
		// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
		$result = $this->db->query("select * from tbl_operator where role = '$role' AND email = '$email' AND BINARY password = '$password';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// convert to associative array
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		} else {
			return []; // kosong return false
		}
	}
	
    

    
}
?>