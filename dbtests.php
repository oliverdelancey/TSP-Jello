<html>
<head>
<title>DB PHP Tests</title>
</head>
<body>
<?php

include "db.php";

try{
    $statement = $conn->prepare(
        "select id from users where username = ?"
    );

    $statement->bind_param("d", $_SESSION["uname"]);

    $statement->execute(); 
    $result = $statement->get_result();
    $row = $statement->fetch();
    $userid = $row[0];

    // tests

    $projects = get_projects($userid);
    echo "<p>function get_projects: " . $projects . "</p>";

} catch(PDOException $e){
    print "Error!" . $e->getMessage() . "<br/>"; 
    die(); 
}


?>
</body>
</html>
