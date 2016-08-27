CREATE SCHEMA `roguewarrior`;
USE `roguewarrior`;

CREATE TABLE `roguewarrior`.`warrior` (
  `warrior_id` INT NOT NULL AUTO_INCREMENT,
  `warrior_name` VARCHAR(45) NULL,
  `warrior_rank` VARCHAR(45) NULL,
  `warrior_hp` INT NULL,
  `warrior_acc` INT NULL,
  `warrior_str` INT NULL,
  `warrior_spd` INT NULL,
  `warrior_dex` INT NULL,
  `warrior_con` INT NULL,
  `warrior_status` VARCHAR(45) NULL,
  `warrior_parent` INT NULL,
  `warrior_victories` INT NULL,
  PRIMARY KEY (`warrior_id`));
  
  CREATE TABLE `roguewarrior`.`results` (
  `fight_id` INT NOT NULL AUTO_INCREMENT,
  `fight_winner` INT NULL,
  `fight_loser` INT NULL,
  `fight_rounds` INT NULL,
  `fight_log` MEDIUMTEXT NULL,
  PRIMARY KEY (`fight_id`));

  CREATE INDEX `idx_warrior_warrior_id`  ON `roguewarrior`.`warrior` (warrior_id) COMMENT '' ALGORITHM DEFAULT LOCK DEFAULT
