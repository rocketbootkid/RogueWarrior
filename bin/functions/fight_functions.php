<?php

function doFight($warriors) {

	writeLog("doFight(): Warriors: " . $warriors);
	
	$warriors = explode(",", $warriors);
	$warrior_one = $warriors[0];
	$warrior_two = $warriors[1];

	# Get Warrior Details
	$arrWarriorOneStats = getAllWarriorDetails($warrior_one);
	$arrWarriorTwoStats = getAllWarriorDetails($warrior_two);

	# See who gets to attack first (Speed)
	if ($arrWarriorOneStats['warrior_spd'] > $arrWarriorTwoStats['warrior_spd']) {
		$arrAttacker = $arrWarriorOneStats;
		$arrDefender = $arrWarriorTwoStats;
		writeLog("doFight(): Warrior 1, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
	} else {
		$arrAttacker = $arrWarriorTwoStats;
		$arrDefender = $arrWarriorOneStats;
		writeLog("doFight(): Warrior 2, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
	}
	
	$round = 0;
	$fight_log = "<table cellpadding=3 cellspacing=3 border=1><tr><td>Round<td>Attacker - " . $arrAttacker['warrior_name'] . "<td>Defender - " . $arrDefender['warrior_name'] . "</tr>";
	
	while ($arrAttacker['warrior_hp'] > 0 && $arrDefender['warrior_hp'] > 0) {

		$round++;
		writeLog("doFight(): Round " . $round . "!");
		$fight_log = $fight_log . "<tr><td>" . $round;

		# First attacks / Second Defends
		
		# See if Attacker Hits
		if (rand(0, $arrAttacker['warrior_acc']) >= rand(0, $arrDefender['warrior_dex'])) {
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " hits!");
			# If hits, how much damage
			$damage = rand(1, $arrAttacker['warrior_str']) - floor(rand(1, $arrDefender['warrior_con'])/2);
			if ($damage < 0) { $damage = 0; }
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " does " . $damage . " damage to " . $arrDefender['warrior_name']);
			
			# Take damage away from Defender HP
			$arrDefender['warrior_hp'] = $arrDefender['warrior_hp'] - $damage;
			
			$fight_log = $fight_log . "<td>" . $arrAttacker['warrior_name'] . " hits " . $arrDefender['warrior_name'] . " for " . $damage . " points of damage!</br>" . $arrDefender['warrior_name'] . " has " . $arrDefender['warrior_hp'] . "HP remaining.";
					
		} else { # Miss
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " misses!");
			
			$fight_log = $fight_log . "<td>" . $arrAttacker['warrior_name'] . " misses " . $arrDefender['warrior_name'] . "!</br>" . $arrDefender['warrior_name'] . " has " . $arrDefender['warrior_hp'] . "HP remaining.";
			
		}
		
		# Second Attacks / First Defends

		# See if Attacker Hits
		if (rand(0, $arrDefender['warrior_acc']) >= rand(0, $arrAttacker['warrior_dex'])) {
			writeLog("doFight(): " . $arrDefender['warrior_name'] . " hits!");
			# If hits, how much damage
			$damage = rand(1, $arrDefender['warrior_str']) - floor(rand(1, $arrAttacker['warrior_con'])/2);
			writeLog("doFight(): " . $arrDefender['warrior_name'] . " does " . $damage . " damage to " . $arrAttacker['warrior_name']);
			
			# Take damage away from Defender
			$arrAttacker['warrior_hp'] = $arrAttacker['warrior_hp'] - $damage;
			
			$fight_log = $fight_log . "<td>" . $arrDefender['warrior_name'] . " hits " . $arrAttacker['warrior_name'] . " for " . $damage . " points of damage!</br>" . $arrAttacker['warrior_name'] . " has " . $arrAttacker['warrior_hp'] . "HP remaining.</tr>";
			
		} else {
			writeLog("doFight(): " . $arrDefender['warrior_name'] . " misses!");
			
			$fight_log = $fight_log . "<td>" . $arrDefender['warrior_name'] . " misses " . $arrAttacker['warrior_name'] . "!</br>" . $arrAttacker['warrior_name'] . " has " . $arrAttacker['warrior_hp'] . "HP remaining.</tr>";
			
		}
		
	}
	
	# Declare the winner
	if ($arrAttacker['warrior_hp'] > 0 && $arrDefender['warrior_hp'] <= 0) { # Attacker wins
		$winner = $arrAttacker['warrior_id'];
		$fight_log = $fight_log . "<tr><td colspan=3 align=center>After " . $round . " rounds, " . $arrAttacker['warrior_name'] . " is the victor!</tr>";
	} elseif ($arrDefender['warrior_hp'] > 0 && $arrAttacker['warrior_hp'] <= 0) { # Defender wins
		$winner = $arrDefender['warrior_id'];
		$fight_log = $fight_log . "<tr><td colspan=3 align=center>After " . $round . " rounds, " . $arrDefender['warrior_name'] . " is the victor!</tr>";
	} else {
		
	}
	
	$fight_log = $fight_log . "</table>";
	  
	echo $fight_log;
	  
	# Write fight log to database
	# fight_id
	# winner_id
	# loser_id
	# fight_log (string containing details of the fight as a table)

	# Update loser warrior record / set status to dead

	# Handle winner updates
	# If title changes (e.g. 5, 10, 15 wins, etc), also randomly choose attribute to buff

}

