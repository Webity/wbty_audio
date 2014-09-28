
		
	CREATE TABLE IF NOT EXISTS `#__wbty_audio_manager_audio_files` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL DEFAULT '1',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	`created_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11)  NOT NULL ,
	`modified_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`file_name` VARCHAR(500) NOT NULL,
		`title` VARCHAR(255) NOT NULL,
		`description` LONGTEXT NOT NULL,
		`include_in_list` INT(4) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;
				
		
	CREATE TABLE IF NOT EXISTS `#__wbty_audio_manager_audio_file_articles` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL DEFAULT '1',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	`created_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11)  NOT NULL ,
	`modified_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`article` VARCHAR(255) NOT NULL,
		`audio_file_id` INT(11)  NOT NULL ,
	PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;
				