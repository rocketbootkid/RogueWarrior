<html>

<head>
<title>Rogue Warrior | Function List</title>
</head>

<body style="font-family: 'Courier';">

<?php

	echo "<table cellpadding=3 cellspacing=1 border=1 width=100%>\n";
	
	$arrFiles = scandir('functions');
	
	foreach ($arrFiles as $file) {
		
		if (substr($file, -3) == 'php') { # ignore anything that's not a PHP file
			
			echo "<tr bgcolor=#ddd><td><strong>" . $file . "</strong></tr>"; # Print filename header
			
			$path = "functions/" . $file;
			$file_contents = file($path);
			
			foreach ($file_contents as $line) {
				
				if (substr($line, 0, 8) == 'function') {
					
					echo "<tr><td>" . substr($line, 9, strpos($line, "{")-10) . "</tr>";
					
				}
				
				
				
				
			}
			
			
			
			
			
			
		}
		
	}

?>

</body>

</html>