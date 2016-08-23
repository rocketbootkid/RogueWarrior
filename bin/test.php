<?php
	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');
	
	
	generateWarrior(0);	doFight(chooseWarriors());	
	displayLog();

?>