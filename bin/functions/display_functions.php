<?php

function liveWarriors() {
	
	#HEAD:Lists the current live warriors, ordered by their victories, and their stats
	
	writeLog("liveWarriors()");
	
	$sql = "SELECT warrior_id FROM roguewarrior.warrior WHERE warrior_status = 'Alive' ORDER BY warrior_victories DESC;";
	$results = doSearch($sql);
	
	displayWarriors($results);
	
}

function legendaryWarriors($limit) {
	
	#HEAD:Lists the best warriors ever, ordered by their victories, and their stats
	
	writeLog("legendaryWarriors()");
	
	$sql = "SELECT warrior_id FROM roguewarrior.warrior ORDER BY warrior_victories DESC LIMIT " . $limit . ";";
	$results = doSearch($sql);
	
	displayWarriors($results);
	
}

function displayWarriors($arrWarriors) {
	
	#HEAD:Builds a table of current warriors, their traits and stats, from provided array
	
	writeLog("displayWarriors()");
	
	$text = "<table cellpadding=3 cellspacing=1 border=1>\n<tr bgcolor=#ddd><td>Rank<td>Name<td>Traits<td align=center>Victories<td align=center>SPD<td align=center>ACC<td align=center>DEX<td align=center>STR<td align=center>CON<td align=center>Total</tr>\n";
	
	foreach ($arrWarriors as $warrior) {
		
		$warrior_details = getAllWarriorDetails($warrior['warrior_id']);
		
		if ($warrior_details <> 0) {
				
			$text = $text . "<tr>";
			$text = $text . "<td>" . $warrior_details['warrior_rank'];
			$text = $text . "<td><a href='warrior.php?warrior=" . $warrior_details['warrior_id'] . "'>" . $warrior_details['warrior_name'] . "</a>";
			$text = $text . "<td>" . warriorTraits($warrior_details['warrior_id']);
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_victories'], 5, 'right');
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_spd'], 5, 'right');
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_acc'], 5, 'right');
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_dex'], 5, 'right');
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_str'], 5, 'right');
			$text = $text . "<td>" . generateGraph($warrior_details['warrior_con'], 5, 'right');
			
			$total = $warrior_details['warrior_spd'] + $warrior_details['warrior_acc'] + $warrior_details['warrior_dex'] + $warrior_details['warrior_str'] + $warrior_details['warrior_con'];
			$text = $text . "<td>" . generateGraph($total, 2, 'right');
			
			$text = $text . "</tr>\n";
		
		}
		
	}
	
	$text = $text . "</table>";
	
	echo $text;
	
}	

function generateGraph($value, $multiplier, $legend) {
	
	#HEAD:Generates a simple bar graph for the provided parameters
	
	writeLog("generateGraph()");
	
	$width = $value * $multiplier;
	if ($legend == 'left') {
		$text = "<table cellpadding=0 cellspacing=2 border=0 width=100%><tr><td align=right>" . $value . "<td width=" . $width . "px bgcolor=red></tr></table>";		
	} elseif ($legend == 'right') {
		$text = "<table cellpadding=0 cellspacing=2 border=0 width=100%><tr><td width=" . $width . "px bgcolor=red><td align=left>" . $value . "</tr></table>";
	}
	
	return $text;
	
}

function mostRecentFights($number) {
	
	#HEAD:Generates a table of the most recent fights
	
	writeLog("mostRecentFights()");
	
	$sql = "SELECT * FROM roguewarrior.results ORDER BY fight_id DESC LIMIT " . $number . ";";
	writeLog("mostRecentFights(): SQL: " . $sql);
	$results = doSearch($sql);
	
	$text = "<p><table cellpadding=3 cellspacing=1 border=1>\n<tr bgcolor=#ddd><td colspan=2>" . $number . " most recent fights</tr>";
	
	foreach ($results as $fight) {
		
		$winner_name = getWarriorAttribute($fight['fight_winner'], "warrior_name");
		if ($winner_name == "") { 
			$winner_name = "A nameless warrior"; 
		} else {
			$winner_name = "<a href='warrior.php?warrior=" . $fight['fight_winner'] . "'>" . $winner_name . "</a>";
		}


		
		$loser_name = getWarriorAttribute($fight['fight_loser'], "warrior_name");
		if ($loser_name == "") { 
			$loser_name = "a nameless warrior"; 
		} else {
			$loser_name = "<a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>" . $loser_name . "</a>";
		}

		$text = $text . "<tr><td><a href='fight.php?fight=" . $fight['fight_id'] . "'>" . $fight['fight_id'] . "</a><td>" . $winner_name . " defeated " . $loser_name . " in " . $fight['fight_rounds'] . " rounds.</tr>";
		
	}
	
	$text = $text . "</table>";
	
	echo $text;
	
}

