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
                //echo "<input type = 'hidden' name = projID value = " . $userProjects[$i][3] . ">";
                //echo "<input type = 'submit' value ='Delete Project' name ='delete'>";
                echo "<input type='button' onclick='alert('Project ID: " . $userProjects[$i][3] . "')' value='Delete Project'>";
                echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
        ?>  
        </table>

    </form>
    <!--TODO:
        Button that leads to project creation page
    -->
  </body>

  <?php
  
    /*if ( isset($_POST["delete"]) ) {
        echo "<pre>" . $_POST["projID"] . "</pre>";
        echo '<p style="color:red">test</p>';
    }*/
  
  ?>
</html>
