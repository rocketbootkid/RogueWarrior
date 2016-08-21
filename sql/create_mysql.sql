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
  PRIMARY KEY (`warrior_id`));