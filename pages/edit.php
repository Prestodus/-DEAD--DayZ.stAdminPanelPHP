<h1>Edit survivor</h1>
<?php

if ($loggedin === false OR !rights("survivor")) { echo "You do not have the rights to edit a survivor."; }
else {
    
    if (!isset($_GET["survivor"]) OR !is_numeric($_GET["survivor"])) echo "You did not choose a survivor to edit.";
    else {
            
        $survivorid = $_GET["survivor"];
        $querysurvivor = "SELECT survivor.id, survivor.model, survivor.worldspace, survivor.unique_id, profile.unique_id AS profileid, profile.name, profile.humanity, start_time, last_updated, medical FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND survivor.id = '$survivorid' AND is_dead = '0' ORDER BY id LIMIT 1";
        $count = $dbh->query($querysurvivor)->fetchAll();
        
        if (count($count) < 1) {
                
            echo "No matching alive survivors have been found.<br>The survivor you are looking for may have died before opening this page.";
                
        } else {
            
            $query = $dbh->prepare("SELECT instance.world_id AS world_id, world.max_y AS max_y FROM instance, world WHERE instance.world_id = world.id LIMIT 1");
            $query->execute();
            $world = $query->fetch();
            $worlds = array("1" => "", "2" => "/lingor", "3" => "/", "4" => "/takistan", "5" => "/panthera", "6" => "/fallujah", "7" => "/", "8" => "namalsk", "9" => "/", "10" => "taviana");
    		$worldid = $worlds[$world["world_id"]];
            
            if (isset($_POST["humanity"])) {
                
        		if (isset($_POST["model"]) AND $_POST["model"] !== "0") $model = $_POST["model"]; else $model = NULL;
                if (isset($_POST["uncounscious"]) AND $_POST["uncounscious"] == "true") $uncounscious = "true"; else $uncounscious = "false";
                if (isset($_POST["infected"]) AND $_POST["infected"] == "true") $infected = "true"; else $infected = "false";
                if (isset($_POST["pain"]) AND $_POST["pain"] == "true") $pain = "true"; else $pain = "false";
				if ($_POST["teleport"] !== "0") {
				
					$query = $dbh->prepare("SELECT worldspace FROM survivor WHERE id = '".$_POST["teleport"]."'");
					$query->execute();
					$player = $query->fetch();
					$updateworldspace = $dbh->prepare("UPDATE survivor SET worldspace = '".$player["worldspace"]."' WHERE id = '".$_POST["id"]."'");
					$updateworldspace->execute();
				
				}
                $blood = (is_numeric($_POST["blood"])&&$_POST["blood"]<=12000&&$_POST["blood"]>0?$_POST["blood"]:$_POST["oldblood"]);
                $humanity = (is_numeric($_POST["humanity"])&&$_POST["humanity"]>=-50000&&$_POST["humanity"]<15000?$_POST["humanity"]:$_POST["oldhumanity"]);
                $explode = explode(",", $_POST["oldmedical"]);
                $newmedical = $explode[0].",".$uncounscious.",".$infected.",".$explode[3].",".$pain.",".$explode[5].",".$explode[6].",".$blood.",".$explode[8].",".$explode[9].",".$explode[10].",".$explode[11].",".$explode[12].",".$explode[13];
        		if (isset($_POST["fullheal"]) AND $_POST["fullheal"] == "true") $newmedical = "[false,false,false,false,false,false,false,12000,[],[0,0],0]";
        		$updatequery1 = "UPDATE survivor SET medical = '$newmedical'".($model===NULL?"":", model = '$model'")." WHERE id = '".$_POST["id"]."'";
                $updatequery2 = "UPDATE profile SET humanity = '$humanity' WHERE unique_id = '".$_POST["profileid"]."'";
                $update1 = $dbh->prepare($updatequery1);
                $update1->execute();
                $update2 = $dbh->prepare($updatequery2);
                $update2->execute();
                echo "The survivor has been updated.<br><a href='./?p=edit&survivor=".$_POST["id"]."'>Go back</a>";
                
            } else {
                
                $survivor = $dbh->query($querysurvivor)->fetch();
                echo "<h3>Edit</h3>";
                echo "<form method='post' name='editform' action='./?p=edit&survivor=".$survivor["id"]."' class='formwithslider'>";
                $explode = explode(",", $survivor["medical"]);
                $uncounscious = $explode[1]; $infected = $explode[2]; $pain = $explode[4]; $blood = $explode[7]; $humanity = $survivor["humanity"];
                $oldmodel = $survivor["model"];
                $modellist = array("SurvivorW2_DZ" => "Woman", "BanditW1_DZ" => "Woman (bandit)", "Survivor2_DZ" => "Survivor", "Survivor3_DZ" => "Hero", "Sniper1_DZ" => "Ghillie suit", "Camo1_DZ" => "Camo suit", "Bandit1_DZ" => "Bandit", "Soldier1_DZ" => "Soldier", "Rocket_DZ" => "Rocket (red barret)");
                $explodeworldspace = explode(",", $survivor["worldspace"]);
                $xcoords = (int)str_replace("[", "", $explodeworldspace["1"]);
                $ycoords = (int)$explodeworldspace["2"];
				if ($world["world_id"] == "1") {
				
					$xcoords = str_pad((int)round($xcoords/100), 3, "0", STR_PAD_LEFT);
					$ycoords = str_pad((int)round(($world["max_y"]-$ycoords)/100), 3, "0", STR_PAD_LEFT);
				
				}
                
                ?>
                
                <input type="hidden" name="oldmedical" value="<?php echo $survivor["medical"]; ?>">
                <input type="hidden" name="id" value="<?php echo $survivor["id"]; ?>">
                <input type="hidden" name="profileid" value="<?php echo $survivor["profileid"]; ?>">
                <input type="hidden" name="oldhumanity" value="<?php echo $humanity; ?>">
                <input type="hidden" name="oldblood" value="<?php echo $blood; ?>">
                <div class="datagrid"><table><tbody>
                <thead><tr><th colspan="2">Editing "<?php echo $survivor["name"]; ?>"</th></tr></thead>
                
                <?php
                
                $alt = " class=\"alt\"";
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
                    
                    echo "<tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Blood</td><td><input type='range' id='blood' name='blood' min='1' max='12000' value='$blood' onchange='rangevalue1.value=value'>&nbsp;&nbsp;<output id='rangevalue1'>$blood</output> - <a href='#' onclick='blood.value=$blood; rangevalue1.value=$blood'>Reset to current blood</a></td></tr>";
                    echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'>Humanity</td><td><input type='range' id='humanity' name='humanity' min='-50000' max='20000' step='100' value='".$survivor["humanity"]."' onchange='rangevalue2.value=value'>&nbsp;&nbsp;<output id='rangevalue2'>".$survivor["humanity"]."</output> - <a href='#' onclick='humanity.value=".$survivor["humanity"]."; rangevalue2.value=".$survivor["humanity"]."'>Reset to current humanity</a></td></tr>";
                
                } else {
                    
                    echo "<tr><td style='width: 100px; font-weight: bold; font-size: 15px;'>Blood</td><td><input type='text' name='blood' id='blood' value='$blood'>&nbsp;&nbsp;&nbsp;<a href='#' onclick='blood.value=$blood'>Reset to current blood</a></td></tr>";
                    echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'>Humanity</td><td><input type='text' name='humanity' id='humanity' value='$humanity'>&nbsp;&nbsp;&nbsp;<a href='#' onclick='humanity.value=$humanity'>Reset to current humanity</a></td></tr>";
                    
                }
                echo "<tr><td style='font-weight: bold; font-size: 15px;'>Medical</td><td>";
                echo "<input type='checkbox' name='uncounscious' value='true' id='uncounscious'".($uncounscious=="false"?"":" checked")."><label for='uncounscious'>Uncounscious</label><br>";
                echo "<input type='checkbox' name='infected' value='true' id='infected'".($infected=="false"?"":" checked")."><label for='infected'>Infected</label><br>";
                echo "<input type='checkbox' name='pain' value='true' id='pain'".($pain=="false"?"":" checked")."><label for='pain'>Pain</label><br>";
                echo "<input type='checkbox' name='fullheal' value='true' id='fullheal'><label for='fullheal'>Heal completely (counscious, no infection, no pain, no broken leg, full blood)</label>";
                echo "</td></tr>";
                echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'>Skin</td><td><select name='model'>";
                foreach ($modellist AS $key => $value) {
    				
    				echo "<option value='".($survivor["model"]==$key?"0":$key)."'".($survivor["model"]==$key?" selected":"").">$value".($survivor["model"]==$key?" - Current":"")."</option>";
    				
                }
                echo $survivor["model"]."</select></td></tr>";
                echo "<tr><td style='font-weight: bold; font-size: 15px;'>Teleport to</td><td><select name='teleport'><option value='0' selected='selected'>Do not teleport</option>";
				$query = $dbh->prepare("SELECT profile.name, survivor.id FROM survivor, profile WHERE survivor.unique_id = profile.unique_id AND survivor.is_dead = '0' ORDER BY profile.name");
				$query->execute();
				$allplayers = $query->fetchAll();
                foreach ($allplayers as $teleportto) {
    				
    				echo "<option value='".$teleportto["id"]."'>".$teleportto["name"]."</option>";
    				
                }
                echo $survivor["model"]."</select></td></tr>";
                echo "<tr$alt><td style='font-weight: bold; font-size: 15px;'><input type='submit' value='Submit' style='width: 100%;'></td><td><a href=\"javascript:popUp('http://dayz.st/loadout?".URLVARS."&id=".$survivor["id"]."')\">Show inventory</a>";
                if ($worldid !== "/") echo "&nbsp;&nbsp;&nbsp;".(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false?"<a href=\"#posdiv\" onclick=\"toggle();\">Show/hide approximate position</a>":"<a href='http://www.dayzdb.com/map$worldid#5.$xcoords.$ycoords' target='_blank'>Show approximate position</a>");
                echo "</td></tr>";
                
                ?>
                
                </tbody></table></div></form>
                <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false AND $worldid !== "/") { ?>
                
                    <div style="display: none; margin-top: 20px;" class="iframe" id="posdiv"><iframe src="http://www.dayzdb.com/map<?php echo $worldid; ?>#5.<?php echo $xcoords.".".$ycoords;?>" width="100%" height="500px" seamless></iframe></div>
                
                <?php
                
                }
                
            }
            
        }
    
    }
    
}

?>
