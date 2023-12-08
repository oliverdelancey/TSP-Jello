<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>

    <ul>
        <li class="h1" style="font-size:30px; font-weight:600">Jello</li>

        <li style="float:right"><a href="http://syenite.net/login.php">Sign In</a></li>
        <li class="active" style="float:right"><a href="http://syenite.net/register.php">Register</a></li>

    </ul>

    <div class="content">
        <div class="container">

            <h1>Create User</h1>
            <form method="post" action="userauth.php">

                <?php
                        if(isset($_GET['error']) && $_GET['error']=="1") {
                            echo('<p style="color:red">Username already exists!</p>');
                        } else {
                            unset($_GET['error']);
                        }
                ?>
    
                <label for="uname">Username:</label><br>
                <p><input type="text" id="uname" name="uname" placeholder="Type your username"
                        onClick="this.value='';"><br></p>
    
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" placeholder="Type your password"
                    onClick="this.value='';"><br><br>
    
                <input type="submit" id="submit" name="submit" value="Create User"><br>
            </form>
            <label for="register" id="register"><p>Already have an account? <a href="http://syenite.net/login.php">Sign In</a></p></label>
        </div>
    </div>
  </body>
</html>
