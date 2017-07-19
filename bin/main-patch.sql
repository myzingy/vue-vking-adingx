/*2017-06-28*/
ALTER TABLE `facebook_ads`.`rules` ADD COLUMN `root_id` VARCHAR(20) NULL AFTER `id`, ADD COLUMN `user_id` VARCHAR(20) NULL AFTER `root_id`;
ALTER TABLE `facebook_ads`.`rules_link` ADD COLUMN `root_id` VARCHAR(20) NULL AFTER `id`, ADD COLUMN `user_id` VARCHAR(20) NULL AFTER `root_id`;

ALTER TABLE `facebook_ads`.`rules_link` ADD INDEX (`target_id`);

ALTER TABLE `facebook_ads`.`user` ADD COLUMN `business_id` VARCHAR(20) NULL AFTER `group_id`;

ALTER TABLE `facebook_ads`.`assets` ADD COLUMN `author` VARCHAR(20) NULL AFTER `uptime`;
ALTER TABLE `facebook_ads`.`assets` ADD COLUMN `skus` VARCHAR(255) NULL AFTER `author`;

ALTER TABLE `facebook_ads`.`accounts_insights` ADD COLUMN `CLICK1D_WebsiteAddstoCartConversionValue` VARCHAR(20) DEFAULT '0' NULL AFTER `CLICK1D_MobileSpend`;
ALTER TABLE `facebook_ads`.`ads_insights` ADD COLUMN `CLICK1D_WebsiteAddstoCartConversionValue` VARCHAR(20) DEFAULT '0' NULL AFTER `CLICK1D_MobileSpend`;
ALTER TABLE `facebook_ads`.`adsets_insights` ADD COLUMN `CLICK1D_WebsiteAddstoCartConversionValue` VARCHAR(20) DEFAULT '0' NULL AFTER `CLICK1D_MobileSpend`;
ALTER TABLE `facebook_ads`.`campaigns_insights` ADD COLUMN `CLICK1D_WebsiteAddstoCartConversionValue` VARCHAR(20) DEFAULT '0' NULL AFTER `CLICK1D_MobileSpend`;
