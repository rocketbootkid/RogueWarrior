<?php

	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');

	# If there aren't enough warriors, create some new ones
	if (countWarriors() < 20) {
		for ($w = 0; $w < 10; $w++) { generateWarrior(0); }
	}
	
	doFight(chooseWarriors(), "silent");
	
	#displayLog();
	
	header('Refresh: 5; URL=dofight.php');

?>