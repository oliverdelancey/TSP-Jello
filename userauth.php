<html>
<body>
<?php
var_dump($_POST);
include "database.php";
session_start();
#session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}

$uname = $_POST["uname"];
$_SESSION["uname"]=$_POST["uname"];
#$_SESSION["uname"] = $_POST["uname"];
$valid_input = true;
if (empty($uname)) {
	echo "Username field is empty";
	$valid_input = false;
}
$password = $_POST["password"];
if (empty($password)) {
	echo "password field is empty";
	$valid_input = false;
}
if ($valid_input) {

	if(isset($_POST["submit"])) {
		if($_POST["submit"]==="Log in") {
			$query = $conn->prepare('SELECT username, password FROM users WHERE username = ?');
			$query->bind_param("s", $uname);
			$query->execute();
			$result = $query->get_result();
			$isSuccess = false;
			
			while ($row = $result->fetch_assoc()) {
				if (!empty($row)) {
					$hpassword = $row["password"];
					if ($password == $hpassword) {
						$isSuccess = true;
					}
				}
			}
			
			if ($isSuccess) {
				print "login successful";
				header("LOCATION: userHome.php");
			} else {
				print "login failed";
			}
		} else if($_POST["submit"]==="Create User") {
			try{

				$hashed_password = sha256($password, 256);
				
			        $statement = $conn->prepare('INSERT INTO users VALUES (?, RAND() * (100000 - 1) + 1, ?);');
			
			        $statement->bind_param("ss", $username, $hashed_password);
			
			        $statement->execute();
				
			        $result = $statement->get_result();
	
				header("LOCATION: simplelogin.html");
			        return $result;
		        } catch(mysqli_sql_exception $e){
		            print "Error!" . $e->getMessage() . "<br/>";  
		        }
		}
	}
}

$conn->close();

?>
</body>
</html>
