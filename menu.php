<div class="menulink"><a href="./">Home</a></div>
<?php if ($loggedin) { ?><div class="menulink"><a href="./?p=players">Players</a></div>
<div class="menulink"><a href="./?p=search">Search</a></div><?php } ?>
<?php if ($loggedin === true AND rights("map")) { ?><div class="menulink"><a href="<?php echo "http://dayz.st/map?".URLVARS; ?>" target="_blank">Map</a></div><?php } ?>
<?php if ($loggedin === true AND rights("admin")) { ?><div class="menulink"><a href="./?p=admin">Admins</a></div><?php } ?>
<?php if ($loggedin) { ?><div class="menulink"><a href="./?p=password">Pass</a></div><?php } ?>
<?php if ($loggedin) { ?><div class="menulink"><a href="./?p=login&out=1">Logout</a></div><?php } ?>