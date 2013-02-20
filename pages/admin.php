<h1>Administrator settings</h1>
<?php

if ($loggedin === false OR !rights("server")) { echo "You do not have the rights to access this page."; }
else {
    
    $allrights = array("map" => "Open the map", "admin" => "Edit admin rights", "survivor" => "Edit survivors", "server" => "Start/stop/restart server", "loadout" => "Edit the starting loadout", "database" => "Access to the database", "inventory" => "Edit inventories");
    if (isset($_GET["action"]) AND $_GET["action"] == "levels") {
        
        echo "<h2>User levels<span style='float: right;'><a href='./?p=admin&action=levels&action2=new'>New level</a></h2>";
        if (isset($_GET["action2"]) AND $_GET["action2"] !== "1" AND $_GET["action2"] !== "new") {
            
            $query = $dbh->prepare("SELECT * FROM admin_levels WHERE id = ?");
            $query->execute(array($_GET["action2"]));
            $level = $query->fetch();
            if (!$level) {
                
                echo "No user levels with id ".$_GET["action2"]." have been found.<br /><a href='./?p=admin&action=levels'>Go back</a>.";
                
            } else {
                
                $query = $dbh->query("UPDATE admin_users SET rights = '1' WHERE rights = '".$level["id"]."'");
                if (!$query) {
                    
                    echo "Something went wrong.<br /><a href='./?p=admin&action=levels'>Go back and try again</a>.";
                                        
                } else {
                    
                    $query = $dbh->query("DELETE FROM admin_levels WHERE id = '".$level["id"]."'");
                    if (!$query) {
                        
                        echo "Something went wrong.<br /><a href='./?p=admin&action=levels'>Go back and try again</a>.";
                        
                    } else {
                        
                        echo "The user level has been deleted.<br /><a href='./?p=admin&action=levels'>Go back</a>.";
                        
                    }
                    
                }
                
            }
            
        } elseif (isset($_GET["action2"]) AND $_GET["action2"] == "new") {
            
            if (isset($_POST["levelname"])) {
                
                if (strlen($_POST["levelname"]) > 20 OR strlen($_POST["levelname"]) < 1) {
                            
                    echo "The name must be between 1 and 20 characters long.<br /><a href='./?p=admin&action=levels&action2=new'>Go back</a>.";
                            
                } else {
                            
                    $rightsstring = "";
                    foreach ($_POST["rightsarray"] AS $key => $value) {
                                
                        $rightsstring .= $key.",";
                                
                    }
                    $query = $dbh->prepare("INSERT INTO admin_levels (name, rights) VALUES (?, ?)");
                    $query->execute(array($_POST["levelname"], $rightsstring));
                    echo "The level has been successfully created.<br /><a href='./?p=admin&action=levels'>Go back</a>.";
                            
                }
                
            } else {
                
                ?>
                
                <form method="POST" action="./?p=admin&action=levels&action2=new">
                <div class="datagrid"><table><tbody>
                <thead><tr><th colspan="2">New user level</th></tr></thead>
                        
                <?php
                        
                $alt = " class=\"alt\"";
                echo "<tbody><tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Name</td><td><input type='text' name='levelname' id='levelname' /></td></tr>";
                echo "<tr$alt><td style='width: 100px; font-weight: bold; font-size: 15px;'>Rights</td><td>";
                foreach ($allrights AS $key => $value) {
                                
                    echo "<input type='checkbox' name='rightsarray[$key]' id='right_$key'><label for='right_$key'>&nbsp;$value</label><br />";
                            
                }
                echo "</td></tr>";
                echo "<tr><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;'></td><td>&nbsp;</td></tr>";
                echo "</tbody></table></div></form>";
            
            }
            
        } else {
            
            if (isset($_GET["level"]) AND $_GET["level"] !== "1") {
                
                $query = $dbh->prepare("SELECT * FROM admin_levels WHERE id = ?");
                $query->execute(array($_GET["level"]));
                $level = $query->fetch();
                if (!$level) {
                    
                    echo "No user levels with id ".$_GET["level"]." have been found.<br /><a href='./?p=admin&action=levels'>Go back</a>.";
                    
                } else {
                    
                    if (isset($_POST["submitted"])) {
                        
                        if (strlen($_POST["levelname"]) > 20 OR strlen($_POST["levelname"]) < 1) {
                            
                            echo "The name must be between 1 and 20 characters long.<br /><a href='./?p=admin&action=levels&level=".$_GET["level"]."'>Go back</a>.";
                            
                        } else {
                            
                            $rightsstring = "";
                            foreach ($_POST["rightsarray"] AS $key => $value) {
                                
                                $rightsstring .= $key.",";
                                
                            }
                            $query = $dbh->prepare("UPDATE admin_levels SET name = ?, rights = ? WHERE id = ?");
                            $query->execute(array($_POST["levelname"], $rightsstring, $_GET["level"]));
                            echo "The level has been successfully updated.<br /><a href='./?p=admin&action=levels'>Go back</a>.";
                            
                        }
                        
                    } else {
                    
                        ?>
                        
                        <form method="POST" action="./?p=admin&action=levels&level=<?php echo $level["id"]; ?>">
                        <input type="hidden" name="submitted" value="1" />
                        <div class="datagrid"><table><tbody>
                        <thead><tr><th colspan="2">Editing "<?php echo $level["name"]; ?>"</th></tr></thead>
                        
                        <?php
                        
                        $rights = explode(",", $level["rights"]);
                        $alt = " class=\"alt\"";
                        echo "<tbody><tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Name</td><td><input type='text' name='levelname' id='levelname' value='".$level["name"]."'></td></tr>";
                        echo "<tr$alt><td style='width: 100px; font-weight: bold; font-size: 15px;'>Rights</td><td>";
                        foreach ($allrights AS $key => $value) {
                            
                            if (in_array($key, $rights)) {
                                
                                echo "<input type='checkbox' name='rightsarray[$key]' id='right_$key' checked='checked'><label for='right_$key'>&nbsp;$value</label><br />";
                                
                            } else {
                                
                                echo "<input type='checkbox' name='rightsarray[$key]' id='right_$key'><label for='right_$key'>&nbsp;$value</label><br />";
                                
                            }
                            
                        }
                        echo "</td></tr>";
                        echo "<tr><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;'></td><td>&nbsp;</td></tr>";
                        echo "</tbody></table></div></form>";
                    
                    }
                    
                }
            
            } else {
        
                $query = $dbh->prepare("SELECT * FROM admin_levels");
                $query->execute();
                $alllevels = $query->fetchAll();
                ?>
                
                <div class="datagrid"><table>
                <thead><tr><th style="width: 200px;">Level name</th><th>Rights</th><th style="width: 100px;">Edit</th><th style="width:  100px;">Delete</th></tr></thead>
                <tbody>
                
                <?php
                
                $alt = " class=\"alt\"";
                foreach ($alllevels AS $level) {
                        
                    $rights = explode(",", $level["rights"]);
                    echo "<tr$alt>";
                    echo "<td style='font-weight: bold;'>".$level["name"]."</td>";
                    echo "<td>";
                    
                    $number = 1;
                    foreach ($rights AS $right) {
                        
                        if ($right !== "") {
                            
                            echo ($number>1?"<br />&#10004; ":"&#10004; ").$allrights[$right];
                            $number++;
                        
                        }
                        
                    }
                    echo "<td>".($level["id"]==1?"":"<a href='./?p=admin&action=levels&level=".$level["id"]."'>Edit level</a>")."</td>";
                    echo "</td><td>".($level["id"]=="1"?"":"<a href='./?p=admin&action=levels&action2=".$level["id"]."' onclick=\"return confirm('Are you sure you want to delete user level ".$level["name"]."? This action cannot be rolled back. All users who currently have this level will be set to level 1 (Administrator).');\">Delete</a>")."</td>";
                    echo "</tr>";
                    $alt = ($alt==" class=\"alt\""?"":" class=\"alt\"");
                    
                }
                echo "</tbody></table></div>";
            
            }
            
        }
    
    } else if (isset($_GET["action"]) AND $_GET["action"] == "accounts") {
        
        echo "<h2>User accounts<span style='float: right;'><a href='./?p=admin&action=accounts&action2=new'>New user</a></h2>";
        if (isset($_GET["action2"]) AND $_GET["action2"] !== $_SESSION["adminid"] AND $_GET["action2"] !== "new") {
            
            $query = $dbh->prepare("SELECT * FROM admin_users WHERE id = ?");
            $query->execute(array($_GET["action2"]));
            $user = $query->fetch();
            if (!$user) {
                
                echo "No user with id ".$_GET["action2"]." has been found.<br /><a href='./?p=admin&action=accounts'>Go back</a>.";
                
            } else {
                    
                $query = $dbh->query("DELETE FROM admin_users WHERE id = '".$user["id"]."'");
                if (!$query) {
                        
                    echo "Something went wrong.<br /><a href='./?p=admin&action=accounts'>Go back and try again</a>.";
                        
                } else {
                        
                    echo "The user has been deleted.<br /><a href='./?p=admin&action=accounts'>Go back</a>.";
                        
                }
                
            }
            
        } elseif (isset($_GET["action2"]) AND $_GET["action2"] == "new") {
            
            if (isset($_POST["username"])) {
                
                if (strlen($_POST["username"]) > 20 OR strlen($_POST["username"]) < 1 OR strlen($_POST["password"]) > 20 OR strlen($_POST["password"]) < 1) {
                            
                    echo "The username and password must be between 1 and 20 characters long.<br /><a href='./?p=admin&action=accounts&action2=new'>Go back</a>.";
                            
                } else {
                
                    $password = md5($_POST["password"]);
                    $query = $dbh->prepare("INSERT INTO admin_users (username, password, rights) VALUES (?, ?, ?)");
                    $query->execute(array($_POST["username"], $password, $_POST["userlevel"]));
                    echo "The user has been successfully created.<br /><a href='./?p=admin&action=accounts'>Go back</a>.";
                            
                }
                
            } else {
                
                ?>
                
                <form method="POST" action="./?p=admin&action=accounts&action2=new">
                <div class="datagrid"><table><tbody>
                <thead><tr><th colspan="2">New user</th></tr></thead>
                        
                <?php
                
                $query = $dbh->prepare("SELECT * FROM admin_levels");
                $query->execute();
                $levels = $query->fetchAll();
                $alt = " class=\"alt\"";
                echo "<tbody><tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Username</td><td><input type='text' name='username' id='username' /></td></tr>";
                echo "<tr$alt><td style='width: 100px; font-weight: bold; font-size: 15px;'>Password</td><td><input type='password' name='password' id='password' /></td></tr>";
                echo "<tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Userlevel</td><td><select name='userlevel'>";
                foreach ($levels AS $level) {
                    
                    echo "<option value='".$level["id"]."'>".$level["name"]."</option>";
                    
                }
                echo "</select></td></tr>";
                echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;'></td><td>&nbsp;</td></tr>";
                echo "</tbody></table></div></form>";
            
            }
            
        } else {
            
            if (isset($_GET["user"])) {
                    
                $query = $dbh->prepare("SELECT * FROM admin_users WHERE id = ?");
                $query->execute(array($_GET["user"]));
                $user = $query->fetch();
                if (!$user) {
                        
                    echo "The user you are trying to edit does not exist. <br /><a href='./?p=admin&action=accounts'>Go back to the user list</a>.";
                        
                } else {
                
                    if (isset($_POST["submitted"])) {
                            
                        if (strlen($_POST["username"]) > 20 OR strlen($_POST["username"]) < 1) {
                                        
                            echo "The username must be between 1 and 20 characters long.<br /><a href='./?p=admin&action=accounts&user=".$_GET["user"]."'>Go back</a>.";
                                        
                        } else {
                            
                            $query = $dbh->prepare("UPDATE admin_users SET username = ?, rights = ? WHERE id = ?");
                            $query->execute(array($_POST["username"], $_POST["userlevel"], $_GET["user"]));
                            echo "The user has been successfully edited.<br /><a href='./?p=admin&action=accounts'>Go back</a>.";
                                        
                        }
                        
                    } else {
                        
                        ?>
                        
                        <form method="POST" action="./?p=admin&action=accounts&user=<?php echo $_GET["user"]; ?>">
                        <input type="hidden" name="submitted" value="1" />
                        <div class="datagrid"><table><tbody>
                        <thead><tr><th colspan="2">Editing "<?php echo $user["username"]; ?>"</th></tr></thead>
                                
                        <?php
                        
                        $query = $dbh->prepare("SELECT * FROM admin_levels");
                        $query->execute();
                        $levels = $query->fetchAll();
                        $alt = " class=\"alt\"";
                        echo "<tbody><tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Username</td><td><input type='text' name='username' id='username' value='".$user["username"]."' /></td></tr>";
                        echo "<tr$alt><td style='width: 100px; font-weight: bold; font-size: 15px;'>Userlevel</td><td><select name='userlevel'>";
                        foreach ($levels AS $level) {
                            
                            echo "<option value='".$level["id"]."'".($level["id"]==$user["rights"]?" selected='selected'":"").">".$level["name"]."</option>";
                            
                        }
                        echo "</select></td></tr>";
                        echo "<tr><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;'></td><td>&nbsp;</td></tr>";
                        echo "</tbody></table></div></form>";
                        
                    }
                    
                }
                
            } else {
        
                $query = $dbh->prepare("SELECT admin_users.*, admin_levels.name AS levelname FROM admin_users, admin_levels WHERE admin_users.rights = admin_levels.id");
                $query->execute();
                $allusers = $query->fetchAll();
                ?>
                
                <div class="datagrid"><table>
                <thead><tr><th style="width: 200px;">Username</th><th>Userlevel</th><th style="width: 100px;">Edit</th><th style="width:  100px;">Delete</th></tr></thead>
                <tbody>
                    
                <?php
                    
                $alt = " class=\"alt\"";
                foreach ($allusers AS $user) {
                    
                    echo "<tr$alt>";
                    echo "<td style='font-weight: bold;'>".$user["username"]."</td>";
                    echo "<td>".$user["levelname"]."</td>";
                    echo "<td><a href='./?p=admin&action=accounts&user=".$user["id"]."'>Edit</a></td>";
                    echo "<td>".($user["id"]==$_SESSION["adminid"]?"":"<a href='./?p=admin&action=accounts&action2=".$user["id"]."' onclick=\"return confirm('Are you sure you want to delete user ".$user["username"]."?');\">Delete</a>")."</td>";
                    echo "</tr>";
                    $alt = ($alt==" class=\"alt\""?"":" class=\"alt\"");
                        
                }
                echo "</tbody></table></div>";
            
            }
        
        }
        
    } else {
        
        echo "<p>Choose one of the options listed below:</p>";
        echo "<p><a href='./?p=admin&action=levels'>Edit admin levels</a>.<br />";
        echo "<a href='./?p=admin&action=accounts'>Edit admin accounts</a>.</p>";
        
    }
    
}

?>