function displayWarriorStats($warrior_id) {
	
	#HEAD:Displays the stats for the provided warrior
	
	writeLog("displayWarriorStats()");
	
	$details = getAllWarriorDetails($warrior_id);
	
	$text = "";
	
	$text = $text . "<table cellpadding=3 cellspacing=1 border=1 align=center width=500px>\n";
	$text = $text . "<tr bgcolor=#ddd><td colspan=2 align=center><h1>The " . $details['warrior_rank'] . " " . $details['warrior_name'] . "</h1><h3>" . warriorTraits($warrior_id) . "</h3></tr>";
	$text = $text . "<tr><td width=200px align=right>SPD<td>" . generateGraph($details['warrior_spd'], 5, 'left') . "</tr>";
	$text = $text . "<tr><td width=200px align=right>ACC<td>" . generateGraph($details['warrior_acc'], 5, 'left') . "</tr>";
	$text = $text . "<tr><td width=200px align=right>STR<td>" . generateGraph($details['warrior_str'], 5, 'left') . "</tr>";
	$text = $text . "<tr><td width=200px align=right>DEX<td>" . generateGraph($details['warrior_dex'], 5, 'left') . "</tr>";
	$text = $text . "<tr><td width=200px align=right>CON<td>" . generateGraph($details['warrior_con'], 5, 'left') . "</tr>";
	$text = $text . "</table>";
	
	return $text;
	
}

function allWarriorFights($warrior_id) {
	
	#HEAD:Generates a list of all fights in which the provided warrior has fought
	
	writeLog("allWarriorFights()");
	
	echo "<p><table cellpadding=3 cellspacing=1 border=1 align=center width=500px>\n";
	echo "<tr bgcolor=#ddd><td align=center colspan=2><h3>Fight History</h3></tr>";
	
	# Loss
	$sql = "SELECT * FROM roguewarrior.results WHERE fight_loser = " . $warrior_id . " ORDER BY fight_id DESC LIMIT 1;";
	writeLog("allWarriorFights(): SQL: " . $sql);
	$results = doSearch($sql);
	foreach ($results as $fight) {
		echo "<tr><td align=center><a href='fight.php?fight=" . $fight['fight_id'] . "'>LOSS</a><td>Defeated by <a href='warrior.php?warrior=" . $fight['fight_winner'] . "'>The " . getWarriorAttribute($fight['fight_winner'], 'warrior_rank') . " " . getWarriorAttribute($fight['fight_winner'], 'warrior_name') . "</a> in " . $fight['fight_rounds'] . " rounds.</tr>";	
	}	
	
	# Wins
	$sql = "SELECT * FROM roguewarrior.results WHERE fight_winner = " . $warrior_id . " ORDER BY fight_id DESC;";
	writeLog("allWarriorFights(): SQL: " . $sql);
	$results = doSearch($sql);

	foreach ($results as $fight) {
		
		echo "<tr><td align=center><a href='fight.php?fight=" . $fight['fight_id'] . "'>WIN</a>";
		
		$loser_name = getWarriorAttribute($fight['fight_loser'], 'warrior_name');
		$loser_rank = getWarriorAttribute($fight['fight_loser'], 'warrior_rank');
		if ($loser_name != "" && $loser_rank != "") {
			echo "<td>Defeated <a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>The " . $loser_rank . " " . $loser_name . "</a> in " . $fight['fight_rounds'] . " rounds.</tr>";
		} else {
			echo "<td>Defeated a nameless warrior in " . $fight['fight_rounds'] . " rounds.</tr>";
		}
		
	}
	
	echo "</table>";
	
}

