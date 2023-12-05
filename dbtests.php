<html>
<head>
<title>DB PHP Tests</title>
</head>
<body>

<?php

include "db.php";

//var_dump($_SESSION);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function heading($text) {
    echo "<h3>";
    echo $text;
    echo "</h3>";
}

function print_array($array) {
    echo "<pre>"; print_r($array); echo "</pre>";
    echo "<br/>";
}

try {
    $statement = $conn->prepare(
        "select id from users where username = ?"
    );

    $statement->bind_param("d", $_SESSION["uname"]);

    $statement->execute(); 
    $statement->bind_result($userid);
    $statement->fetch();
    echo "Got userid: " . $userid . "<br/>";
    $statement->close();

    /* TESTS */

    // get_projects
    $projects = get_projects($userid);
    heading("function get_projects");
    print_array($projects);

    // get_columns
    $project_id = $projects[0][3];
    $columns = get_columns($project_id);
    heading("function get_columns");
    print_array($columns);

    // get_tasks

    // get_collaborators

    // create_project

    // create_column

    // create_task
    
    // create_user

    // delete_project

    // delete_column

    // delete_task

    // modify_task

    // modify_project

    // modify_column

    // modify_task_assignment

    // create_project_assignment

    // delete_project_assignment

} catch(PDOException $e) {
    print "Error!" . $e->getMessage() . "<br/>"; 
    die(); 
}


?>
</body>
</html>
