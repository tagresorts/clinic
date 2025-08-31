-- Create the default_dashboard_layouts table
CREATE TABLE `default_dashboard_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `widget_key` varchar(255) NOT NULL,
  `x_pos` int(11) NOT NULL,
  `y_pos` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `wrapper_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `default_dashboard_layouts_widget_key_unique` (`widget_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;