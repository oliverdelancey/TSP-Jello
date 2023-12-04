<html>
<body>
<?php
include "database.php";
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
    if($_POST["submit"]=="Log in") {
        if(authenticate_user($uname, $password)) {
            echo "login successful";
		    header("LOCATION: userHome.php");
        }
    }

    if($_POST["submit"]=="Create User") {
        if(create_user($uname, $password)) {
            header("LOCATION: simplelogin.html");
        }
    }
}

$conn->close();

?>
</body>
</html>
