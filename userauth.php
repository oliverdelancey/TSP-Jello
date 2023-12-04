<html>
<body>
<?php
var_dump($_POST);
include "database.php";
include "db.php";
#session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}

$valid_input = true;

$uname = $_POST["uname"];
#$_SESSION["uname"] = $_POST["uname"];

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

	echo("validated");
	if(isset($_POST["submit"])) {

		echo("submitted");
	}
	if($_POST["submit"]=='Log in') {
		echo("Logged in");
		#authenticate_user($uname, $password);
		header('Location: userHome.php');
	}
	
	if($_POST["submit"]=='Create User') {
		echo("Created");
		#create_user($uname, $password);
		header('Location: simplelogin.html');
	}

	echo("Not redirected");
	header('Location: index.html');
	exit();
}
echo("not valid");
$conn->close();

?>
</body>
</html>
