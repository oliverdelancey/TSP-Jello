<html>
<body>
<?php
include "database.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}

$uname = $_POST["uname"];
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
	} else {
		echo "login failed";
	}
}

$conn->close();

?>
</body>
</html>
