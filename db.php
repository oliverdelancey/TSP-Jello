<?php

include "database.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}


    function authenticate_user($username, $password){
        return null;
    }


    function get_projects($userid){
        try{
            $statement = $conn->prepare(
                "select name, start, end, id, description 
                    from project 
                    inner join projectAssignments on id = proj_id and ? = user_id;
                    "
            );

            $statement->bind_param("d", $userid);

            $statement->execute(); 
            $result = $statement->get_result();

            return $result;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_columns($projectid){
        try{

            $statement = $conn->prepare(
                "select id, name 
                    from column 
                    where proj_id = ?;
                    "
            );

            $statement->bind_param("d", $projectid);

            $statement->execute();
            $result = $statement->get_result();


            return $result;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_tasks($columnid){
        try{
            $statement = $conn->prepare(
                "select id, priority, description, status, user_id 
                    from task
                    inner join taskAssignments on id = task_id
                    where col_id = ?;
                    "
            );

            $statement->bind_param("d", $columnid);

            $result = $statement->execute();

            return $row;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function get_collaborators($projectid){
        return null;
    }

    function create_project($name, $start, $end, $description){
        return null;
    }

    function create_column($name, $projectid){
        return null;
    }
    
    function create_task($priority, $description, $columnid, $projectid){
        return null;
    }

    function create_user($username, $password){
        return null;
    }
    
    function delete_project($columnid){
        return null;
    }
    
    //i want this to orphan tasks to the default column
    //since it's "on delete cascade", move the tasks' column
    //ids to the default before deleting column
    function delete_column($columnid, $defaultid){
        return null;
    }
    
    function delete_task($columnid){
        return null;
    }

    function modify_task($id, $priority, $description, $status, $columnid){
        return null;
    }

    function modify_project($id, $name, $start, $end, $description){
        return null;
    }

    function modify_column($id, $name){
        return null;
    }

    function modify_task_assignment($userid, $taskid){
        return null;
    }
    function create_project_assignment($userid, $projectid){
        return null;
    }
    function delete_project_assignment($userid, $projectid){
        return null;
    }


?>