<html>

<head>
<title>Rogue Warrior | Legends</title>
</head>

<body>

<div align=center>

<h2>Legendary Warriors</h2>

<a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a> | <a href="fights.php">Fights</a><p>

<?php

	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');
	include('functions/display_functions.php');

	# List of Legendary Warriors in fights won order
	
	legendaryWarriors(10);
	
	#displayLog();
	
?>

</div>

</html>