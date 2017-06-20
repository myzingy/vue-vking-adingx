/*2017-06-13*/
ALTER TABLE `facebook_ads`.`ads` DROP COLUMN `rule_runtime`;
ALTER TABLE `facebook_ads`.`adsets` DROP COLUMN `rule_runtime`;

/*2017-06-20*/
ALTER TABLE `facebook_ads`.`x_cron` ADD COLUMN `ac_id` VARCHAR(50) NULL AFTER `path`;
ALTER TABLE `facebook_ads`.`x_cron` ADD INDEX (`ac_id`);