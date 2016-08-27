<?php

	$calls = 0;

	function connect() {

		// Connects to MySQL
		$connection = mysqli_connect('localhost', 'root', 'root', 'roguewarrior');
		if (mysqli_connect_errno()) {
			die("Could not connect: " . mysqli_connect_error());
		}
		
		return $connection;
		
	}
	
	function disconnect($connection) {
	
		// Disconnects from MySQL
		mysqli_close($connection);
	
	}

	function doInsert($dml) {
		
		$GLOBALS['calls']++;
		
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

		$GLOBALS['calls']++;
	
		writeLog("doSearch(): SQL: " . $sql);
		$connection = connect();
		
		$result = mysqli_query($connection, $sql);		

		disconnect($connection);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
		
	}
	
	function createDatabase() {
		
		#HEAD:Creates required schema / tables
		
		$connection = mysqli_connect('localhost', 'root', 'root');
		
		$ddl = "CREATE SCHEMA `roguewarrior`;";
		$status = mysqli_query($connection, $ddl);
		
		$ddl = "USE `roguewarrior`;";
		$status = mysqli_query($connection, $ddl);

		$ddl = "CREATE TABLE `roguewarrior`.`warrior` (`warrior_id` INT NOT NULL AUTO_INCREMENT, `warrior_name` VARCHAR(45) NULL,   `warrior_rank` VARCHAR(45) NULL,   `warrior_hp` INT NULL,  `warrior_acc` INT NULL,  `warrior_str` INT NULL,  `warrior_spd` INT NULL,  `warrior_dex` INT NULL,  `warrior_con` INT NULL,  `warrior_status` VARCHAR(45) NULL,  `warrior_parent` INT NULL,  `warrior_victories` INT NULL,  PRIMARY KEY (`warrior_id`));";
		$status = mysqli_query($connection, $ddl);
  
		$ddl = "CREATE TABLE `roguewarrior`.`results` ( `fight_id` INT NOT NULL AUTO_INCREMENT, `fight_winner` INT NULL, `fight_loser` INT NULL, `fight_rounds` INT NULL, `fight_log` MEDIUMTEXT NULL, PRIMARY KEY (`fight_id`));";
		$status = mysqli_query($connection, $ddl);
		
		$ddl = "CREATE INDEX `idx_warrior_warrior_id`  ON `roguewarrior`.`warrior` (warrior_id) COMMENT '' ALGORITHM DEFAULT LOCK DEFAULT";
		$status = mysqli_query($connection, $ddl);
		
		mysqli_close($connection);
		
	}
	
?>