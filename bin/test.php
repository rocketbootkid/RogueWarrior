<?php
	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');	include('functions/display_functions.php');
			mostRecentFights(10);		
	displayLog();

?>