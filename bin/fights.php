<html>

<head>
<title>Rogue Warrior | Fights</title>
</head>

<body>

<div align=center><a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a></div>
<p>

<?php

	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');
	include('functions/display_functions.php');
	
	displayFights();
	
	displayLog();

?>

<p><div align=center><a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a></div>

</html>