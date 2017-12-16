
CREATE TABLE `pre_werewolfroom` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`salt` int(11) unsigned NOT NULL,
	`expiry` int(11) unsigned NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `pre_werewolfrole` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`room_id` mediumint(8) unsigned NOT NULL,
	`role_id` tinyint(3) unsigned NOT NULL,
	`used` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY (`room_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
