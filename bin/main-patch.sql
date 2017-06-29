/*2017-06-28*/
ALTER TABLE `facebook_ads`.`rules` ADD COLUMN `root_id` VARCHAR(20) NULL AFTER `id`, ADD COLUMN `user_id` VARCHAR(20) NULL AFTER `root_id`;
ALTER TABLE `facebook_ads`.`rules_link` ADD COLUMN `root_id` VARCHAR(20) NULL AFTER `id`, ADD COLUMN `user_id` VARCHAR(20) NULL AFTER `root_id`;

ALTER TABLE `facebook_ads`.`rules_link` ADD INDEX (`target_id`); 
