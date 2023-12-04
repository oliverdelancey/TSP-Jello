<html>
<body>
<?php
var_dump($_POST);
include "database.php";
include "db.php";
#session_start();

echo("here1");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo("here2");

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}

echo("here3");

$valid_input = true;

echo("here4");

$uname = $_POST["uname"];
#$_SESSION["uname"] = $_POST["uname"];

echo("here5");

if (empty($uname)) {
	echo "Username field is empty";
	$valid_input = false;
}

echo("here6");

$password = $_POST["password"];

if (empty($password)) {
	echo "password field is empty";
	$valid_input = false;
}

echo("here7");

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
