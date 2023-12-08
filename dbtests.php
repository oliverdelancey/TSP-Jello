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
    echo "<pre>"; 
    print_r($array); 
    echo "</pre>";
    echo "<br/>";
}

try {
    //$conn->begin_transaction();
    // $conn->autocommit(false);
    // $committed = false;

    /* TESTS */


    // create_user
    heading("function create_user");
    $status = create_user("sam", "smith");
    print_array($status);

    heading("function authenticate_user");
    $status = authenticate_user("sam", "smith");
    print_array($status);
    $userid = $status[0][0];

    // create_project
    heading("function create_project");
    $status = create_project("test", "test", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $userid);
    print_array($status);

    // get_projects
    heading("function get_projects");
    $projects = get_projects($userid);
    print_array($projects);
    $projectid = $projects[0][3];

    // create_column.
    heading("function create_column");
    $status = create_column("test_col", $projectid);
    print_array($status);

    // get_columns
    heading("function get_columns");
    $columns = get_columns($projectid);
    print_array($columns);
    $columnid = $columns[0][0];

    // create_task
    heading("function create_task");
    $status = create_task($columnid, 0, 3, "test task");
    print_array($status);

    // get_tasks
    heading("function get_tasks");
    $tasks = get_tasks($columnid);
    print_array($tasks);
    $taskid = $tasks[0][0];



    // modify_task
    heading("function modify_task");
    $result = modify_task($taskid, 2, "why", "status", $columnid);
    print_array($result);

    // modify_project
    heading("function modify_project");
    $result = modify_project("test", 5, 5, $projectid, "why");
    print_array($result);

    // modify_column
    heading("function modify_column");
    $result = modify_column($columnid, "testing");
    print_array($result);

    // modify_task_assignment
    heading("function modify_task_assignment");
    $result = modify_task_assignment($userid, $taskid);
    print_array($result);



    // create_project_assignment
    heading("function create_project_assignment");
    $status = create_user("ray", "cob");
    $userid2 = $status[0][0];
    $result = create_project_assignment($userid2, $projectid);
    print_array($result);



    // get_collaborators
    heading("function get_collaborators");
    $collaborators = get_collaborators($projectid);
    print_array($collaborators);



    // delete_project_assignment
    heading("function delete_project_assignment");
    $result = delete_project_assignment($userid2, $projectid);
    print_array($result);

    // delete_task
    heading("function delete_task");
    $result = delete_task($taskid);
    print_array($result);

    // delete_column
    heading("function delete_column");
    $result = delete_column($columnid, $defaultid);
    print_array($result);

    // delete_project
    heading("function delete_project");
    $result = delete_project($projectid);
    print_array($result);


    // $conn->commit();
    // $committed = true;

} catch(PDOException $e) {
    print "Error!" . $e->getMessage() . "<br/>"; 
    
} finally {
    // data only needs to exist for hte lifetime of the test
    // if ($committed) {
    //     $conn->rollback();  // undo changes
    // }
    // $conn->autocommit(true);
    print "Finally exited lesgoo <br/>";
    echo "testing echo in finally block <br/>";
    //$conn->rollback();
    die(); 
}


?>
</body>
</html>
