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
               "select id, priority, description, status, name, user_id
                    from task
                    inner join (select name, task_id, user_id
                        from users inner join taskAssignments on id = user_id) as a
                        on id = task_id
                    where col_id = ?;
                "
            );

            $statement->bind_param("d", $columnid);

            $statement->execute();
            $result = $statement->get_result();

            return $result;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function getCollaborators($projectid){
        try{
            $statement = $conn->prepare(
                "select name, id 
                    from users
                    inner join projectAssignments ? = proj_id and id = user_id;
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
    
    function deleteProject($projectid){
        try{
            $statement = $conn->prepare(
                "delete from project
                    where id = ?;
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
        return null;
    }
    
    //i want this to orphan tasks to the default column
    //since it's "on delete cascade", move the tasks' column
    //ids to the default before deleting column
    function deleteColumn($columnid, $defaultid){
        return null;
    }
    
    function deleteTask($taskid){
        try{
            $statement = $conn->prepare(
                "delete from task
                    where id = ?
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
        return null;
    }

    function modifyTask($id, $priority, $description, $status, $columnid){
        try{
            $statement = $conn->prepare(
                "update task
                    set col_id = ?, priority = ?, description = ? status = ?
                    where id = ?;
                    "
            );

            $statement->bind_param("ddssd", $columnid, $priority, $description, $status, $id);

            $statement->execute();
            $result = $statement->get_result();

            return $result;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
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