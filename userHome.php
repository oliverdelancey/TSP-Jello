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

        tr:hover,
        tr:focus-within {
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

        td > a:first-child {
            display: flex;
            padding: 18px;
            text-decoration: none;
            color: inherit;
            z-index: 0;

            &:focus {
                outline: 0;
            }
        }

        a {
        color: #5165ff;
        }

        td a:hover:not(.active) {
            color: #ccc;
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

    <form method="post" action="userHome.php">
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Start</th>
                <th>End</th>
            </tr>

        <?php

            $uid = getUserID($_SESSION["uname"]);
            $userProjects = get_projects($uid);

            for($i = 0; $i < sizeof($userProjects); $i++){
                echo "<tr>";
                $proj_id = $userProjects[$i][3];
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

                echo "<td>";
                echo "<a href='project.php?id=$proj_id' class='row-link'>";
                echo "Go to Project"; 
                echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
        ?>  

        </table>
        <br>
        
        <table>
            <tr>
                <td><input type="text" id="title" name="title" placeholder="Enter Project Title"
                            onClick="this.value='';"></td>
                <td><textarea style="max-width: 250px;"
                id="description" name="description" rows="1" cols="40" 
                placeholder="Enter Project Description"></textarea></td>
                <td>
                    <label for="startDate">Start Date:</label>
                    <input type="datetime-local" id="startDate" name="startDate"></td>
                <td>
                    <label for="endDate">End Date:</label>
                    <input type="datetime-local" id="endDate" name="endDate"></td>
                <td><input type="submit" value="Create Project" name="create"> </td>
            </tr>
        </table>

    </form>
    <!--TODO:
        Button that leads to project creation page
    -->
</body>

  <?php
    /*if(isset($_GET["id"])){
        echo "<pre style='color:white'>"; print($_GET["id"]); echo "</pre>";
    }*/
    if ( isset($_POST["create"]) ) {

        $start = date("Y-m-d H:i:s", strtotime($_POST["startDate"]));
        $end = date("Y-m-d H:i:s", strtotime($_POST["endDate"]));

        /*echo "<pre style='color:white'>"; print($_POST["title"]); echo "</pre>";
        echo "<pre style='color:white'>"; print($_POST["description"]); echo "</pre>";
        echo "<pre style='color:white'>"; print($start); echo "</pre>";
        echo "<pre style='color:white'>"; print($end); echo "</pre>";*/

        create_project($_POST["title"], $_POST["description"], $start, $end, $uid);
        header("Refresh:0");
    }
  
  ?>
</html>
