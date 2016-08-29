<?php

function doFight($warriors, $mode) {
	
	#HEAD: Handles the fight between the two warriors

	writeLog("doFight(): Warriors: " . $warriors);
	
	$warriors = explode(",", $warriors);
	$warrior_one = $warriors[0];
	$warrior_two = $warriors[1];

	# Get Warrior Details
	$arrWarriorOneStats = getAllWarriorDetails($warrior_one);
	$arrWarriorTwoStats = getAllWarriorDetails($warrior_two);

	# See who gets to attack first (Speed)
	if ($arrWarriorOneStats['warrior_spd'] > $arrWarriorTwoStats['warrior_spd']) { # Warrior 1 is faster
		$arrAttacker = $arrWarriorOneStats;
		$arrDefender = $arrWarriorTwoStats;
		writeLog("doFight(): Warrior 1, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
	} elseif ($arrWarriorOneStats['warrior_spd'] < $arrWarriorTwoStats['warrior_spd']) { # Warrior 2 is faster
		$arrAttacker = $arrWarriorTwoStats;
		$arrDefender = $arrWarriorOneStats;
		writeLog("doFight(): Warrior 2, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
	} else { # Same speed
		writeLog("doFight(): Both Warriors just as fast!");
		$choice = rand(0, 1);
		if ($choice == 0) { # Warrior 1
			$arrAttacker = $arrWarriorOneStats;
			$arrDefender = $arrWarriorTwoStats;
			writeLog("doFight(): Warrior 1, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
		} else { # Warrior 2
			$arrAttacker = $arrWarriorTwoStats;
			$arrDefender = $arrWarriorOneStats;
			writeLog("doFight(): Warrior 2, " . $arrAttacker['warrior_name'] . " goes first, then " . $arrDefender['warrior_name']);
		}
	}
	
	$round = 0;
	$fight_log = "<table cellpadding=3 cellspacing=1 border=1>";
	
	$fight_log = $fight_log . "<tr bgcolor=#ddd><td>Round<td align=center colspan=2>Attacker<td align=center colspan=2>Defender</tr>";
		
	$fight_log = $fight_log . "<tr bgcolor=#ddd><td>";
	$fight_log = $fight_log . "<td align=center colspan=2><h3><a href=warrior.php?warrior=" . $arrAttacker['warrior_id'] . ">The " . $arrAttacker['warrior_rank'] . " " . $arrAttacker['warrior_name'] . "</a></h3>";
	$fight_log = $fight_log . "<td align=center colspan=2><h3><a href=warrior.php?warrior=" . $arrDefender['warrior_id'] . ">The " . $arrDefender['warrior_rank'] . " " . $arrDefender['warrior_name'] . "</a></h3>";
	$fight_log = $fight_log . "</tr>";
	
	while ($arrAttacker['warrior_hp'] > 0 && $arrDefender['warrior_hp'] > 0) {

		$round++;
		writeLog("doFight(): Round " . $round . "!");

		# First attacks / Second Defends
		
		# See if Attacker Hits
		if (rand(0, $arrAttacker['warrior_acc']) >= rand(0, $arrDefender['warrior_dex'])) {
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " hits!");
			# If hits, how much damage
			$damage = rand(1, $arrAttacker['warrior_str']) - floor(rand(1, $arrDefender['warrior_con'])/2);
			if ($damage < 1) { $damage = 1; }
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " does " . $damage . " damage to " . $arrDefender['warrior_name']);
			
			# Take damage away from Defender HP
			$arrDefender['warrior_hp'] = $arrDefender['warrior_hp'] - $damage;
			
			$attacker_details = "<td>Hits for " . $damage . " points of damage!";
			$defender_hp = "<td align=right>" . generateGraph($arrDefender['warrior_hp'], 3, 'right');
					
		} else { # Miss
			writeLog("doFight(): " . $arrAttacker['warrior_name'] . " misses!");
			
			$attacker_details = "<td>Misses!";
			$defender_hp = "<td align=right>" . generateGraph($arrDefender['warrior_hp'], 3, 'right');
			
		}
		
		# Second Attacks / First Defends

		if ($arrDefender['warrior_acc'] > 0) { # Check that defender is not already dead
		
			# See if Attacker Hits
			if (rand(0, $arrDefender['warrior_acc']) >= rand(0, $arrAttacker['warrior_dex'])) {
				writeLog("doFight(): " . $arrDefender['warrior_name'] . " hits!");
				# If hits, how much damage
				$damage = rand(1, $arrDefender['warrior_str']) - floor(rand(1, $arrAttacker['warrior_con'])/2);
				if ($damage < 1) { $damage = 1; }
				writeLog("doFight(): " . $arrDefender['warrior_name'] . " does " . $damage . " damage to " . $arrAttacker['warrior_name']);
				
				# Take damage away from Defender
				$arrAttacker['warrior_hp'] = $arrAttacker['warrior_hp'] - $damage;
				
				$defender_details = "<td>Hits for " . $damage . " points of damage!";
				$attacker_hp = "<td>" . generateGraph($arrAttacker['warrior_hp'], 3, 'left');
								
			} else {
				writeLog("doFight(): " . $arrDefender['warrior_name'] . " misses!");
				
				$defender_details = "<td>Misses!";
				$attacker_hp = "<td>" . generateGraph($arrAttacker['warrior_hp'], 3, 'left');
				
			}
		
		} else {
			writeLog("doFight(): " . $arrDefender['warrior_name'] . " already dead!");
			
			$fight_log = $fight_log . "<td>" . $arrDefender['warrior_name'] . " does nothing, because he is dead.</tr>";
			
		}
		
		$fight_log = $fight_log . "<tr><td align=center>" . $round . $attacker_details . $attacker_hp . $defender_hp . $defender_details . "</tr>";
		
	}
	
	# Declare the winner
	if ($arrAttacker['warrior_hp'] > 0 && $arrDefender['warrior_hp'] <= 0) { # Attacker wins
		$winner = $arrAttacker['warrior_id'];
		$loser = $arrDefender['warrior_id'];
		$fight_log = $fight_log . "<tr bgcolor=#ddd><td colspan=5 align=center>After " . $round . " rounds, <a href=warrior.php?warrior=" . $arrAttacker['warrior_id'] . ">The " . $arrAttacker['warrior_rank'] . " " . $arrAttacker['warrior_name'] . "</a> is the victor!</tr>";
	} elseif ($arrDefender['warrior_hp'] > 0 && $arrAttacker['warrior_hp'] <= 0) { # Defender wins
		$winner = $arrDefender['warrior_id'];
		$loser = $arrAttacker['warrior_id'];
		$fight_log = $fight_log . "<tr bgcolor=#ddd><td colspan=5 align=center>After " . $round . " rounds, <a href=warrior.php?warrior=" . $arrDefender['warrior_id'] . ">The " . $arrDefender['warrior_rank'] . " " . $arrDefender['warrior_name'] . "</a> is the victor!</tr>";
	} else {
		writeLog("doFight(): Attacker HP: " . $arrAttacker['warrior_hp']);
		writeLog("doFight(): Defender HP: " . $arrDefender['warrior_hp']);
		$winner = $arrAttacker['warrior_id'];
		$loser = $arrDefender['warrior_id'];
	}
	
	$fight_log = $fight_log . "</table>";
	 
	# Don't output the data so that redirect page will work.
	if ($mode != "silent") {
		echo $fight_log;
	}
	  
	writeLog("doFight(): Fight Log length: " . strlen($fight_log));
	  
	# Write fight log to database
	recordFight($winner, $loser, $round, $fight_log);

	# Update loser warrior record / set status to dead
	updateLoser($loser);

	# Handle winner updates
	updateWinner($winner);
	
}

function chooseWarriors() {
	
	#HEAD:Handles the selection of the two warriors
	
	writeLog("chooseWarriors()");
	
	# Choose one warrior at random
	$warrior_one = chooseRandomWarrior();

	# Choose second warrior at same rank, or one rank higher or lower.
	$warrior_two = chooseSuitableWarrior($warrior_one);

	return $warrior_one  . "," . $warrior_two;

}
function chooseRandomWarrior() {
	
	#HEAD:Chooses a random warrior

	writeLog("chooseRandomWarrior()");

	$sql = "SELECT warrior_id FROM roguewarrior.warrior WHERE warrior_status = 'Alive';";		
	writeLog("chooseRandomWarrior(): SQL: " . $sql);
	$results = doSearch($sql);
	$count = count($results) - 1;
	writeLog("chooseRandomWarrior(): Potential Warriors: " . $count);
	
	$randomwarrior = $results[rand(0, $count)]['warrior_id'];

	writeLog("chooseRandomWarrior(): Random Warrior: " . $randomwarrior);
	
	return $randomwarrior;
		 
}

function chooseSuitableWarrior($warrior_one_id) {
	
	#HEAD:Chooses a suitable opponent for the provided warrior
	
	writeLog("chooseSuitableWarrior(): Warrior 1: " . $warrior_one_id);

	# Get rank of Warrior One
	$warrior_one_rank = getWarriorAttribute($warrior_one_id, 'warrior_rank');
	writeLog("chooseSuitableWarrior(): Warrior 1 Rank: " . $warrior_one_rank);
	
	$arrRanks = buildRankArray();

	# Convert to Rank Number
	$warrior_one_rank_number = 0;
	for ($r = 0; $r < count($arrRanks); $r++) {
		if ($arrRanks[$r][0] == $warrior_one_rank) {
			$warrior_one_rank_number = $r;
		}
	}
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
	
	if (count($results) != 0) {
	
		$warrior_two = $results[rand(0,$rows)]['warrior_id'];
		writeLog("chooseSuitableWarrior(): Warrior 2: " . $warrior_two);
		
		$warrior_two_rank = getWarriorAttribute($warrior_two, 'warrior_rank');
		writeLog("chooseSuitableWarrior(): Warrior 1 Rank: " . $warrior_one_rank);
		writeLog("chooseSuitableWarrior(): Warrior 2 Rank: " . $warrior_two_rank);
	} else {
		$warrior_two = 0;
	}
	
	return $warrior_two;

}

function getWarriorAttribute($warrior_id, $attribute) {

	#HEAD:Returns the selected attribute for the provided warrior

	writeLog("getWarriorAttribute()");
	
	$sql = "SELECT " . $attribute . " FROM roguewarrior.warrior WHERE warrior_id = " . $warrior_id . ";";
	$results = doSearch($sql);
	if (count($results) == 0) { 
		return "";
	} else {
		return $results[0][$attribute];
	}
	
}


function getAllWarriorDetails($warrior_id) {

	#HEAD:Returns all attributes for the provided warrior

	writeLog("getAllWarriorDetails()");

	# Search for warrior
	$sql = "SELECT * FROM roguewarrior.warrior WHERE warrior_id = " . $warrior_id . ";";
	$results = doSearch($sql);
	if (count($results) > 0) {
		return $results[0];
	} else {
		return "";
	}

}

function buildRankArray() {
	
	#HEAD:Builds the array of the ranks / levels
	
	writeLog("buildRankArray()");

	# Read ranks.txt into a 2D array
	$file_contents = file('functions/lists/ranks.txt');
	$rows = count($file_contents);
	$arrRanks = array();
	for ($r = 0; $r < $rows; $r++) {
		$row_details = explode(",", $file_contents[$r]);
		$arrRanks[$r] = array(trim($row_details[0]), trim($row_details[1]));
	}

	return $arrRanks;

}

function recordFight($winner, $loser, $round, $fight_log) {
	
	#HEAD:Records the details of the fight
	
	writeLog("recordFight()");
	
	$dml = "INSERT INTO roguewarrior.results (fight_winner, 
									fight_loser, 
									fight_rounds, 
									fight_log) 
								VALUES (
									" . $winner . ",
									" . $loser . ",
									" . $round . ",
									'" . $fight_log . "'
								);";

	writeLog("recordFight(): DML: " . $dml);												
	$status = doInsert($dml);
	
}

