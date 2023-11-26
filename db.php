<?php
    function connectDB() { 
        $config = parse_ini_file("db.ini"); 
        $dbh = new PDO($config['dsn'], $config['username'], $config['password']); 
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        return $dbh; 
    }

    function authenticateUser($username, $password){
        return null;
    }

    function get_projects($userid){

        try{
            $dbh = connectDB();
            $statement = $dbh->prepare(
                "select name, start, end, id, description 
                    from project 
                    inner join projectAssignments on id = proj_id and :userid = user_id;
                    "
            );

            $statement->bindParam(":username", $username);

            $result = $statement->execute();

            $dbh = null;
            return $row;
        } catch(PDOException $e){
            print "Error!" . $e->getMessage() . "<br/>"; 
            die(); 
        }
    }

    function getColumns($projectid){
        return null;
    }

    function getTasks($columnid){
        return null;
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