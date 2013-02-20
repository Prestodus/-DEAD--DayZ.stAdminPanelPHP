<?php
    
/*$test = $dbh->prepare("SHOW COLUMNS FROM admins WHERE Field = 'id'");
$test->execute();
$test2 = $test->fetch();
if ($test2) {
        
    echo "success";
        
} else {
        
    echo "failure";
        
}*/
    
$check1 = $dbh->prepare("SELECT 1 FROM `admin_users`"); $check2 = $dbh->prepare("SELECT 1 FROM `admin_levels`");
$check1->execute(); $check2->execute();
$check1_go = $check1->fetch(); $check2_go = $check2->fetch();
$create1 = true; $insert1 = true; $create2 = true; $insert2 = true;
$fail = false;
if (!$check1_go) {
        
    $create1 = $dbh->query("
        CREATE TABLE IF NOT EXISTS `admin_users` (
          `id` int(4) NOT NULL AUTO_INCREMENT,
          `username` varchar(20) NOT NULL,
          `password` char(32) NOT NULL,
          `rights` smallint(2) NOT NULL DEFAULT '1',
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=2 ;
    ");
    $insert1 = $dbh->query("
        INSERT INTO `admin_users` (`id`, `username`, `password`, `rights`) VALUES
        (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);
    ");
        
}
if (!$check2_go) {
        
    $create2 = $dbh->query("
        CREATE TABLE IF NOT EXISTS `admin_levels` (
          `id` int(5) NOT NULL AUTO_INCREMENT,
          `name` varchar(20) NOT NULL,
          `rights` varchar(255) NOT NULL DEFAULT 'map,admin,users,',
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=2 ;
    ");
    $insert2 = $dbh->query("
        INSERT INTO `admin_levels` (`id`, `name`, `rights`) VALUES
        (1, 'Administrator', 'map,admin,users,');
    ");
        
}
if (!$create1 OR !$insert1) $fail = true;
if (!$create2 OR !$insert2) $fail = true;

if (isset($_SESSION['loggedin'])) {
        
    $query = $dbh->prepare("SELECT * FROM admin_users WHERE id = '".$_SESSION['adminid']."'");
    $query->execute();
    $user = $query->fetch();
    if (md5($user["username"].$user["password"].$salt) == $_SESSION['loggedin']) {
            
        $loggedin = true;
        function rights($right) {
            
            global $dbh;
            global $user;
            $query = $dbh->prepare("SELECT * FROM admin_levels WHERE id = '".$user["rights"]."'");
            $query->execute();
            $userrights = $query->fetch();
            $rightsarray = explode(",", $userrights["rights"]);
            if (in_array($right, $rightsarray)) {
                
                return(true);
                
            } else {
                
                return(false);
                
            }
            
        }
            
    } else {
            
        $loggedin = false;
            
    }
        
} else {
        
    $loggedin = false;
        
}

?>

