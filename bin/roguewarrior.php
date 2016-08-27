<html>

<head>
<title>Rogue Warrior</title>
</head>

<body>

<h2>Rogue Warrior</h2>

<a href="warriors.php">Warriors</a> | 
<a href="legends.php">Legends</a> | 
<a href="fights.php">Fights</a> | 
<a href="dofight.php" target="_blank">Begin fights</a> (Close the page to stop)<p>

<?php

	$log = "";
	$connection = 0;
	error_reporting(0);

	include('functions/mysql_functions.php');
	include('functions/log_functions.php');
	include('functions/warrior_functions.php');
	
	if (!mysqli_connect('localhost', 'root', 'root', 'roguewarrior')) {
		createDatabase();
		for ($w = 0; $w < 20; $w++) { generateWarrior(0); }
	} else {
		mysqli_close($connection);
	}
	
?>

</html>