function updateLoser($loser) {
	
	#HEAD:Updates the losing warrior
	
	writeLog("updateLoser()");
	
	# Determine how many victories for the loser
	$victories = getWarriorVictories($loser);
	
	$dml = "UPDATE roguewarrior.warrior SET warrior_status = 'Dead' WHERE warrior_id = " . $loser . ";";
	writeLog("updateLoser(): DML: " . $dml);												
	$status = doInsert($dml);
		
}

function getWarriorVictories($warrior_id) {
	
	#HEAD:Returns the number of victories for the provided warrior
	
	writeLog("getWarriorVictories()");
	
	return getWarriorAttribute($warrior_id, 'warrior_victories');
	
}

function updateWarriorVictories($warrior_id) {

	#HEAD:Updates the number of victories for the provided warrior
	
	writeLog("setWarriorVictories()");
	
	$dml = "UPDATE roguewarrior.warrior SET warrior_victories = warrior_victories + 1 WHERE warrior_id = " . $warrior_id . ";";
	writeLog("updateWarriorVictories(): DML: " . $dml);												
	$status = doInsert($dml);

	$sql = "SELECT warrior_victories FROM roguewarrior.warrior WHERE warrior_id = " . $warrior_id . ";";
	writeLog("updateWarriorVictories(): SQL: " . $sql);
	$results = doSearch($sql);
	
	return $results[0]['warrior_victories'];	
	
}

