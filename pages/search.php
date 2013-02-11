<h1>Search</h1>
<?php

if ($loggedin === false) { echo "Please log in to access the search page."; include_once("login.php"); }
else {
    
    $profiles = $dbh->query("SELECT * FROM profile ORDER BY name");
    
    ?>
    
    <form method="GET" action="./">
        
        Search a survivor by name:<br>
        <input type="hidden" name="p" value="players">
        <input type="text" name="search" id="name" list="getplayers">
        <datalist id="getplayers">
        <select style="display: none;">
            <?php
            
            foreach ($profiles AS $profile) echo "<option value='".$profile["name"]."'>".$profile["name"]."</option>";
            
            ?>
        </select></datalist><br>
        <input type="submit" value="Search">
        
    </form>
    
    <?php
    
}

?>