<?php

function writeLog($message) {
	
	$GLOBALS['log'] = $GLOBALS['log'] . "\n<br/>" . date('Y-m-d H:i:s') . ": " . $message;
	
}

function displayLog() {
	
		echo "<p>Debug Log<hr>" . $GLOBALS['log'];
	
}

?>