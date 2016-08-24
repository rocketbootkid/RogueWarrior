<html>

<head>
<title>Rogue Warrior | Fight</title>
</head>

<body>

<div align=center><a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a> | <a href="fights.php">Fights</a></div>
<p>

<?php

	if (isset($_GET['fight'])) {

		$log = "";
		$connection = 0;

		include('functions/warrior_functions.php');
		include('functions/log_functions.php');
		include('functions/mysql_functions.php');
		include('functions/fight_functions.php');
		include('functions/display_functions.php');
		
		displayFight($_GET['fight']);
		
		#displayLog(); 
	
	} else {
		echo "Select a fight.";
	}

?>

<p><div align=center><a href="roguewarrior.php">Home</a> | <a href="warriors.php">Warriors</a> | <a href="fights.php">Fights</a></div>

</html>