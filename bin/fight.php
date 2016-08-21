<?php

	# Determine how many fights to simulate
	$fights = 1;
	
	for ($f = 0; $f < $fights; $f++) {
		
		# Identify two fighters
		# Pick one fighter at random
		$fighter_one = selectRandomFighter();
		writeLog("fight.php: Fighter 1: " . $fighter_one);
		
		# Pick second fighter with similar number of fights under their belt.
		$fighter_two = selectSimilarFighter($fighter_one);
		writeLog("fight.php: Fighter 2: " . $fighter_two);
		
		
		
		
		
		
		
		
		
		
	}
	
	


?>