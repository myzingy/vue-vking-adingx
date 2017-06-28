/*
SQLyog Ultimate v11.27 (32 bit)
MySQL - 5.6.20 : Database - facebook_ads
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`facebook_ads` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `facebook_ads`;

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `account_id` varchar(50) DEFAULT NULL,
  `account_status` varchar(50) DEFAULT NULL,
  `age` varchar(50) DEFAULT NULL,
  `agency_client_declaration` varchar(50) DEFAULT NULL,
  `amount_spent` varchar(50) DEFAULT NULL,
  `balance` varchar(50) DEFAULT NULL,
  `business` varchar(50) DEFAULT NULL,
  `business_city` varchar(50) DEFAULT NULL,
  `business_country_code` varchar(50) DEFAULT NULL,
  `business_name` varchar(50) DEFAULT NULL,
  `business_state` varchar(50) DEFAULT NULL,
  `business_street` varchar(50) DEFAULT NULL,
  `business_street2` varchar(50) DEFAULT NULL,
  `business_zip` varchar(50) DEFAULT NULL,
  `capabilities` varchar(50) DEFAULT NULL,
  `created_time` varchar(50) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `disable_reason` varchar(50) DEFAULT NULL,
  `end_advertiser` varchar(50) DEFAULT NULL,
  `end_advertiser_name` varchar(50) DEFAULT NULL,
  `failed_delivery_checks` varchar(50) DEFAULT NULL,
  `funding_source` varchar(50) DEFAULT NULL,
  `funding_source_details` varchar(50) DEFAULT NULL,
  `has_migrated_permissions` varchar(50) DEFAULT NULL,
  `id` varchar(50) NOT NULL DEFAULT '',
  `io_number` varchar(50) DEFAULT NULL,
  `is_notifications_enabled` varchar(50) DEFAULT NULL,
  `is_personal` varchar(50) DEFAULT NULL,
  `is_prepay_account` varchar(50) DEFAULT NULL,
  `is_tax_id_required` varchar(50) DEFAULT NULL,
  `line_numbers` varchar(50) DEFAULT NULL,
  `media_agency` varchar(50) DEFAULT NULL,
  `min_campaign_group_spend_cap` varchar(50) DEFAULT NULL,
  `min_daily_budget` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `offsite_pixels_tos_accepted` varchar(50) DEFAULT NULL,
  `owner` varchar(50) DEFAULT NULL,
  `partner` varchar(50) DEFAULT NULL,
  `rf_spec` varchar(50) DEFAULT NULL,
  `salesforce_invoice_group_id` varchar(50) DEFAULT NULL,
  `spend_cap` varchar(50) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `tax_id_status` varchar(50) DEFAULT NULL,
  `tax_id_type` varchar(50) DEFAULT NULL,
  `timezone_id` varchar(50) DEFAULT NULL,
  `timezone_name` varchar(50) DEFAULT NULL,
  `timezone_offset_hours_utc` varchar(50) DEFAULT NULL,
  `tos_accepted` varchar(50) DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `accounts_insights` */

DROP TABLE IF EXISTS `accounts_insights`;