function displayFights() {
	
	#HEAD:Displays a list of the most recent fights.
	
	writeLog("displayFights()");
	
	echo "<p><table cellpadding=3 cellspacing=1 border=1 align=center width=800px>\n";
	echo "<tr bgcolor=#ddd><td align=center colspan=5><h3>Fight History</h3></tr>";
	echo "<tr bgcolor=#ddd><td align=center>ID<td align=right>Winner<td><td>Loser<td align=center>Rounds</tr>";
	
	$sql = "SELECT * FROM roguewarrior.results ORDER BY fight_id DESC LIMIT 50;";
	writeLog("displayFights(): SQL: " . $sql);
	$results = doSearch($sql);
	foreach ($results as $fight) {
		echo "<tr><td align=center><a href='fight.php?fight=" . $fight['fight_id'] . "'>" . $fight['fight_id'] . "</a>";
		$winner_rank = getWarriorAttribute($fight['fight_winner'], 'warrior_rank');
		$winner_name = getWarriorAttribute($fight['fight_winner'], 'warrior_name');
		writeLog("displayFights(): Winner: The " . $winner_rank . " " . $winner_name);
		if ($winner_rank != "" && $winner_name != "") {
			echo "<td align=right><a href='warrior.php?warrior=" . $fight['fight_winner'] . "'>The " . $winner_rank . " " . $winner_name . "</a>";
		} else {
			echo "<td align=right>Some nameless warrior";
		}
		
		echo "<td align=center>defeated";
		$loser_rank = getWarriorAttribute($fight['fight_loser'], 'warrior_rank');
		$loser_name = getWarriorAttribute($fight['fight_loser'], 'warrior_name');
		writeLog("displayFights(): Winner: The " . $loser_rank . " " . $loser_name);
		if ($loser_rank != "" && $loser_name != "") {
			echo "<td><a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>The " .$loser_rank . " " . $loser_name . "</a>";
		} else {
			echo "<td align=left>Some nameless warrior";
		}
		echo "<td align=center>" . $fight['fight_rounds'];
		echo "</tr>";	
	}
	
	echo "</table>";
	
}

function displayFight($fight_id) {
	
	#HEAD:Extracts and displays the stored fight log for the provided fight.
	
	writeLog("displayFights()");
	
	$sql = "SELECT fight_log FROM roguewarrior.results WHERE fight_id = " . $fight_id . ";";
	writeLog("displayFights(): SQL: " . $sql);
	$results = doSearch($sql);
	
	echo "<div align=center>" . $results[0]['fight_log'] . "</div>";
	
	
}

function warriorTraits($warrior_id) {
	
	#HEAD:Generates a list of the traits for the provided warrior
	
	writeLog("warriorTraits(): Warrior ID: " . $warrior_id);
	
	$traits = "";
	$details = getAllWarriorDetails($warrior_id);
		
	if ($details['warrior_acc'] >=8) {
		$traits = $traits . "Accurate ";
	}
	if ($details['warrior_str'] >=8) {
		$traits = $traits . "Mighty ";
	}
	if ($details['warrior_spd'] >=8) {
		$traits = $traits . "Fast ";
	}
	if ($details['warrior_dex'] >=8) {
		$traits = $traits . "Nimble ";
	}
	if ($details['warrior_con'] >=8) {
		$traits = $traits . "Stalwart ";
	}
	
	return trim($traits);

}

function displayWarriorFamily($warrior_id) {
	
	#HEAD:Displays the provided warrior's father and sons
	
	writeLog("displayWarriorFamily(): Warrior ID: " . $warrior_id);
	
	$text = "<table cellpadding=3 cellspacing=1 border=1 align=center width=500px>";
	
	# Get Parent
	$parent_id = getWarriorAttribute($warrior_id, 'warrior_parent');
	$parent_name = getWarriorAttribute($parent_id, 'warrior_name');
	$parent_rank = getWarriorAttribute($parent_id, 'warrior_rank');
	if ($parent_name != "" && $parent_rank != "") {
		$text = $text . "<tr><td width=50px>Father<td><a href='warrior.php?warrior=" . $parent_id . "'>The " . $parent_rank . " " . $parent_name . "</a></tr>";
	} else {
		$text = $text . "<tr><td width=50px>Father<td>Some nameless warrior</tr>";
	}
	
	
	# Get Warrior Name
	$warrior_name = getWarriorAttribute($warrior_id, 'warrior_name');
	$warrior_rank = getWarriorAttribute($warrior_id, 'warrior_rank');
	$text = $text . "<tr><td colspan=2><strong>The " . $warrior_rank . " " . $warrior_name . "</strong></tr>";
	
	# Get Children
	$sql = "SELECT warrior_id FROM roguewarrior.warrior WHERE warrior_parent = " . $warrior_id . ";";
	writeLog("displayWarriorFamily(): SQL: " . $sql);
	$results = doSearch($sql);
		
	foreach ($results as $child) {
		
		$child_name = getWarriorAttribute($child['warrior_id'], 'warrior_name');
		$child_rank = getWarriorAttribute($child['warrior_id'], 'warrior_rank');
		$text = $text . "<tr><td>Son<td><a href='warrior.php?warrior=" . $child['warrior_id'] . "'>The " . $child_rank . " " . $child_name . "</a></tr>";
		
	}
	
	$text = $text . "</table>";
	
	return $text;
	
}

?>