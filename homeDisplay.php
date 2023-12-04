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

get_userID(){
    try{
        $statement = $conn->prepare(
            "select id from users where username = ?"
        );

        $statement->bind_param("d", "1111");

        $statement->execute(); 
        $result = $statement->get_result();
        $row = $statement->fetch()

        return $row[0];
    } catch(PDOException $e){
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    }
}

function display_projects(){
    try{
        $statement = $conn->prepare(
            "select name, description, start, end
                from project 
                inner join projectAssignments on id = proj_id and ? = user_id;
                "
        );

        $statement->bind_param("d", "1111");

        $statement->execute(); 
        $result = $statement->get_result();

        echo "<table>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Start</th>";
            echo "<th>End</th>";
        echo "</tr>";
        while ($row = $statement->fetch()){
            echo "<tr>";
            $data = $row[0] . "\t";
            echo "<td>";
            print $data; 
            echo "</td>";
            $data = $row[1] . "\t";
            echo "<td>";
            print $data; 
            echo "</td>";
            $data = $row[2] . "\n";
            echo "<td>";
            print $data;
            $data = $row[3] . "\n";
            echo "<td>";
            print $data; 
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "test";
    } catch(PDOException $e){
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    }
}