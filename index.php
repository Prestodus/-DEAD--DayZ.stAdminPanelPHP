<?php

session_start();

require_once("config.php");
$userexists = false;
foreach($users as $username => $password) if (isset($_SESSION['loggedin']) AND md5($username.$password.$salt) == $_SESSION['loggedin']) $userexists = true;	
if ($userexists !== true) $loggedin = false;
else $loggedin = true;
if ($loggedin === true) $dbh = new PDO($DB_CONNSTRING, $DB_USERNAME, $DB_PASSWORD);

?>
<!doctype html>
<html>

<head>

    <meta charset="iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=8"> <!-- IMPORTANT -->
    <title>DayZ Admin Panel</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script>
    <!-- Begin
    function popUp(URL) {
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=730,height=488,left = 595,top = 356');");
    }
    // End -->
    </script>
    <script>
    function validateForm()
    {
    var x=document.forms["editform"]["blood"].value;
    var y=document.forms["editform"]["humanity"].value;
    if (x==null || x=="" || y==null || y=="")
      {
      alert("Humanity and blood must be filled out.");
      return false;
      }
    }
    </script>
    <script>
    function toggle() {
    var ele = document.getElementById("posdiv");
    var text = document.getElementById("showpos");
    if(ele.style.display == "block") {
        ele.style.display = "none";
        text.innerHTML = "<a href='#posdiv' onclick='toggle();'>Show/hide position</a>";
    }
    else {
        ele.style.display = "block";
        text.innerHTML = "<a href='#posdiv' onclick='toggle();'>Show/hide position</a>";    
    }
    } 
    </script>
    <script>
    function autoIframe(frameId) {
       try {
          frame = document.getElementById(frameId);
          innerDoc = (frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
          objToResize = (frame.style) ? frame.style : frame;
          objToResize.height = innerDoc.body.scrollHeight + 10;
       }
       catch(err) {
          window.status = err.message;
       }
    }
    </script>
    <script>
    var count=70;
    var counter=setInterval(timer, 1000);
    function timer() {
        count=count-1;
        if (count <= 0) {
            clearInterval(counter);
            document.getElementById("timer").innerHTML="Your server has been restarted."; // watch for spelling
            return;
        } else {
            document.getElementById("timer").innerHTML="Your server will restart in " + count + " seconds."; // watch for spelling
        }
    }
    </script>
    
</head>
<body onload="autoIframe('rcon');">

    <div class="centerdiv">
    
        <div class="header">
        
            <img src="images/logo.jpg" />
        
        </div>
    
        <div class="menu">
        
            <?php include_once("menu.php"); ?>
        
        </div>
    
        <div class="content">
        
            <?php
            
            $pages = array("players", "search", "login", "edit", "server");
            if (isset($_GET["p"]) AND in_array($_GET["p"], $pages)) {
                
                include_once ("pages/".$_GET["p"].".php");
                
            } else {
                
                include_once ("pages/home.php");
                
            }
            
            ?>
        
        </div>
    
        <div class="footer">
        
            &copy; 2013 Ruben Coolen (Who's shooting in Cherno?!)
        
        </div>
    
    </div>

</body>

</html>