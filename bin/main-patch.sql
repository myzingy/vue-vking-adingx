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


ALTER TABLE `facebook_ads`.`accounts_insights` ADD COLUMN `country_spend` VARCHAR(5000) DEFAULT '{}' NULL AFTER `CLICK1D_WebsiteAddstoCartConversionValue`;
ALTER TABLE `facebook_ads`.`accounts_insights` CHANGE `country_spend` `country_cost` VARCHAR(500) CHARSET utf8mb4
COLLATE utf8mb4_general_ci DEFAULT '{}' NULL, ADD COLUMN `country_purchase` VARCHAR(500) DEFAULT '{}' NULL AFTER
`country_cost`, ADD COLUMN `country_income` VARCHAR(500) DEFAULT '{}' NULL AFTER `country_purchase`, ADD COLUMN `country_add_to_cart` VARCHAR(500) DEFAULT '{}' NULL AFTER `country_income`, ADD COLUMN `country_cpm` VARCHAR(500) DEFAULT '{}' NULL AFTER `country_addtocart`, ADD COLUMN `country_ctr` VARCHAR(500) DEFAULT '{}' NULL AFTER `country_cpm`, ADD COLUMN `country_link_click` VARCHAR(500) DEFAULT '{}' NULL AFTER `country_ctr`;



ALTER TABLE `facebook_ads`.`feeds_marks` ADD COLUMN `bg_img_path` VARCHAR(100) NULL AFTER `background`, ADD COLUMN `bg_img_hash` CHAR(16) NULL AFTER `bg_img_path`;
ALTER TABLE `facebook_ads`.`feeds_items` CHANGE `g_title` `g_title` VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `facebook_ads`.`feeds_items` CHANGE `g_custom_label_0` `g_custom_label_0` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `g_custom_label_1` `g_custom_label_1` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `g_custom_label_2` `g_custom_label_2` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `g_custom_label_3` `g_custom_label_3` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `g_custom_label_4` `g_custom_label_4` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL;


