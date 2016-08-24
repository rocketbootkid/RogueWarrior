<?php

function liveWarriors() {
	
	writeLog("liveWarriors()");
	
	$sql = "SELECT warrior_id FROM roguewarrior.warrior WHERE warrior_status = 'Alive';";
	$results = doSearch($sql);
	
	$arrWarriors = array();
	
	for ($w = 0; $w < count($results); $w++) {
		
		writeLog("liveWarriors(): Warrior ID: " . $results[$w]['warrior_id']);
		
		$details = array(getVictories($results[$w]['warrior_id']), $results[$w]['warrior_id']);
		array_push($arrWarriors, $details);
		
	}
	arsort($arrWarriors);
	
	displayWarriors($arrWarriors);
	
}

function getVictories($warrior_id) {

	writeLog("getVictories()");
	
	$sql = "SELECT count(*) FROM roguewarrior.results WHERE fight_winner = " . $warrior_id . ";";
	writeLog("liveWarriors(): SQL: " . $sql);
	$results = doSearch($sql);
	
	writeLog("getVictories(): Victories: " . $results[0]['count(*)']);
	
	return $results[0]['count(*)'];
	
}

function displayWarriors($arrWarriors) {
	
	writeLog("displayWarriors()");
	
	$text = "<table cellpadding=3 cellspacing=1 border=1>\n<tr bgcolor=#ddd><td>Name<td>Victories<td>SPD<td>ACC<td>DEX<td>STR<td>CON<td>Total</tr>\n";
	
	foreach ($arrWarriors as $warrior) {
		
		$warrior_details = getAllWarriorDetails($warrior[1]);
		
		$text = $text . "<tr><td><a href='warrior.php?warrior=" . $warrior_details['warrior_id'] . "'>The " . $warrior_details['warrior_rank'] . " " . $warrior_details['warrior_name'] . "</a>";
		$text = $text . "<td>" . generateGraph($warrior[0], 5, 'left');
		$text = $text . "<td>" . generateGraph($warrior_details['warrior_spd'], 5, 'left');
		$text = $text . "<td>" . generateGraph($warrior_details['warrior_acc'], 5, 'left');
		$text = $text . "<td>" . generateGraph($warrior_details['warrior_dex'], 5, 'left');
		$text = $text . "<td>" . generateGraph($warrior_details['warrior_str'], 5, 'left');
		$text = $text . "<td>" . generateGraph($warrior_details['warrior_con'], 5, 'left');
		
		$total = $warrior_details['warrior_spd'] + $warrior_details['warrior_acc'] + $warrior_details['warrior_dex'] + $warrior_details['warrior_str'] + $warrior_details['warrior_con'];
		$text = $text . "<td>" . generateGraph($total, 2, 'left');
		
		$text = $text . "</tr>\n";
		
	}
	
	$text = $text . "</table>";
	
	echo $text;
	
}	

function generateGraph($value, $multiplier, $legend) {
	
	writeLog("generateGraph()");
	
	$width = $value * $multiplier;
	if ($legend == "left") {
		$text = "<table cellpadding=0 cellspacing=2 border=0><tr><td width=20px align=center>" . $value . "<td width=" . $width . "px bgcolor=red></tr></table>";		
	} elseif ($legend == "right") {
		$text = "<table cellpadding=0 cellspacing=2 border=0><tr><td width=" . $width . "px bgcolor=red><td width=20px align=center>" . $value . "</tr></table>";
	}

	
	return $text;
	
}

