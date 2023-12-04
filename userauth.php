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
			echo('<p>RUNS LOG IN</p>');
			
		} else if($_POST["submit"]==="Create User") {
			echo('<p>RUNS CREATE USER</p>');
			ob_start();
			$functionOutput = create_user($uname, $password);
			$errorMessage = ob_get_clean();
			
			if ($errorMessage) {
				echo "<p>Error creating user: ".$errorMessage."</p>";
			} else {
				header("LOCATION: simplelogin.html");
			}
		}
	}
	
	$query = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
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
		echo "login successful";
		header("LOCATION: userHome.php");
	} else {
		echo "login failed";
	}
}

$conn->close();

?>
</body>
</html>