function updateWinner($warrior_id) {
	
	#HEAD:Updates the winning warrior
	
	writeLog("updateWinner()");
	
	# Update / Get winner's current number of victories
	$victories = updateWarriorVictories($warrior_id);
	writeLog("doFight(): New Victories: " . $victories);
		
	# Extract that rank title from array
	$new_rank = findRank($victories);
	writeLog("updateWinner(): New Rank: " . $new_rank);
	
	if ($new_rank != "") {
		# Update winner's title
		$dml = "UPDATE roguewarrior.warrior SET warrior_rank = '" . $new_rank . "' WHERE warrior_id = " . $warrior_id . ";";
		writeLog("updateWinner(): DML: " . $dml);												
		$status = doInsert($dml);
		
		# Also spawn new warrior with 2 of the parent's stats kept, and the others random 
		generateWarrior($warrior_id);

		# Buff random attribute
		$arrWarriorStats = getAllWarriorDetails(300);
		$arrAttributes = array('warrior_acc', 'warrior_str', 'warrior_spd', 'warrior_dex', 'warrior_con');
		$attribute = $arrAttributes[rand(0, 4)];

		$dml = "UPDATE roguewarrior.warrior SET " . $attribute . " = " . $attribute . " + 1 WHERE warrior_id = " . $warrior_id . ";";
		writeLog("updateWinner(): DML: " . $dml);												
		$status = doInsert($dml);	
		
	}
	
}

function findRank($new_rank_id) {
	
	writeLog("findRank(): New Rank ID: " . $new_rank_id);
	
	$arrRanks = buildRankArray();
	
	#var_dump($arrRanks);
	
	$new_rank = "";
	
	foreach ($arrRanks as $rank) {
	
		if ($rank[1] == $new_rank_id) {
			$new_rank = $rank[0];	
		}
		
	}
	
	writeLog("findRank(): New Rank: " . $new_rank);
	
	return $new_rank;
	
}


?>
