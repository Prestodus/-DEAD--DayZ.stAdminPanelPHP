<h1>Restart server</h1>
<?php

if ($loggedin === false OR !rights("server")) { echo "Please log in to access the server page."; include_once("login.php"); }
else {
    
    $action = $_GET["action"];
    if ($action == "restart") {
        
        echo "<p><span id=\"timer\">The restart request has been sent.</span></p>";
        echo "<iframe src=\"http://www.dayz.st/control?u=".$ST_USERNAME."&p=".$ST_PASSWORD."&restart=1\" style=\"display: none;\"></iframe>";
        
    } else if ($action == "start") {
        
        echo "<p>Your server has been started.</p>";
        echo "<iframe src=\"http://www.dayz.st/control?u=".$ST_USERNAME."&p=".$ST_PASSWORD."&start=1\" style=\"display: none;\"></iframe>";
        
    } else if ($action == "stop") {
        
        echo "<p>Your server has been shut down.</p>";
        echo "<iframe src=\"http://www.dayz.st/control?u=".$ST_USERNAME."&p=".$ST_PASSWORD."&stop=1\" style=\"display: none;\"></iframe>";
        
    }
    
}

?>