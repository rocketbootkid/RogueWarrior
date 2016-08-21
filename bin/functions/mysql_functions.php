<?php

	function connect() {

		// Connects to MySQL
		$connection = mysqli_connect('localhost', 'root', 'root', 'roguewarrior');
		if (mysqli_connect_errno()) {
			die("Could not connect: " . mysqli_connect_error() . "<script>window.reload();</script>");
		}
		
		return $connection;
		
	}
	
	function disconnect($connection) {
	
		// Disconnects from MySQL
		mysqli_close($connection);
	
	}

	function doInsert($dml) {
		
		writeLog("doInsert(): DML: " . $dml);
		$connection = connect();
		
		$status = mysqli_query($connection, $dml);
		
		if ($status == TRUE) {
			writeLog("doInsert(): Insert Successful!");
		} else {
			writeLog("doInsert(): ERROR: Insert Failed!");
		}
	
		disconnect($connection);
	
		return $status;
		
	}
	
	function doSearch($sql) {
		
		writeLog("doSearch(): SQL: " . $sql);
		$connection = connect();
		
		$result = mysqli_query($connection, $sql);		

		disconnect($connection);
		
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
		
	}


?>