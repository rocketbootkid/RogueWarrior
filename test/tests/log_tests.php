<?php

include('../bin/functions/log_functions.php');

$log = "";

echo "<tr bgcolor=#bbb><td colspan=3>log_functions.php Tests</tr>";

test_writeLog();
test_displayLog();



function test_writeLog() {
	
	# Setup
	$GLOBALS['log'] = "";
	$log_message = "test";
	
	# Test
	writeLog($log_message);
	
	# Assert
	if (substr_count($GLOBALS['log'], $log_message) == 1) {
		$status = "PASS";
	} else {
		$status = "FAIL";
	}
	
	# Log
	echo "<tr><td>" . $status . "<td>test_writeLog<td>" . $GLOBALS['log'] . "</tr>";
	
}

function test_displayLog() {

}

?>