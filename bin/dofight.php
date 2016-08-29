<?php

	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');

	# If there aren't enough warriors, create some new ones
	if (countWarriors() < 30) {
		for ($w = 0; $w < 10; $w++) { generateWarrior(0); }
	}
	
	# Keep picking warriors until two are found
	$warrior_one = 0;
	$warrior_two = 0;
	while ($warrior_one == 0 && $warrior_two == 0) {
		$warriors = chooseWarriors();
		$arrWarriors = explode(",", $warriors);
		$warrior_one = $arrWarriors[0];
		$warrior_two = $arrWarriors[1];
	}
	
	#Fight!
	doFight($warriors, "silent");
	
	#displayLog();
	
	header('Refresh: 5; URL=dofight.php');

?>