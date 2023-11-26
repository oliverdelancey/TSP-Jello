<?php

include "database.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
	die("connection failed: " . $conn->connect_error);
}


    function authenticateUser($username, $password){
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

    function getColumns($projectid){
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

    function getTasks($columnid){
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

    function getCollaborators($projectid){
        return null;
    }

    function createProject($name, $start, $end, $description){
        return null;
    }

    function createColumn($name, $projectid){
        return null;
    }
    
    function createTask($priority, $description, $columnid, $projectid){
        return null;
    }

    function createUser($username, $password){
        return null;
    }
    
    function deleteProject($columnid){
        return null;
    }
    
    //i want this to orphan tasks to the default column
    //since it's "on delete cascade", move the tasks' column
    //ids to the default before deleting column
    function deleteColumn($columnid, $defaultid){
        return null;
    }
    
    function deleteTask($columnid){
        return null;
    }

    function modifyTask($id, $priority, $description, $status, $columnid){
        return null;
    }

    function modifyProject($id, $name, $start, $end, $description){
        return null;
    }

    function modifyColumn($id, $name){
        return null;
    }

    function modifyTaskAssignment($userid, $taskid){
        return null;
    }
    function createProjectAssignment($userid, $projectid){
        return null;
    }
    function delete_project_assignment($userid, $projectid){
        return null;
    }


?>