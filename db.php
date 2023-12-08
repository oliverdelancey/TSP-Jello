<?php

include "database.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}


    function authenticate_user($username, $password){
        global $conn;
        try{
            $statement = $conn->prepare(
                "select id
                    from users
                    where name = ? and password = sha2(?, 256);
                    "
            );

            $statement->bind_param("ss", $username, $password);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>";  
        }
    }
    


    function get_projects($userid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "select name, start, end, id, description 
                    from project 
                    inner join projectAssignments on id = proj_id and ? = user_id;
                    "
            );

            $statement->bind_param("i", $userid);

            $statement->execute(); 
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_columns($projectid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "select id, name 
                    from col 
                    where proj_id = ?;
                    "
            );

            $statement->bind_param("i", $projectid);

            $statement->execute();
            $result = $statement->get_result();


            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_tasks($columnid){
        global $conn;
        try{
            $statement = $conn->prepare(
               "select id, priority, description, status, username, user_id
                    from task
                    inner join (select username, task_id, user_id
                        from users inner join taskAssignments on id = user_id) as a
                        on id = task_id
                    where col_id = ?;
                "
            );

            $statement->bind_param("i", $columnid);

            $statement->execute();
            $result = $statement->get_result();

            
            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_collaborators($projectid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "select username, id 
                    from users
                    inner join projectAssignments on ? = proj_id and id = user_id;
                    "
            );

            $statement->bind_param("i", $projectid);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function create_project($name, $description, $start, $end,  $userid){
        global $conn;
        
        try {
            $conn->begin_transaction();
            
                // creates random id number for the project
                $projectid = rand(1,100000);
                
                //create a project for the user
                if($end <= 0){
                    throw new mysqli_sql_exception("End date needs to be greater than the current date and time");
                }

                $statement1 = $conn->prepare(
                    "insert into 
                        project (name, start, end, id, description) 
                        values (?, ?, ?, ?, ?);
                    "
                );
                
                $statement1->bind_param("sssis", $name, $start, $end, $projectid, $description);
                $statement1->execute();
                
                //assign user to the project
                create_project_assignment($userid, $projectid);
                create_column("default", $projectid);

            $conn->commit();
            return $statement1->get_result()->fetch_all();

        } catch (mysqli_sql_exception $e) {
            print "Error!" . $e->getMessage() . "<br/>"; 
            $conn->rollback();
            return $e->getCode();
        }
    }

    function create_column($name, $projectid){
        global $conn;
        try {
            $statement = $conn->prepare(
                "insert into col 
                    (id, proj_id, name)
                    values (RAND() * (100000 - 1) + 1, ?, ?);"
            );

            $statement->bind_param("is", $projectid, $name);
            $statement->execute();
            
            return $statement->get_result()->fetch_all();
            
        } catch (mysqli_sql_exception $e) {
            print "Error! " . $e->getMessage() . "<br/r>";
            die();
        }
    }

    function create_task($columnid, $projectid, $priority = 3, $description){
        global $conn;

        try {
            $statement = $conn->prepare(
                "insert into task 
                    (id, col_id, proj_id, priority, description)
                    values (RAND() * (100000 - 1) + 1, ?, ?, ?, ?);
                "
            );

            $statement->bind_param("iiis", $columnid, $projectid, $priority, $description);
            $statement->execute();

            return $statement->get_result()->fetch_all();

        } catch (mysqli_sql_exception $e) {
            print "Error! " . $e->getMessage() . "<br/r>";
            die();
        }
    }

    function create_user($username, $password){
        global $conn;
        try{
            $statement = $conn->prepare(
                "insert into users
                    values (?, RAND() * (100000 - 1) + 1, sha2(?, 256));
                "
            );

            $statement->bind_param("ss", $username, $password);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>";  
        }
    }
    
    function delete_project($projectid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "delete from project
                    where id = ?;
                "
            );

            $statement->bind_param("i", $projectid);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }
    
    //i want this to orphan tasks to the default column
    //since it's "on delete cascade", move the tasks' column
    //ids to the default before deleting column
    function delete_column($columnid, $defaultid){
        global $conn;
        try{
            $conn->begin_transaction();
                $statement = $conn->prepare(
                    "update task
                        set col_id = ?
                        where col_id = ?;"
                );
                $statement2 = $conn->prepare(
                    "delete from column
                        where id = ?;"
                );

                $statement->bind_param("ii", $defaultid, $columnid);
                $statement2->bind_param("i", $columnid);

                $statement->execute();
                $statement2->execute();

                $result = $statement2->get_result();
            $conn->commit();
            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            $conn->rollback();
            die();
        }
    }
    
    function delete_task($taskid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "delete from task
                    where id = ?
                    "
            );

            $statement->bind_param("i", $projectid);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function modify_task($id, $priority, $description, $status, $columnid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "update task
                    set col_id = ?, priority = ?, description = ? status = ?
                    where id = ?;
                    "
            );

            $statement->bind_param("iissi", $columnid, $priority, $description, $status, $id);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function modify_project($name, $start, $end, $id, $description){
        global $conn;

        try {
            $statement = $conn->prepare(
                "update project
                    set name=?, start=?, end=?, decription=?
                    where id=?;
                "
            );

            $statement->bind_param("siis", $name, $start, $end, $description);
            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();

        } catch (mysqli_sql_exception $e) {
            print "Error!" . $e->getMessage() . "<br/>";
            die();
        }
    }

    function modify_column($id, $name){
        global $conn;

        try {
            $statement = $conn->prepare(
                "update col
                    set name=?
                    where id=?;
                "
            );

            $statement->bind_param("si", $name, $id);
            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();

        } catch (mysqli_sql_exception $e) {
            print "Error!" . $e->getMessage() . "<br/>";
            die();
        }

    }

    function modify_task_assignment($userid, $taskid){
        global $conn;
        try{
            $conn->begin_transaction();
                $statement1 = $conn->prepare(
                    "select user_id from taskAssignments
                        where task_id = ?;                    
                    "
                );
                $statement1->bind_param("i", $taskid);
                $statement1->execute();
                $result1 = $statement1->get_result();
                $result1Row = $result1->fetch_row();

                if($result1Row == null){ //entry doesn't exist 
                    $statement2 = $conn->prepare(
                        "insert into taskAssignment
                            values (?, ?);
                        "
                    );
                    $statement2->bind_param("ii", $userid, $taskid);
                    $statement2->execute();
                    $result2 = $statement2->get_result();
                } else if($result1Row[0] == $userid){ //entry has userid as the assignment
                    $statement2 = $conn->prepare(
                        "update taskAssignment
                            set user_id = NULL
                            where task_id = ?;
                            "
                    );
                    $statement2->bind_param("i", $taskid);
                    $statement2->execute();
                    $result2 = $statement2->get_result();    
                } else{ //entry has some other user or null as assignment 
                    $statement2 = $conn->prepare(
                        "update taskAssignment
                            set user_id = ?
                            where task_id = ?;
                            "
                    );
                    $statement2->bind_param("ii", $userid, $taskid);
                    $statement2->execute();
                    $result2 = $statement2->get_result();
                }

            $conn->commit();

            return ($result2->fetch_row())[0];
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            $conn->rollback();
            return $e->getCode();
        }
    }

    function create_project_assignment($userid, $projectid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "insert into projectAssignments
                    values (?, ?);
                    "
            );

            $statement->bind_param("ii", $userid, $projectid);

            $statement->execute(); 
            //$result = $statement->get_result();

            return $statement->get_result()->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
        }
    }
    
    function delete_project_assignment($userid, $projectid){
        global $conn;
        try{
            $statement = $conn->prepare(
                "remove from task
                    where user_id = ? and proj_id = ?;
                    "
            );

            $statement->bind_param("ii", $userid, $projectid);

            $statement->execute();
            $result = $statement->get_result();

            return $result->fetch_all();
        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }


?>