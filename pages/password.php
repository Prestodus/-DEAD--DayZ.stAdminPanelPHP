<h1>Players</h1>
<?php

if ($loggedin === false) { echo "Please log in to edit your password."; include_once("login.php"); }
else {
    
    if (isset($_POST["old"])) {
        
        $query = $dbh->prepare("SELECT * FROM admin_users WHERE id = '".$_SESSION["adminid"]."'");
        $query->execute();
        $user = $query->fetch();
        if (!$user) {
            
            echo "Something went wrong.<br /><a href='./?p=password'>Please try again</a>.";
            
        } else {
            
            if (md5($_POST["old"]) !== $user["password"]) {
                
                echo "The old password you entered is not the same as the current password.<br /><a href='./?p=password'>Please try again</a>.";
                
            } else {
                
                if (strlen($_POST["new1"]) < 3) {
                    
                    echo "Your password has to contain at least 3 characters.<br /><a href='./?p=password'>Please try again</a>.";
                    
                } else {
                    
                    if ($_POST["new1"] !== $_POST["new2"]) {
                        
                        echo "Your new passwords do not match.<br /><a href='./?p=password'>Please try again</a>.";
                    
                    } else {
                        
                        $query = $dbh->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                        $query->execute(array(md5($_POST["new1"]), $_SESSION["adminid"]));
                        if (!$query) {
                            
                            echo "Something went wrong.<br /><a href='./?p=password'>Please try again</a>.";
                            
                        } else {
                            
                            echo "Your password has been changed.";
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
    } else {
        
        ?>
             
        <form method="POST" action="./?p=password">   
        <div class="datagrid"><table>
        <thead><tr><th style="width: 200px;">New password</th><th></th></tr></thead>
        <tbody>
                    
        <?php
                    
        $alt = " class=\"alt\"";        
        echo "<tr><td style='font-weight: bold; font-size: 15px;'>Old password</td><td><input type='password' name='old' /></td></tr>";
        echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'>New password</td><td><input type='password' name='new1' /></td></tr>";
        echo "<tr><td style='font-weight: bold; font-size: 15px;'>New password (repeat)</td><td><input type='password' name='new2' /></td></tr>";
        echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;' /></td><td></td></tr>";
        echo "</tbody></table></div></form>";
        
    }

}

?>