CREATE TABLE `accounts_insights` (
  `id` varchar(32) NOT NULL COMMENT 'ad_id + date',
  `type` tinyint(1) DEFAULT '0',
  `account_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `action_values` varchar(50) DEFAULT NULL,
  `actions` varchar(50) DEFAULT NULL,
  `app_store_clicks` varchar(50) DEFAULT NULL,
  `buying_type` varchar(50) DEFAULT NULL,
  `call_to_action_clicks` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `campaign_name` varchar(50) DEFAULT NULL,
  `clicks` varchar(50) DEFAULT NULL,
  `cost_per_action_type` varchar(50) DEFAULT NULL,
  `cost_per_estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `cost_per_inline_link_click` varchar(50) DEFAULT NULL,
  `cost_per_inline_post_engagement` varchar(50) DEFAULT NULL,
  `cost_per_total_action` varchar(50) DEFAULT NULL,
  `cost_per_unique_action_type` varchar(50) DEFAULT NULL,
  `cost_per_unique_click` varchar(50) DEFAULT NULL,
  `cost_per_unique_inline_link_click` varchar(50) DEFAULT NULL,
  `cpc` varchar(50) DEFAULT NULL,
  `cpm` varchar(50) DEFAULT NULL,
  `cpp` varchar(50) DEFAULT NULL,
  `ctr` varchar(50) DEFAULT NULL,
  `date_start` varchar(50) DEFAULT NULL,
  `date_stop` varchar(50) DEFAULT NULL,
  `deeplink_clicks` varchar(50) DEFAULT NULL,
  `estimated_ad_recall_rate` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `impressions` varchar(50) DEFAULT NULL,
  `inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `inline_link_clicks` varchar(50) DEFAULT NULL,
  `inline_post_engagement` varchar(50) DEFAULT NULL,
  `objective` varchar(50) DEFAULT NULL,
  `reach` varchar(50) DEFAULT NULL,
  `social_clicks` varchar(50) DEFAULT NULL,
  `social_impressions` varchar(50) DEFAULT NULL,
  `social_reach` varchar(50) DEFAULT NULL,
  `social_spend` varchar(50) DEFAULT NULL,
  `spend` varchar(50) DEFAULT NULL,
  `total_action_value` varchar(50) DEFAULT NULL,
  `total_actions` varchar(50) DEFAULT NULL,
  `total_unique_actions` varchar(50) DEFAULT NULL,
  `unique_actions` varchar(50) DEFAULT NULL,
  `unique_clicks` varchar(50) DEFAULT NULL,
  `unique_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_clicks` varchar(50) DEFAULT NULL,
  `unique_link_clicks_ctr` varchar(50) DEFAULT NULL,
  `unique_social_clicks` varchar(50) DEFAULT NULL,
  `website_clicks` varchar(50) DEFAULT NULL,
  `website_ctr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `accounts_insights_action_types` */

DROP TABLE IF EXISTS `accounts_insights_action_types`;

CREATE TABLE `accounts_insights_action_types` (
  `accounts_insights_id` char(32) NOT NULL,
  `insight_key` varchar(50) NOT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `1d_click` varchar(50) DEFAULT NULL,
  `1d_view` varchar(50) DEFAULT NULL,
  KEY `account_insights_id` (`accounts_insights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `ads` */

DROP TABLE IF EXISTS `ads`;

CREATE TABLE `ads` (
  `account_id` varchar(50) DEFAULT NULL,
  `ad_review_feedback` varchar(50) DEFAULT NULL,
  `adlabels` varchar(50) DEFAULT NULL,
  `adset` varchar(50) DEFAULT NULL,
  `adset_id` varchar(50) DEFAULT NULL,
  `bid_amount` varchar(50) DEFAULT NULL,
  `bid_info` varchar(50) DEFAULT NULL,
  `bid_type` varchar(50) DEFAULT NULL,
  `campaign` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `configured_status` varchar(50) DEFAULT NULL,
  `conversion_specs` varchar(50) DEFAULT NULL,
  `created_time` varchar(50) DEFAULT NULL,
  `creative` varchar(50) DEFAULT NULL,
  `effective_status` varchar(50) DEFAULT NULL,
  `id` varchar(50) NOT NULL,
  `last_updated_by_app_id` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `recommendations` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tracking_specs` varchar(50) DEFAULT NULL,
  `updated_time` varchar(50) DEFAULT NULL,
  `adset_spec` varchar(50) DEFAULT NULL,
  `date_format` varchar(50) DEFAULT NULL,
  `display_sequence` varchar(50) DEFAULT NULL,
  `execution_options` varchar(50) DEFAULT NULL,
  `redownload` varchar(50) DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `ads_insights` */

DROP TABLE IF EXISTS `ads_insights`;

CREATE TABLE `ads_insights` (
  `id` varchar(32) NOT NULL COMMENT 'ad_id + date',
  `type` tinyint(1) DEFAULT '99',
  `account_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `action_values` varchar(50) DEFAULT NULL,
  `actions` varchar(50) DEFAULT NULL,
  `ad_id` varchar(50) DEFAULT NULL,
  `ad_name` varchar(50) DEFAULT NULL,
  `adset_id` varchar(50) DEFAULT NULL,
  `adset_name` varchar(50) DEFAULT NULL,
  `app_store_clicks` varchar(50) DEFAULT NULL,
  `buying_type` varchar(50) DEFAULT NULL,
  `call_to_action_clicks` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `campaign_name` varchar(50) DEFAULT NULL,
  `canvas_avg_view_percent` varchar(50) DEFAULT NULL,
  `canvas_avg_view_time` varchar(50) DEFAULT NULL,
  `canvas_component_avg_pct_view` varchar(50) DEFAULT NULL,
  `clicks` varchar(50) DEFAULT NULL,
  `cost_per_10_sec_video_view` varchar(50) DEFAULT NULL,
  `cost_per_action_type` varchar(50) DEFAULT NULL,
  `cost_per_estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `cost_per_inline_link_click` varchar(50) DEFAULT NULL,
  `cost_per_inline_post_engagement` varchar(50) DEFAULT NULL,
  `cost_per_total_action` varchar(50) DEFAULT NULL,
  `cost_per_unique_action_type` varchar(50) DEFAULT NULL,
  `cost_per_unique_click` varchar(50) DEFAULT NULL,
  `cost_per_unique_inline_link_click` varchar(50) DEFAULT NULL,
  `cpc` varchar(50) DEFAULT NULL,
  `cpm` varchar(50) DEFAULT NULL,
  `cpp` varchar(50) DEFAULT NULL,
  `ctr` varchar(50) DEFAULT NULL,
  `date_start` varchar(50) DEFAULT NULL,
  `date_stop` varchar(50) DEFAULT NULL,
  `deeplink_clicks` varchar(50) DEFAULT NULL,
  `estimated_ad_recall_rate` varchar(50) DEFAULT NULL,
  `estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `impressions` varchar(50) DEFAULT NULL,
  `inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `inline_link_clicks` varchar(50) DEFAULT NULL,
  `inline_post_engagement` varchar(50) DEFAULT NULL,
  `objective` varchar(50) DEFAULT NULL,
  `place_page_name` varchar(50) DEFAULT NULL,
  `reach` varchar(50) DEFAULT NULL,
  `relevance_score` varchar(50) DEFAULT NULL,
  `social_clicks` varchar(50) DEFAULT NULL,
  `social_impressions` varchar(50) DEFAULT NULL,
  `social_reach` varchar(50) DEFAULT NULL,
  `social_spend` varchar(50) DEFAULT NULL,
  `spend` varchar(50) DEFAULT NULL,
  `total_action_value` varchar(50) DEFAULT NULL,
  `total_actions` varchar(50) DEFAULT NULL,
  `total_unique_actions` varchar(50) DEFAULT NULL,
  `unique_actions` varchar(50) DEFAULT NULL,
  `unique_clicks` varchar(50) DEFAULT NULL,
  `unique_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_clicks` varchar(50) DEFAULT NULL,
  `unique_link_clicks_ctr` varchar(50) DEFAULT NULL,
  `unique_social_clicks` varchar(50) DEFAULT NULL,
  `video_10_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_15_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_30_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_percent_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_time_watched_actions` varchar(50) DEFAULT NULL,
  `video_p100_watched_actions` varchar(50) DEFAULT NULL,
  `video_p25_watched_actions` varchar(50) DEFAULT NULL,
  `video_p50_watched_actions` varchar(50) DEFAULT NULL,
  `video_p75_watched_actions` varchar(50) DEFAULT NULL,
  `video_p95_watched_actions` varchar(50) DEFAULT NULL,
  `website_clicks` varchar(50) DEFAULT NULL,
  `website_ctr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ad_id` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `ads_insights_action_types` */

DROP TABLE IF EXISTS `ads_insights_action_types`;

CREATE TABLE `ads_insights_action_types` (
  `ads_insights_id` char(32) NOT NULL,
  `insight_key` varchar(50) NOT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `1d_click` varchar(50) DEFAULT NULL,
  `1d_view` varchar(50) DEFAULT NULL,
  KEY `ads_insights_id` (`ads_insights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `ads_rules_run` */

DROP TABLE IF EXISTS `ads_rules_run`;

CREATE TABLE `ads_rules_run` (
  `id` varchar(50) NOT NULL,
  `rule_runtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `adsets` */

DROP TABLE IF EXISTS `adsets`;

CREATE TABLE `adsets` (
  `account_id` varchar(50) DEFAULT NULL,
  `billing_event` varchar(50) DEFAULT NULL,
  `budget_remaining` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `configured_status` varchar(50) DEFAULT NULL,
  `created_time` varchar(50) DEFAULT NULL,
  `daily_budget` varchar(50) DEFAULT NULL,
  `effective_status` varchar(50) DEFAULT NULL,
  `frequency_cap_reset_period` varchar(50) DEFAULT NULL,
  `id` varchar(50) NOT NULL,
  `is_autobid` tinyint(1) DEFAULT NULL,
  `is_average_price_pacing` tinyint(1) DEFAULT NULL,
  `lifetime_budget` varchar(50) DEFAULT NULL,
  `lifetime_imps` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `optimization_goal` varchar(50) DEFAULT NULL,
  `recurring_budget_semantics` tinyint(1) DEFAULT NULL,
  `rtb_flag` tinyint(1) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `adsets_insights` */

DROP TABLE IF EXISTS `adsets_insights`;

CREATE TABLE `adsets_insights` (
  `id` varchar(32) NOT NULL COMMENT 'adset_id + date',
  `type` tinyint(1) DEFAULT '99',
  `account_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `action_values` varchar(50) DEFAULT NULL,
  `actions` varchar(50) DEFAULT NULL,
  `ad_id` varchar(50) DEFAULT NULL,
  `ad_name` varchar(50) DEFAULT NULL,
  `adset_id` varchar(50) DEFAULT NULL,
  `adset_name` varchar(50) DEFAULT NULL,
  `app_store_clicks` varchar(50) DEFAULT NULL,
  `buying_type` varchar(50) DEFAULT NULL,
  `call_to_action_clicks` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `campaign_name` varchar(50) DEFAULT NULL,
  `canvas_avg_view_percent` varchar(50) DEFAULT NULL,
  `canvas_avg_view_time` varchar(50) DEFAULT NULL,
  `canvas_component_avg_pct_view` varchar(50) DEFAULT NULL,
  `clicks` varchar(50) DEFAULT NULL,
  `cost_per_10_sec_video_view` varchar(50) DEFAULT NULL,
  `cost_per_action_type` varchar(50) DEFAULT NULL,
  `cost_per_estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `cost_per_inline_link_click` varchar(50) DEFAULT NULL,
  `cost_per_inline_post_engagement` varchar(50) DEFAULT NULL,
  `cost_per_total_action` varchar(50) DEFAULT NULL,
  `cost_per_unique_action_type` varchar(50) DEFAULT NULL,
  `cost_per_unique_click` varchar(50) DEFAULT NULL,
  `cost_per_unique_inline_link_click` varchar(50) DEFAULT NULL,
  `cpc` varchar(50) DEFAULT NULL,
  `cpm` varchar(50) DEFAULT NULL,
  `cpp` varchar(50) DEFAULT NULL,
  `ctr` varchar(50) DEFAULT NULL,
  `date_start` varchar(50) DEFAULT NULL,
  `date_stop` varchar(50) DEFAULT NULL,
  `deeplink_clicks` varchar(50) DEFAULT NULL,
  `estimated_ad_recall_rate` varchar(50) DEFAULT NULL,
  `estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `impressions` varchar(50) DEFAULT NULL,
  `inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `inline_link_clicks` varchar(50) DEFAULT NULL,
  `inline_post_engagement` varchar(50) DEFAULT NULL,
  `objective` varchar(50) DEFAULT NULL,
  `place_page_name` varchar(50) DEFAULT NULL,
  `reach` varchar(50) DEFAULT NULL,
  `relevance_score` varchar(50) DEFAULT NULL,
  `social_clicks` varchar(50) DEFAULT NULL,
  `social_impressions` varchar(50) DEFAULT NULL,
  `social_reach` varchar(50) DEFAULT NULL,
  `social_spend` varchar(50) DEFAULT NULL,
  `spend` varchar(50) DEFAULT NULL,
  `total_action_value` varchar(50) DEFAULT NULL,
  `total_actions` varchar(50) DEFAULT NULL,
  `total_unique_actions` varchar(50) DEFAULT NULL,
  `unique_actions` varchar(50) DEFAULT NULL,
  `unique_clicks` varchar(50) DEFAULT NULL,
  `unique_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_clicks` varchar(50) DEFAULT NULL,
  `unique_link_clicks_ctr` varchar(50) DEFAULT NULL,
  `unique_social_clicks` varchar(50) DEFAULT NULL,
  `video_10_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_15_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_30_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_percent_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_time_watched_actions` varchar(50) DEFAULT NULL,
  `video_p100_watched_actions` varchar(50) DEFAULT NULL,
  `video_p25_watched_actions` varchar(50) DEFAULT NULL,
  `video_p50_watched_actions` varchar(50) DEFAULT NULL,
  `video_p75_watched_actions` varchar(50) DEFAULT NULL,
  `video_p95_watched_actions` varchar(50) DEFAULT NULL,
  `website_clicks` varchar(50) DEFAULT NULL,
  `website_ctr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adset_id` (`adset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `adsets_insights_action_types` */

DROP TABLE IF EXISTS `adsets_insights_action_types`;

CREATE TABLE `adsets_insights_action_types` (
  `adsets_insights_id` char(32) NOT NULL,
  `insight_key` varchar(50) NOT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `1d_click` varchar(50) DEFAULT NULL,
  `1d_view` varchar(50) DEFAULT NULL,
  KEY `adsets_id` (`adsets_insights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `adsets_rules_run` */

DROP TABLE IF EXISTS `adsets_rules_run`;

CREATE TABLE `adsets_rules_run` (
  `id` varchar(50) NOT NULL,
  `rule_runtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `campaigns` */

DROP TABLE IF EXISTS `campaigns`;

CREATE TABLE `campaigns` (
  `account_id` varchar(50) DEFAULT NULL,
  `adlabels` varchar(50) DEFAULT NULL,
  `brand_lift_studies` varchar(50) DEFAULT NULL,
  `budget_rebalance_flag` varchar(50) DEFAULT NULL,
  `buying_type` varchar(50) DEFAULT NULL,
  `can_create_brand_lift_study` varchar(50) DEFAULT NULL,
  `can_use_spend_cap` varchar(50) DEFAULT NULL,
  `configured_status` varchar(50) DEFAULT NULL,
  `created_time` varchar(50) DEFAULT NULL,
  `effective_status` varchar(50) DEFAULT NULL,
  `id` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `objective` varchar(50) DEFAULT NULL,
  `recommendations` varchar(50) DEFAULT NULL,
  `spend_cap` varchar(50) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `stop_time` varchar(50) DEFAULT NULL,
  `updated_time` varchar(50) DEFAULT NULL,
  `execution_options` varchar(50) DEFAULT NULL,
  `promoted_object` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `campaigns_insights` */

DROP TABLE IF EXISTS `campaigns_insights`;

CREATE TABLE `campaigns_insights` (
  `id` varchar(32) NOT NULL COMMENT 'campaign_id + date',
  `type` tinyint(1) DEFAULT '99',
  `account_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `action_values` varchar(50) DEFAULT NULL,
  `actions` varchar(50) DEFAULT NULL,
  `ad_id` varchar(50) DEFAULT NULL,
  `ad_name` varchar(50) DEFAULT NULL,
  `adset_id` varchar(50) DEFAULT NULL,
  `adset_name` varchar(50) DEFAULT NULL,
  `app_store_clicks` varchar(50) DEFAULT NULL,
  `buying_type` varchar(50) DEFAULT NULL,
  `call_to_action_clicks` varchar(50) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `campaign_name` varchar(50) DEFAULT NULL,
  `canvas_avg_view_percent` varchar(50) DEFAULT NULL,
  `canvas_avg_view_time` varchar(50) DEFAULT NULL,
  `canvas_component_avg_pct_view` varchar(50) DEFAULT NULL,
  `clicks` varchar(50) DEFAULT NULL,
  `cost_per_10_sec_video_view` varchar(50) DEFAULT NULL,
  `cost_per_action_type` varchar(50) DEFAULT NULL,
  `cost_per_estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `cost_per_inline_link_click` varchar(50) DEFAULT NULL,
  `cost_per_inline_post_engagement` varchar(50) DEFAULT NULL,
  `cost_per_total_action` varchar(50) DEFAULT NULL,
  `cost_per_unique_action_type` varchar(50) DEFAULT NULL,
  `cost_per_unique_click` varchar(50) DEFAULT NULL,
  `cost_per_unique_inline_link_click` varchar(50) DEFAULT NULL,
  `cpc` varchar(50) DEFAULT NULL,
  `cpm` varchar(50) DEFAULT NULL,
  `cpp` varchar(50) DEFAULT NULL,
  `ctr` varchar(50) DEFAULT NULL,
  `date_start` varchar(50) DEFAULT NULL,
  `date_stop` varchar(50) DEFAULT NULL,
  `deeplink_clicks` varchar(50) DEFAULT NULL,
  `estimated_ad_recall_rate` varchar(50) DEFAULT NULL,
  `estimated_ad_recallers` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `impressions` varchar(50) DEFAULT NULL,
  `inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `inline_link_clicks` varchar(50) DEFAULT NULL,
  `inline_post_engagement` varchar(50) DEFAULT NULL,
  `objective` varchar(50) DEFAULT NULL,
  `place_page_name` varchar(50) DEFAULT NULL,
  `reach` varchar(50) DEFAULT NULL,
  `relevance_score` varchar(50) DEFAULT NULL,
  `social_clicks` varchar(50) DEFAULT NULL,
  `social_impressions` varchar(50) DEFAULT NULL,
  `social_reach` varchar(50) DEFAULT NULL,
  `social_spend` varchar(50) DEFAULT NULL,
  `spend` varchar(50) DEFAULT NULL,
  `total_action_value` varchar(50) DEFAULT NULL,
  `total_actions` varchar(50) DEFAULT NULL,
  `total_unique_actions` varchar(50) DEFAULT NULL,
  `unique_actions` varchar(50) DEFAULT NULL,
  `unique_clicks` varchar(50) DEFAULT NULL,
  `unique_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_click_ctr` varchar(50) DEFAULT NULL,
  `unique_inline_link_clicks` varchar(50) DEFAULT NULL,
  `unique_link_clicks_ctr` varchar(50) DEFAULT NULL,
  `unique_social_clicks` varchar(50) DEFAULT NULL,
  `video_10_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_15_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_30_sec_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_percent_watched_actions` varchar(50) DEFAULT NULL,
  `video_avg_time_watched_actions` varchar(50) DEFAULT NULL,
  `video_p100_watched_actions` varchar(50) DEFAULT NULL,
  `video_p25_watched_actions` varchar(50) DEFAULT NULL,
  `video_p50_watched_actions` varchar(50) DEFAULT NULL,
  `video_p75_watched_actions` varchar(50) DEFAULT NULL,
  `video_p95_watched_actions` varchar(50) DEFAULT NULL,
  `website_clicks` varchar(50) DEFAULT NULL,
  `website_ctr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `campaigns_insights_action_types` */

DROP TABLE IF EXISTS `campaigns_insights_action_types`;

CREATE TABLE `campaigns_insights_action_types` (
  `campaigns_insights_id` char(32) NOT NULL,
  `insight_key` varchar(50) NOT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `1d_click` varchar(50) DEFAULT NULL,
  `1d_view` varchar(50) DEFAULT NULL,
  KEY `campaign_id` (`campaigns_insights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `group` */

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `group_rules` */

DROP TABLE IF EXISTS `group_rules`;

CREATE TABLE `group_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `rules` */

DROP TABLE IF EXISTS `rules`;

CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` text,
  `xml` longtext,
  `stime` int(10) DEFAULT NULL,
  `etime` int(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `rules_exec_log` */

DROP TABLE IF EXISTS `rules_exec_log`;

CREATE TABLE `rules_exec_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(10) DEFAULT NULL,
  `account_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `rule_id` int(11) DEFAULT NULL,
  `rule_name` varchar(255) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `target_id` varchar(50) DEFAULT NULL,
  `target_data` text,
  `rule_exec` varchar(2000) DEFAULT NULL,
  `spend_cut` float(6,2) DEFAULT '0.00',
  `spend_put` float(6,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `rules_link` */

DROP TABLE IF EXISTS `rules_link`;

CREATE TABLE `rules_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `target_id` varchar(50) DEFAULT NULL,
  `exec_hour_minute` varchar(5) DEFAULT NULL,
  `runtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `long_token` varchar(255) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  `group_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `token` (`token`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `user_accounts` */

DROP TABLE IF EXISTS `user_accounts`;

CREATE TABLE `user_accounts` (
  `root_id` varchar(20) DEFAULT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `account_id` varchar(50) NOT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `user_accounts_links` */

DROP TABLE IF EXISTS `user_accounts_links`;

CREATE TABLE `user_accounts_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `user_children` */

DROP TABLE IF EXISTS `user_children`;

CREATE TABLE `user_children` (
  `user_id` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `group_id` tinyint(1) DEFAULT '-1',
  PRIMARY KEY (`email`),
  KEY `user_id` (`user_id`),
  KEY `child_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `x_cron` */

DROP TABLE IF EXISTS `x_cron`;

CREATE TABLE `x_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) DEFAULT NULL,
  `ac_id` varchar(50) DEFAULT NULL,
  `param` varchar(255) DEFAULT NULL,
  `method` varchar(10) DEFAULT 'GET',
  `header` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `retry` tinyint(1) DEFAULT '0',
  `addtime` int(10) DEFAULT NULL,
  `runtime` int(10) DEFAULT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `crontime` int(10) DEFAULT '0',
  `hash` char(32) DEFAULT NULL,
  `priority` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `ac_id` (`ac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
