<h1>Players</h1>
<?php

if ($loggedin === false) { echo "Please log in to access the players page."; include_once("login.php"); }
else {
    
    ?>
    
    <form method="GET" action="./">
    
        <?php $select = (isset($_GET["limit"])&&is_numeric($_GET["limit"])?$_GET["limit"]:"30"); ?>
        <p><input type="hidden" name="p" value="players">
        Show last: <select name="limit" onChange="this.form.submit()">
        
            <option<?php echo ($select=="10"?" selected":""); ?>>10</option>
            <option<?php echo ($select=="20"?" selected":""); ?>>20</option>
            <option<?php echo ($select=="30"?" selected":""); ?>>30</option>
            <option<?php echo ($select=="40"?" selected":""); ?>>40</option>
            <option<?php echo ($select=="50"?" selected":""); ?>>50</option>
            <option<?php echo ($select=="60"?" selected":""); ?>>60</option>
            <option<?php echo ($select=="70"?" selected":""); ?>>70</option>
            <option<?php echo ($select=="80"?" selected":""); ?>>80</option>
            <option<?php echo ($select=="90"?" selected":""); ?>>90</option>
            <option<?php echo ($select=="100"?" selected":""); ?>>100</option>
            <option<?php echo ($select=="150"?" selected":""); ?>>150</option>
            <option<?php echo ($select=="15000"?" selected":""); ?> value="15000">All</option>
        
        </select><noscript><input type="submit" value="Show"></noscript></p>
    
    </form>
    
    <?php
    if (isset($_GET["search"])) {
        
        $search = $_GET["search"];
        $querycount = "SELECT COUNT(*) FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0' AND profile.name LIKE '%$search%'";
        $queryselect = "SELECT survivor.id, survivor.unique_id, survivor.worldspace, profile.unique_id AS profileid, profile.name, profile.humanity, start_time, last_updated, survival_time, medical FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0' AND profile.name LIKE '%$search%' ORDER BY survivor.id DESC";
        
    } else {
        
        if (isset($_GET["limit"]) AND is_numeric($_GET["limit"])) {
            
            $limit = $_GET["limit"];
            $querycount = "SELECT COUNT(*) FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0'";
            $queryselect = "SELECT survivor.id, survivor.unique_id, survivor.worldspace, profile.unique_id AS profileid, profile.name, profile.humanity, start_time, last_updated, survival_time, medical FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0' ORDER BY survivor.id DESC LIMIT $limit";
            
        } else {
            
            $querycount = "SELECT COUNT(*) FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0'";
            $queryselect = "SELECT survivor.id, survivor.unique_id, survivor.worldspace, profile.unique_id AS profileid, profile.name, profile.humanity, start_time, last_updated, survival_time, medical FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND is_dead = '0' ORDER BY survivor.id DESC LIMIT 30";
            
        }
        
    }
    
    $count = $dbh->prepare($querycount);
    $count->execute();
    if ($count->fetch(PDO::FETCH_NUM) < 1) {
        
        echo "No matching survivors have been found.";
        
    } else {
        
        ?>
        
        <div class="datagrid"><table>
        <thead><tr><th>&nbsp;</th><th>Inventory</th><th>Name</th><th>ID</th><th>Unique ID</th><th>Survived</th><th>Last update</th></tr></thead>
        <tbody>
        
        <?php
        $alt = " class=\"alt\"";
        foreach ($dbh->query($queryselect) AS $survivor) {
             
            echo "<tr$alt><td><a href='./?p=edit&survivor=".$survivor["id"]."'>Edit survivor</a></td><td>";
            echo "<a href=\"javascript:popUp('http://dayz.st/loadout?".URLVARS."&id=".$survivor["id"]."')\">Show inventory</a>";
            echo "</td><td>".$survivor["name"]."</td><td>".$survivor["id"]."</td><td>".$survivor["profileid"]."</td><td>".$survivor["survival_time"]." min</td><td>".$survivor["last_updated"]."</td>";
            $alt = ($alt==""?" class=\"alt\"":"");
            
        }
        ?>
        
        </tbody></table></div>        
        
        <?php
        
    }
    
}

?>