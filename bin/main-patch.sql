/*2017-06-13*/
ALTER TABLE `facebook_ads`.`ads` DROP COLUMN `rule_runtime`;
ALTER TABLE `facebook_ads`.`adsets` DROP COLUMN `rule_runtime`;

/*2017-06-20*/
ALTER TABLE `facebook_ads`.`x_cron` ADD COLUMN `ac_id` VARCHAR(50) NULL AFTER `path`;
ALTER TABLE `facebook_ads`.`x_cron` ADD INDEX (`ac_id`);

/*2017-06-21*/
ALTER TABLE `facebook_ads`.`rules_exec_log` ADD COLUMN `account_id` VARCHAR(50) NULL AFTER `time`, ADD COLUMN `account_name` VARCHAR(50) NULL AFTER `account_id`;
ALTER TABLE `facebook_ads`.`rules_exec_log` ADD COLUMN `spend_cut` FLOAT(6,2) DEFAULT 0.00 NULL AFTER `rule_exec`, ADD COLUMN `spend_put` FLOAT(6,2) DEFAULT 0.00 NULL AFTER `spend_cut`;