<h1>Home</h1>

<?php

if ($loggedin === false) { echo "Please log in to access the admin panel."; include_once("login.php"); }
else {
    
    echo "Click on one of the menu items or one of the links below to start.<br><br>";
    if (rights("server")) {
        
        echo "<div style=\"float: left; width: 200px; display: inline-block; text-align: left;\"><a href=\"./?p=server&action=restart\" style=\"font-size: 20pt;\">Start server</a></div>";
        echo "<div style=\"float: left; width: 200px; display: inline-block; text-align: left;\"><a href=\"./?p=server&action=restart\" style=\"font-size: 20pt;\">Stop server</a></div>";
        echo "<div style=\"float: left; width: 200px; display: inline-block; text-align: left;\"><a href=\"./?p=server&action=restart\" style=\"font-size: 20pt;\">Restart server</a></div><div style=\"clear: both; margin-bottom: 20px;\"></div>";
    
    }
    
	echo "<p><a href=\"javaScript: popUp('http://dayz.st/ajax?a=spawnvehicles&".URLVARS."&limit=300');\">Click here to remove all old, damaged vehicles and respawn new ones</a>.<br />";
	if (rights("loadout")) echo "<a href=\"javascript:popUp('http://dayz.st/loadout?".URLVARS."')\">Click here to edit the default starting loadout</a>.</p>";
    
    if (rights("database")) {
    
        ?>
        
        <form method="post" action="http://www.dayz.st/phpma/index.php?db=<?php echo $DB_NAME; ?>" name="login_form" target="_blank">
        
            <input type="hidden" name="pma_username" value="<?php echo $DB_USERNAME; ?>" />
            <input type="hidden" name="pma_password" value="<?php echo $DB_PASSWORD; ?>" />
            <input type="hidden" name="server" value="1" />
            <input type="submit" value="Click here to go to the server's database (phpMyAdmin)" style="border: none; background: transparent; margin: 0; padding: 0;" class="a" />
                    
        </form>
        
        <?php
    
    }
    
}

?>
