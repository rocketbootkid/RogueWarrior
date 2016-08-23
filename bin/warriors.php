<html>

<head>
<title>Rogue Warrior | Warriors</title>
</head>

<body>

<h2>Warriors</h2>

<a href="roguewarrior.php">Home</a> | <a href="fights.php">Fights</a><p>

<?php

	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');
	include('functions/display_functions.php');

	# List of Live Warriors in fights won order
	
	liveWarriors();
	
	echo mostRecentFights(5);
	
	displayLog();
	
?>

</html>