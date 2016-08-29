<?php
	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');	include('functions/display_functions.php');
			$arrRanks = buildRankArray();		var_dump($arrRanks);		$rank = 'Notorious';	$rank_number = 0;	for ($r = 0; $r < count($arrRanks); $r++) {		if ($arrRanks[$r][0] == $rank) {			$rank_number = $r;		}	}	echo $rank_number;	
	displayLog();

?>