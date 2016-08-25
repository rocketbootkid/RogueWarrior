<?php
	$log = "";
	$connection = 0;

	include('functions/warrior_functions.php');
	include('functions/log_functions.php');
	include('functions/mysql_functions.php');
	include('functions/fight_functions.php');	include('functions/display_functions.php');
			$parent_stats = getAllWarriorDetails(100);		$max_stat = 0;	$max_stat_value = "";		if (intval($parent_stats['warrior_acc']) > $max_stat) {		$max_stat_value  = intval($parent_stats['warrior_acc']);		$max_stat= "acc";	} elseif (intval($parent_stats['warrior_str']) > $max_stat) {		$max_stat_value  = intval($parent_stats['warrior_str']);		$max_stat= "str";	} elseif (intval($parent_stats['warrior_spd']) > $max_stat) {		$max_stat_value  = intval($parent_stats['warrior_spd']);		$max_stat= "spd";	} elseif (intval($parent_stats['warrior_dex']) > $max_stat) {		$max_stat_value  = intval($parent_stats['warrior_dex']);		$max_stat= "dex";	} elseif (intval($parent_stats['warrior_con']) > $max_stat) {		$max_stat_value  = intval($parent_stats['warrior_con']);		$max_stat= "con";	}		echo $max_stat . ": " . $max_stat_value;		var_dump($parent_stats);		
	displayLog();

?>