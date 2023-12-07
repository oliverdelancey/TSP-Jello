<!DOCTYPE html>
<html>

<title> Home </title>

<head>
    <style>
        body {
            font-family: Arial;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            background-image: linear-gradient(90deg, #020024 0%, #090979 35%, #00d4ff 100%);
        }

        .header {
            border-radius: 10px;
            text-align: left;
            background: white;
        }

        .header h1 {
            font-size: 50px;
        }

        .top {
            font-family: Trebuchet MS;
            border-radius: 40px;
            padding: 50px;
            padding: 50px;
            background: white;
        }

        table {
            border-radius: 40px;
            padding: 10px;
            margin-left: auto;
            margin-right: auto;
            background: white;
            border-spacing: 15px;
            
        }

        tr {
            background: white;
            border-bottom: 1px solid #ddd;
        }


        th {
            padding: 5px;
            border-radius: 20px;
            font-size: x-large;
            font-family: Trebuchet MS;
            background: white;
            
        }

        td {
            padding: auto;
            font-size: medium;
            font-family: Trebuchet MS;
            border-style: hidden;
            
        }
        
    </style>

    <div style="clear: both" class = "top">
    
        <h1 style="float: left">
            Home
        </h1>
        
        <form method="post" action="userHome.php">
            <p style="float: right">
                <input type="submit" value="Log Out" name="logout">  
            </p><br>
        </form>
    
    <?php 
    include "database.php";
    include "db.php";
    /*if(!isset($_SESSION)){
        session_start();
    }*/

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $conn = new mysqli($server, $username, $password, $database);
    if ($conn->connect_error) {
    	die("connection failed: " . $conn->connect_error);
    }
    
    function getUserID($username){
        global $conn;
        try{
            $statement = $conn->prepare(
                "select id from users where username = ?"
            );
        
            $statement->bind_param("s", $_SESSION["uname"]);
        
            $statement->execute(); 
            
            $result = $statement->get_result();
            $row = $result->fetch_assoc();

            return $row["id"];

        } catch(mysqli_sql_exception $e){
            print "Error!" . $e->getMessage() . "<br/>";  
        }

    }

    /*
    function display_projects(){
        try{
            $statement = $conn->prepare("select name, description, start, end
            FROM project 
            INNER JOIN projectAssignments ON id = proj_id AND 1111 = user_id;
            ");
	        $statement->bind_param(":uid", $uid);
	        $result = $statement->execute();
    
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
    }*/

        
        if (!isset($_SESSION["uname"])) {
            header("LOCATION: login.php");
        }
    
        if ( isset($_POST["logout"]) ) {
            session_destroy();
            header("LOCATION: login.php");
        }

        
    ?>
        
    </div>
    
        
    </head>

  <body>
    
    <br>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Start</th>
            <th>End</th>
        </tr>

    <?php
        #display_projects();
        $uid = getUserID($_SESSION["uname"]);
        //echo "<pre>"; print($_SESSION["uname"]); echo "</pre>";
        //echo "<pre>"; print_r($uid[0]); echo "</pre>";
        //$userid = $uid[0];
        //echo "<pre>"; echo $uid . "</pre>";

        $userProjects = get_projects($uid);
        //echo "<pre>"; print_r($userProjects); echo "</pre>";

        for($i = 0; $i < sizeof($userProjects); $i++){
            echo "<tr>";
            
            echo "<td>";
            print $userProjects[$i][0]; 
            echo "</td>";

            echo "<td>";
            print $userProjects[$i][4]; 
            echo "</td>";
            
            echo "<td>";
            print $userProjects[$i][1];
            echo "</td>";

            echo "<td>";
            print $userProjects[$i][2]; 
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
    ?>  
    </table>
    <!--TODO:
        Button that leads to project creation page
    -->
  </body>
</html>
