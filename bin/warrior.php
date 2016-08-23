<html>

<head>
<title>Rogue Warrior | Warrior</title>
</head>

<body>

<?php

	if (isset($_GET['warrior'])) {

		$log = "";
		$connection = 0;

		include('functions/warrior_functions.php');
		include('functions/log_functions.php');
		include('functions/mysql_functions.php');
		include('functions/fight_functions.php');
		include('functions/display_functions.php');
		
		displayWarriorStats($_GET['warrior']);
		
		echo allWarriorFights($_GET['warrior']);
		
		#displayLog();

	} else {
		echo "Select a Warrior";
	}
	
?>

<p><div align=center><a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a> | <a href="fights.php">Fights</a></div>

</html>