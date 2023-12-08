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
    $conn->autocommit(false);
    $committed = false;

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
    $tasks = get_tasks(0);
    heading("function get_tasks");
    print_array($tasks);

    // get_collaborators
    $collaborators = get_collaborators(0);
    heading("function get_collaborators");
    print_array($collaborators);

    // create_project
    $status = create_project("test", "test", 0, 0, 0);
    heading("function create_project");
    print_array($status);

    // create_column
    $status = create_column("test_col", 0);
    heading("function create_column");
    print_array($status);

    // create_task
    $status = create_task(0, 0, 3, "test task");
    heading("function create_task");
    print_array($status);
    
    // create_user
    $status = create_user("sam", "smith");
    heading("function create_user");
    print_array($status);

    // modify_task
    $result = modify_task(0, 2, "why", "status", 0);
    heading("function modify_task");
    print_array($result);

    // modify_project
    $result = modify_project("test", 5, 5, 0, "why");
    heading("function modify_project");
    print_array($result);

    // modify_column
    $result = modify_column(0, "testing");
    heading("function modify_column");
    print_array($result);

    // modify_task_assignment
    $result = modify_task_assignment(0, 0);
    heading("function modify_task_assignment");
    print_array($result);

    // create_project_assignment
    $result = create_project_assignment(0, 0);
    heading("function create_project_assignment");
    print_array($result);

    // delete_project_assignment
    $result = delete_project_assignment(0, 0);
    heading("function delete_project_assignment");
    print_array($result);

    // delete_project
    $result = delete_project(0);
    heading("function delete_project");
    print_array($result);

    // delete_column
    $result = delete_column(0, 0);
    heading("function delete_column");
    print_array($result);

    // delete_task
    $result = delete_task(0);
    heading("function delete_task");
    print_array($result);


    $conn->commit();
    $committed = true;

} catch(PDOException $e) {
    print "Error!" . $e->getMessage() . "<br/>"; 
    die(); 
} finally {
    if ($committed) {
        $conn->rollback();  // undo changes
    }
    $conn->autocommit(true);
}


?>
</body>
</html>
