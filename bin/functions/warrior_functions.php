<?php

function generateWarrior($parent_id) {
	
	#HEAD:Generates a new warrior (optionally with parent)
	
	writeLog("generateWarrior()");
	
	$parent = 0;
	$name = generateWarriorName();
	$stats = generateWarriorStats();

	if ($parent_id != 0) {
		$parent = $parent_id;
		
		# Pick highest attribute from parent, and use that
		$stats = getWarriorMaxStat($parent_id, $stats);
		
	}
	
	$dml = "INSERT INTO roguewarrior.warrior (warrior_name, 
									warrior_rank, 
									warrior_hp, 
									warrior_acc, 
									warrior_str, 
									warrior_spd, 
									warrior_dex, 
									warrior_con, 
									warrior_status, 
									warrior_parent,
									warrior_victories) 
								VALUES (
									'" . $name . "',
									'Trainee',
									'30',
									'" . $stats['accuracy'] . "',
									'" . $stats['strength'] . "',
									'" . $stats['speed'] . "',
									'" . $stats['dexterity'] . "',
									'" . $stats['constitution'] . "',
									'Alive',
									'" . $parent . "',
									'0'
								);";
				
	writeLog("generateWarrior(): DML: " . $dml);				
	$status = doInsert($dml);
		
}

function generateWarriorName() {
	
	#HEAD:Generates a warrior name
	
	writeLog("generateWarriorName()");
	$name = generateWarriorForename() . " the " . getWarriorTitle(); 
	writeLog("generateWarriorName(): " . $name);
	return $name;
		
}

function generateWarriorForename() {
	
	#HEAD:Generates a warrior forename
	
	writeLog("generateWarriorForename()");
	
	srand();
	$syllables = rand(2,4);
	writeLog("generateWarriorForename(): Syllables: " . $syllables);
	$forename = "";
	
	for ($s = 0; $s < $syllables; $s++) {
		
		$consonant = "a";
		while ($consonant == "a" || $consonant == "e"|| $consonant == "i" || $consonant == "o" || $consonant == "u") {
			$consonant = chr(rand(97, 122));
		}
		writeLog("generateWarriorForename(): Consonant: " . $consonant);
		
		$vowel = "x";
		while ($vowel != "a" && $vowel != "e"&& $vowel != "i" && $vowel != "o" && $vowel != "u") {
			$vowel = chr(rand(97, 122));
		}
		writeLog("generateWarriorForename(): Vowel: " . $vowel);
		
		$forename = $forename . $consonant . $vowel;
		$consonant = "a";
		$vowel = "x";
		
	}
	
	writeLog("generateWarriorForename(): Forename: " . $forename);
	
	return ucwords($forename);
	
}

function getWarriorTitle() {
	
	#HEAD:Generates a warrior's title
	
	writeLog("getWarriorTitle()");
	
	$titles = file('functions/lists/adjectives.txt');
	srand();
	
	$title = ucwords(trim($titles[rand(0, count($titles))]));
	writeLog("getWarriorTitle(): " . $title);	
	
	return $title;
	
}

function generateWarriorStats() {
	
	#HEAD:Generates a warrior's stats
	
	writeLog("generateWarriorStats()");
	
	$total = 100;
	srand();
	
	while ($total > 25 || $total < 20) {
		
		$accuracy = rand(2, 10);
		$dexterity = rand(2, 10);
		$strength = rand(2, 10);
		$constitution = rand(2, 10);
		$speed = rand(2, 10);
		
		$total = $accuracy + $dexterity + $strength + $constitution + $speed;
		writeLog("generateWarriorStats(): Total: " . $total);
	}
	
	$stats = array(
		'accuracy' => $accuracy, 
		'dexterity' => $dexterity, 
		'strength' => $strength, 
		'constitution' => $constitution, 
		'speed' => $speed
	);
	
	return $stats;
	
}

function countWarriors() {
	
	#HEAD:Counts how many live warriors there are
	
	writeLog("countWarriors()");
	
	$sql = "SELECT count(*) FROM roguewarrior.warrior WHERE warrior_status != 'Dead';";
	$results = doSearch($sql);
	
	return $results[0]['count(*)'];
	
}

function getWarriorMaxStat($warrior_id, $stats) {
	
	#HEAD:Determines a warrior maximum stat
	
	writeLog("getWarriorMaxStat()");
	
	$parent_stats = getAllWarriorDetails($warrior_id);
	
	$max_stat = 0;
	$max_stat_value = "";
	
	if (intval($parent_stats['warrior_acc']) > $max_stat) {
		$max_stat_value = intval($parent_stats['warrior_acc']);
		$max_stat= "accuracy";
	} elseif (intval($parent_stats['warrior_str']) > $max_stat) {
		$max_stat_value = intval($parent_stats['warrior_str']);
		$max_stat= "strength";
	} elseif (intval($parent_stats['warrior_spd']) > $max_stat) {
		$max_stat_value = intval($parent_stats['warrior_spd']);
		$max_stat= "speed";
	} elseif (intval($parent_stats['warrior_dex']) > $max_stat) {
		$max_stat_value  = intval($parent_stats['warrior_dex']);
		$max_stat= "dexterity";
	} elseif (intval($parent_stats['warrior_con']) > $max_stat) {
		$max_stat_value = intval($parent_stats['warrior_con']);
		$max_stat= "constitution";
	}
	
	$stats[$max_stat] = $max_stat_value;

	return $stats;
	
}

?>