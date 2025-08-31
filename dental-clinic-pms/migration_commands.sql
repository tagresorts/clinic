-- Migration commands to run manually in your database

-- 1. Add wrapper_id column to user_dashboard_preferences table
ALTER TABLE `user_dashboard_preferences` ADD COLUMN `wrapper_id` INT DEFAULT 1 AFTER `height`;

-- 2. Create user_dashboard_wrappers table
CREATE TABLE `user_dashboard_wrappers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `wrapper_id` INT NOT NULL,
    `title` VARCHAR(255) NULL,
    `order` INT DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_dashboard_wrappers_user_id_wrapper_id_unique` (`user_id`, `wrapper_id`),
    CONSTRAINT `user_dashboard_wrappers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Insert default wrapper for existing users (optional)
INSERT INTO `user_dashboard_wrappers` (`user_id`, `wrapper_id`, `title`, `order`, `created_at`, `updated_at`)
SELECT DISTINCT `user_id`, 1, 'Dashboard 1', 1, NOW(), NOW()
FROM `user_dashboard_preferences`
WHERE `user_id` NOT IN (SELECT `user_id` FROM `user_dashboard_wrappers` WHERE `wrapper_id` = 1);