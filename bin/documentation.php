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
			
			echo "<tr bgcolor=#999><td colspan=2><strong>" . $file . "</strong></tr>"; # Print filename header
			
			$path = "functions/" . $file;
			$file_contents = file($path); # read contents of php functions file
			#echo count($file_contents);
			foreach ($file_contents as $line) {
				
				if (substr($line, 0, 8) == 'function') { # check if line is a function start
					
					$function = substr($line, 9, strpos($line, "{")-10);
					$function_name = substr($line, 9, strpos($line, "(")-9);
					
					$parameters = substr($function, strpos($function, "(")+1);
					$parameters = str_replace(")", "", $parameters);
					$parameters = str_replace("$", "", $parameters);
					$arrParameters = explode(", ", $parameters);	
					
					echo "<tr bgcolor=#bbb><td width=20%><strong>" . $function_name . "</strong></tr>";
					echo "<tr><td>Parameter(s): " . $parameters . "</tr>";
					
				}
				
				if (substr_count($line, "#HEAD:") > 0) { # Comment line belonging to the preceding function
					echo "<tr><td colspan=2>" . substr($line, 7) . "</tr>";
					
				}
				
			}
			
		}
		
	}
	
	echo "</table>";

?>

</body>

</html>