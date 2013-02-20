<h1>Login</h1>

<?php

if (isset($_GET["out"]) AND $_GET["out"] == "1") {
    
    $_SESSION = array();
    echo "You are logged out.";
    echo "<meta http-equiv=\"Refresh\" content=\"0; url=./\">";
    exit;
    
}
if ($loggedin === true) echo "You are logged in.";
else {
    
    $showform = 1;
    $error = "";
    $showloggedmessage = 0;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
    	if (!ctype_alnum($_POST['username'])):
            $error .= "<br>Invalid characters in the username.";
            $showform = 1;
        endif;
        
    	if ($_POST['password'] == "" OR $_POST['username'] == ""):
            $error .= "<br>Username or password are too short.";
            $showform = 1;
        endif;
    
    	$username = $_POST['username'];
    	$password = md5($_POST['password']);
        
        if ($error == "") {
            
            $query = $dbh->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ? LIMIT 1");
            $query->execute(array($username, $password));
            if ($query->rowCount() > 0) {
                
                $user = $query->fetch();
     			$_SESSION["loggedin"] = md5($user["username"].$user["password"].$salt);
     			$_SESSION["adminid"] = $user["id"];
                $showloggedmessage = 1;
                $showform = 0;
                echo "<meta http-equiv=\"Refresh\" content=\"0; url=./\">";
                
            } else {
                
                $error .= "Invalid username or password.";
                $showform = 1;
                
            }
        
        }
    
    }
    
    if ($error !== "") echo "<h4>$error</h4>";
    if ($showform === 1) {
    
        ?>
        
        <form method="post" action="./?p=login">
        
            <p>Username: <input type="text" name="username"><br>
            Password: <input type="password" name="password"></p>
            
            <input type="submit" name="submit" value="Login">
        
        </form>
        
        <?php
    
    } else {
        
        echo "<h4>Success</h4>You have been successfully logged in.";
        
    }

}

?>