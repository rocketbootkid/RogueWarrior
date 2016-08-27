<?php

function writeLog($message) {
	
	$GLOBALS['log'] = $GLOBALS['log'] . "\n<br/>" . date('Y-m-d H:i:s') . ": " . $message;
	
}

function displayLog() {
	
		echo "<p>Debug Log<hr>RDBMS Calls: " . $GLOBALS['calls'] . "<hr>" . $GLOBALS['log'];
	
}

function writeOperationToLog($command, $duration) {
	
	$file = fopen('../log/db_log.log', 'a');
	$text = date('Y-m-d H:i:s') . ": " . $command . " | " . $duration . "\n";
	fwrite($file, $text);
	fclose($file);
	
}

?>