function mostRecentFights($number) {
	
	writeLog("mostRecentFights()");
	
	$sql = "SELECT * FROM roguewarrior.results ORDER BY fight_id DESC LIMIT " . $number . ";";
	writeLog("mostRecentFights(): SQL: " . $sql);
	$results = doSearch($sql);
	
	$text = "<p><table cellpadding=3 cellspacing=1 border=1>\n<tr bgcolor=#ddd><td colspan=2>" . $number . " most recent fights</tr>";
	
	foreach ($results as $fight) {
		
		$winner_name = getWarriorAttribute($fight['fight_winner'], "warrior_name");
		$loser_name = getWarriorAttribute($fight['fight_loser'], "warrior_name");

		$text = $text . "<tr><td><a href='fight.php?fight=" . $fight['fight_id'] . "'>" . $fight['fight_id'] . "</a><td><a href='warrior.php?warrior=" . $fight['fight_winner'] . "'>" . $winner_name . "</a> defeated <a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>" . $loser_name . "</a> in " . $fight['fight_rounds'] . " rounds.</tr>";
		
	}
	
	$text = $text . "</table>";
	
	echo $text;
	
}

function displayWarriorStats($warrior_id) {
	
	writeLog("displayWarriorStats()");
	
	$details = getAllWarriorDetails($warrior_id);
	
	echo "<table cellpadding=3 cellspacing=1 border=1 align=center width=500px>\n";
	echo "<tr bgcolor=#ddd><td colspan=2 align=center><h2>The " . $details['warrior_rank'] . ", " . $details['warrior_name'] . "</h2></tr>";
	echo "<tr><td width=200px align=right>SPD<td>" . generateGraph($details['warrior_spd'], 5, 'left') . "</tr>";
	echo "<tr><td width=200px align=right>ACC<td>" . generateGraph($details['warrior_acc'], 5, 'left') . "</tr>";
	echo "<tr><td width=200px align=right>STR<td>" . generateGraph($details['warrior_dex'], 5, 'left') . "</tr>";
	echo "<tr><td width=200px align=right>DEX<td>" . generateGraph($details['warrior_str'], 5, 'left') . "</tr>";
	echo "<tr><td width=200px align=right>CON<td>" . generateGraph($details['warrior_con'], 5, 'left') . "</tr>";
	echo "</table>";
	
}

function allWarriorFights($warrior_id) {
	
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
		
		echo "<tr><td align=center><a href='fight.php?fight=" . $fight['fight_id'] . "'>WIN</a><td>Defeated <a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>The " . getWarriorAttribute($fight['fight_loser'], 'warrior_rank') . " " . getWarriorAttribute($fight['fight_loser'], 'warrior_name') . "</a> in " . $fight['fight_rounds'] . " rounds.</tr>";
		
	}
	
	echo "</table>";
	
}

function displayFights() {
	
	writeLog("displayFights()");
	
	echo "<p><table cellpadding=3 cellspacing=1 border=1 align=center width=800px>\n";
	echo "<tr bgcolor=#ddd><td align=center colspan=2><h3>Fight History</h3></tr>";
	
	$sql = "SELECT * FROM roguewarrior.results ORDER BY fight_id DESC LIMIT 50;";
	writeLog("displayFights(): SQL: " . $sql);
	$results = doSearch($sql);
	foreach ($results as $fight) {
		echo "<tr><td align=center><a href='fight.php?fight=" . $fight['fight_id'] . "'>" . $fight['fight_id'] . "</a><td><a href='warrior.php?warrior=" . $fight['fight_winner'] . "'>The " . getWarriorAttribute($fight['fight_winner'], 'warrior_rank') . " " . getWarriorAttribute($fight['fight_winner'], 'warrior_name') . "</a> defeated <a href='warrior.php?warrior=" . $fight['fight_loser'] . "'>The " . getWarriorAttribute($fight['fight_loser'], 'warrior_rank') . " " . getWarriorAttribute($fight['fight_loser'], 'warrior_name') . "</a> in " . $fight['fight_rounds'] . " rounds.</tr>";	
	}
	
	echo "</table>";
	
}

function displayFight($fight_id) {
	
	writeLog("displayFights()");
	
	$sql = "SELECT fight_log FROM roguewarrior.results WHERE fight_id = " . $fight_id . ";";
	writeLog("displayFights(): SQL: " . $sql);
	$results = doSearch($sql);
	
	echo "<div align=center>" . $results[0]['fight_log'] . "</div>";
	
	
}


?>