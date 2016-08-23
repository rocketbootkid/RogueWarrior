<?php

function generateWarrior($parent_id) {
	
	writeLog("generateWarrior()");
	
	$parent = 0;
	if ($parent_id != 0) {
		$parent = $parent_id;
	}
	
	$name = generateWarriorName();
	$stats = generateWarriorStats();
	
	$dml = "INSERT INTO roguewarrior.warrior (warrior_name, 
									warrior_rank, 
									warrior_hp, 
									warrior_acc, 
									warrior_str, 
									warrior_spd, 
									warrior_dex, 
									warrior_con, 
									warrior_status, 
									warrior_parent) 
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
									'" . $parent . "'
								);";
				
	writeLog("generateWarrior(): DML: " . $dml);				
	$status = doInsert($dml);
		
}

function generateWarriorName() {
	
	writeLog("generateWarriorName()");
	$name = generateWarriorForename() . " the " . getWarriorTitle(); 
	writeLog("generateWarriorName(): " . $name);
	return $name;
		
}

function generateWarriorForename() {
	
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
	
	writeLog("getWarriorTitle()");
	
	$titles = file('functions/lists/adjectives.txt');
	srand();
	
	$title = ucwords(trim($titles[rand(0, count($titles))]));
	writeLog("getWarriorTitle(): " . $title);	
	
	return $title;
	
}

function generateWarriorStats() {
	
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
	
	writeLog("countWarriors()");
	
	$sql = "SELECT count(*) FROM roguewarrior.warrior WHERE warrior_status != 'Dead';";
	$results = doSearch($sql);
	
	return $results[0]['count(*)'];
	
}








?>