-- Site Settings Table
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','file','json') DEFAULT 'text',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('site_favicon', NULL, 'file'),
('site_name', 'TopTopJobs', 'text'),
('site_description', 'Find your dream job', 'text'),
('home_title', 'TopTopJobs - Find Your Dream Job', 'text'),
('home_description', 'Find your dream job on TopTopJobs. Browse thousands of job listings.', 'text'),
('jobs_title', 'Job Search & Listings - TopTopJobs', 'text'),
('jobs_description', 'Browse thousands of job listings on TopTopJobs. Search by location, job type, and more.', 'text'),
('postjob_title', 'Post a Job - TopTopJobs', 'text'),
('postjob_description', 'Post your job listing for free on TopTopJobs. Reach thousands of qualified candidates.', 'text')
ON DUPLICATE KEY UPDATE `setting_key` = `setting_key`;

