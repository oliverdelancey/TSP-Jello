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
    /*
        echo "<p>The username should be here: $_SESSION["uname"] </p>";
        if (!isset($_SESSION["uname"])) {
                #header("LOCATION: index.html");
        } */ 
    
        if ( isset($_POST["logout"]) ) {
            #session_destroy();
            header("LOCATION: index.html");
        }
    ?>
        
    </div>
    
        
    </head>

  <body>
    
    <br>
    <?php
    require "homeDisplay.php";
    display_projects();
    ?>  
    <!--TODO:
        Button that leads to project creation page
    -->
  </body>
</html>