function chooseWarriors() {
	
	writeLog("chooseWarriors()");
	
	# Choose one warrior at random
	$warrior_one = chooseRandomWarrior();

	# Choose second warrior at same rank, or one rank higher or lower.
	$warrior_two = chooseSuitableWarrior($warrior_one);

	return $warrior_one  . "," . $warrior_two;

}
function chooseRandomWarrior() {

	writeLog("chooseRandomWarrior()");

	# Count the number of non-dead warriors in the database
	$count = countWarriors();
	  
	# Select one warrior and extract their warrior_id
	srand();
	$randomwarrior = rand(1, $count);
	writeLog("chooseRandomWarrior(): Warrior 1: " . $randomwarrior);

	return $randomwarrior;
	  
}

function chooseSuitableWarrior($warrior_one_id) {
	
	writeLog("chooseSuitableWarrior(): Warrior 1: " . $warrior_one_id);

	# Get rank of Warrior One
	$warrior_one_rank = getWarriorAttribute($warrior_one_id, 'warrior_rank');
	writeLog("chooseSuitableWarrior(): Warrior 1 Rank: " . $warrior_one_rank);
	
	$arrRanks = buildRankArray();

	# Convert to Rank Number
	$warrior_one_rank_number = array_search($warrior_one_rank,array_keys($arrRanks));
	writeLog("chooseSuitableWarrior(): Warrior 1 Rank Number: " . $warrior_one_rank_number);

	# Determine adjacent Rank Numbers, and if less than zero, set to zero
	# Convert adjacent Rank Numbers back to Ranks
	$previous_rank_number = $warrior_one_rank_number - 1;
	if ($previous_rank_number < 0) { $previous_rank_number = 0; }
	$previous_rank = $arrRanks[$previous_rank_number][0];
	writeLog("chooseSuitableWarrior(): Previous Rank Number: " . $previous_rank_number . ", Rank: " . $previous_rank);
	
	$next_rank_number = $warrior_one_rank_number + 1;
	$next_rank = $arrRanks[$next_rank_number][0];
	writeLog("chooseSuitableWarrior(): Next Rank Number: " . $next_rank_number . ", Rank: " . $next_rank);

	# Search for Warrior;
	#   That's not dead
	#   That is one of the three ranks
	#   That is not Warrior One
	$sql = "SELECT warrior_id FROM roguewarrior.warrior WHERE (warrior_rank = '" . $warrior_one_rank . "' OR warrior_rank = '" . $previous_rank . "' OR warrior_rank = '" . $next_rank . "') AND warrior_id NOT LIKE " . $warrior_one_id . " AND warrior_status NOT LIKE 'Dead';";
	writeLog("chooseSuitableWarrior(): SQL: " . $sql);
	$results = doSearch($sql);
	
	writeLog("chooseSuitableWarrior(): Possible Foes: " . count($results));
	$rows = count($results) - 1;
	
	
	$warrior_two = $results[rand(0,$rows)]['warrior_id'];
	writeLog("chooseSuitableWarrior(): Warrior 2: " . $warrior_two);
	
	return $warrior_two;

}

function getWarriorAttribute($warrior_id, $attribute) {

	writeLog("getWarriorAttribute()");
	
	$sql = "SELECT " . $attribute . " FROM roguewarrior.warrior WHERE warrior_id = " . $warrior_id . ";";
	$results = doSearch($sql);
	
	return $results[0][$attribute];

}

function getAllWarriorDetails($warrior_id) {

	writeLog("getAllWarriorDetails()");

	# Search for warrior
	$sql = "SELECT * FROM roguewarrior.warrior WHERE warrior_id = " . $warrior_id . ";";
	$results = doSearch($sql);
	
	return $results[0];

}

function buildRankArray() {
	
	writeLog("buildRankArray()");

	# Read ranks.txt into a 2D array
	$file_contents = file('functions/lists/ranks.txt');
	$rows = count($file_contents);
	$arrRanks = array();
	for ($r = 0; $r < $rows; $r++) {
		$row_details = explode(",", $file_contents[$r]);
		$arrRanks[$r] = array($row_details[0], $row_details[1]);
	}

	return $arrRanks;

}




?>
