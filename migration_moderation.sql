ALTER TABLE `users`
  ADD COLUMN `is_banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`,
  ADD COLUMN `eula_accepted_at` DATETIME NULL DEFAULT NULL AFTER `is_banned`;

CREATE TABLE `reports` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reporter_id` INT(11) NOT NULL,
  `reported_user_id` INT(11) NOT NULL,
  `feed_id` INT(11) NULL DEFAULT NULL,
  `reason` VARCHAR(50) NOT NULL,
  `description` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` DATETIME NULL DEFAULT NULL,
  `resolved_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reported_user_id` (`reported_user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
