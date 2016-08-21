<?php
	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');
	
	
	$Warrior_one_id = selectRandomWarrior();
	$Warrior_two_id = selectSimilarWarrior($Warrior_one_id);
	
	doFight($Warrior_one_id, $Warrior_two_id);
	
	displayLog();







?>