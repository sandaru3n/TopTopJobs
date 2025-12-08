-- ============================================
-- TopTopJobs Database Schema
-- Database: toptopjobs
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `toptopjobs` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE `toptopjobs`;

-- ============================================
-- Companies Table
-- ============================================
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `founded_year` int(4) DEFAULT NULL,
  `headquarters` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_name` (`name`),
  KEY `idx_industry` (`industry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Jobs Table
-- ============================================
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `job_type` enum('full-time','part-time','contract','internship','remote','freelance') NOT NULL DEFAULT 'full-time',
  `experience_level` enum('fresher','junior','mid','senior','lead') NOT NULL DEFAULT 'junior',
  `min_experience` int(2) DEFAULT 0,
  `max_experience` int(2) DEFAULT NULL,
  `salary_min` decimal(12,2) DEFAULT NULL,
  `salary_max` decimal(12,2) DEFAULT NULL,
  `salary_currency` varchar(3) DEFAULT 'INR',
  `salary_period` enum('monthly','yearly','hourly','project') DEFAULT 'yearly',
  `is_salary_disclosed` tinyint(1) DEFAULT 1,
  `location` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_remote` tinyint(1) DEFAULT 0,
  `skills_required` text DEFAULT NULL,
  `status` enum('active','inactive','closed','draft') DEFAULT 'active',
  `views_count` int(11) DEFAULT 0,
  `applications_count` int(11) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `urgent` tinyint(1) DEFAULT 0,
  `posted_by` int(11) DEFAULT NULL,
  `posted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_company_id` (`company_id`),
  KEY `idx_job_type` (`job_type`),
  KEY `idx_experience_level` (`experience_level`),
  KEY `idx_location` (`location`),
  KEY `idx_status` (`status`),
  KEY `idx_posted_at` (`posted_at`),
  KEY `idx_featured` (`featured`),
  KEY `idx_urgent` (`urgent`),
  FULLTEXT KEY `ft_search` (`title`,`description`,`requirements`),
  CONSTRAINT `fk_jobs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Job Skills Table (Many-to-Many)
-- ============================================
CREATE TABLE IF NOT EXISTS `job_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `is_required` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_job_id` (`job_id`),
  KEY `idx_skill_name` (`skill_name`),
  CONSTRAINT `fk_job_skills_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(500) DEFAULT NULL,
  `resume` varchar(500) DEFAULT NULL,
  `user_type` enum('job_seeker','employer','admin') DEFAULT 'job_seeker',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `email_verified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_user_type` (`user_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Saved Jobs Table
-- ============================================
CREATE TABLE IF NOT EXISTS `saved_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_job` (`user_id`,`job_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_job_id` (`job_id`),
  CONSTRAINT `fk_saved_jobs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saved_jobs_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Job Applications Table
-- ============================================
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `resume` varchar(500) DEFAULT NULL,
  `status` enum('pending','reviewing','shortlisted','rejected','accepted') DEFAULT 'pending',
  `applied_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `reviewed_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_job_id` (`job_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_applied_at` (`applied_at`),
  CONSTRAINT `fk_applications_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_applications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Sample Data - Companies
-- ============================================
INSERT INTO `companies` (`id`, `name`, `slug`, `logo`, `website`, `description`, `industry`, `size`, `founded_year`, `headquarters`, `rating`, `total_reviews`) VALUES
(1, 'Google', 'google', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB05iY8MHCloko0xXgRy_Jczz3KCqK0j41JrpKtPrLoEFSBFfS3RRHpNwzjo4352pEft_-EM62Omi8fugVrYLNxKrOsfEO5ZP6w9WUGuZZMWAuQs87m3zlh7lr-j_KpkSIAdOUXj7Uyz_BxbAn456x3WlhcmsufhjVi8jlruQLLjoOKsTE-K0ERqPW3aIXAbIXW8nLj0joDAxMs4LQsueuixWEizOvt6Hc_WHFPI-fgqEFcM-OkXqbqruu1W-l7ZNGeaz-xtRB17OU', 'https://www.google.com', 'Google is a multinational technology company specializing in Internet-related services and products.', 'Technology', '10000+', 1998, 'Mountain View, CA', 4.5, 12500),
(2, 'Meta', 'meta', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCa4i9YIcvfi-4ogR9bYPtb6EJMcZ8KfKUSIiSqiXRRJ3jCbf5rdnslYZNneZtbu6y43LO2fS3xzUfDQErXrK9H0LaCLOoNVZ5kfDwXVkQYE6KYUyvX77gLNFrVcfKuUnUSDq-m5bzJ1MBZP07bfb7uuDtHjgZZ5o8CjvB1Mj0HChB1AF-HBDsjY-Ecyst_57BtODR9uqGxFLCw6b2Fh-3ydN3CDzDGN34kd7W_uavR3nMaQ-nhElLHY3Q6rkqlv0zlgsIHBn5nvI0', 'https://www.meta.com', 'Meta builds technologies that help people connect, find communities, and grow businesses.', 'Technology', '10000+', 2004, 'Menlo Park, CA', 4.7, 8900),
(3, 'Spotify', 'spotify', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQsPRlVdF3rN3bKlU8wxZtnvbjdk5DNq4DlRb_JCSH3qOCzaHxtyplssUPOFlQAwvq6pVcnSx1QmYwF68l57sHCFdV84ClRyXCzL0pKb7X2nIOmfcEntKcn8SGFGlJItZ4lKsNSIfAFpikh2D8ogZa-76swsmJK1ck4_XPjdYClAxG0bB29yURje5XPKJspi5wSXAmyDEjhrJ-DrbDKQ6V5_133Ar5VEPEqIBToz7WDCjDd-iWk5iXJyHWiDTzVGp02RQO1Gy-h9M', 'https://www.spotify.com', 'Spotify is a digital music service that gives you access to millions of songs.', 'Music & Entertainment', '5000-10000', 2006, 'Stockholm, Sweden', 4.3, 5600),
(4, 'Amazon', 'amazon', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCbPYBEnGDCgg5AuErg8Ad1-82nyneAu2AfDt4vaL-Sb5V6alib6oYn-x2ana1u7rB6knYikdgAICW-02xN1qPS5C1sBWZQR5SbsomyWuq0PWcSLWQngi4oyO_L6zkA0AJ47HG4x1EE_WnZhW0Q5ToBetjzUwBE1aDA9KPpZyR9SWxkTf7bBrTeSXBUpR98uVRt14E4D8NRGanAWd4p6ZOX5ref_jNMLfEiRaxfWXuFWdMN-gfc_BuzwxA9WXt5Og3kwsQxtQM-QyY', 'https://www.amazon.com', 'Amazon is an American multinational technology company focusing on e-commerce, cloud computing, and digital streaming.', 'E-commerce & Technology', '10000+', 1994, 'Seattle, WA', 4.2, 15200),
(5, 'Slack', 'slack', 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6dCnph3Osdogd3AI2I8gtmgR4Nyk3QNY8GcxYg2wiseVuZgqpE3tisH3Sj-F1Ks5SAUJYq6FsLBtLWfjOxe2DNPnErv5aDYg5_yDJgNJl0CnKhLdmvpfF8Ss7HTOYPfQlgDTF8S2_cqGsRGp21QnadsR0ev86n3xoJb0v22ME7ilNwWiHMfnPpB_dJ4--1zA_oqVTcBVsTLQOvCA0G1oph0I7KDcRZxCAITomTFMk2reXTFbn8LvjJU51uuKcZZvLFVU8nxRFRfU', 'https://www.slack.com', 'Slack is a business communication platform offering persistent chat rooms organized by channels.', 'Technology', '1000-5000', 2009, 'San Francisco, CA', 4.6, 3200),
(6, 'Shopify', 'shopify', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAPEpuUQWTh6vxomG4Yb6m5TEd75ohHpmHO11hDa3ACXjcdfAyZpafbzUlgzqP0E_MDHfRWDj_wOdhTGFWrVxVRApC1PKZksihRcqNVMYmkMNK3zLdDgv9x2I6ln4e3rxevAYjXaXhWzUSIX2rFUZoxvz9dmXYk6lMWAMQDE-PNJe4GCK_xz85hFMJ0M1hlJxT9JtY5P3mKJ4Y9GJoZz1fbHW1iOMmXtBK_mC99xxfCQjdHoPyNZ0MkxwjbYD_Fn2CzXGtDFRasDq4', 'https://www.shopify.com', 'Shopify is a commerce platform that allows anyone to set up an online store and sell their products.', 'E-commerce', '5000-10000', 2006, 'Ottawa, ON', 4.4, 4800);

-- ============================================
-- Sample Data - Jobs
-- ============================================
INSERT INTO `jobs` (`id`, `company_id`, `title`, `slug`, `description`, `requirements`, `job_type`, `experience_level`, `min_experience`, `max_experience`, `salary_min`, `salary_max`, `salary_currency`, `salary_period`, `is_salary_disclosed`, `location`, `latitude`, `longitude`, `is_remote`, `status`, `views_count`, `applications_count`, `featured`, `urgent`, `posted_at`) VALUES
(1, 1, 'Senior Product Designer', 'senior-product-designer-google', 'We\'re looking for a creative and passionate product designer to join our team and help shape the future of our products. You will work closely with product managers, engineers, and other designers to create beautiful and functional user experiences.', '5+ years of experience in product design, proficiency in Figma, strong portfolio', 'full-time', 'senior', 5, 10, 15000000.00, 20000000.00, 'INR', 'yearly', 1, 'Mountain View, CA', 37.4220, -122.0841, 0, 'active', 1250, 45, 1, 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 2, 'Backend Engineer (PHP)', 'backend-engineer-php-meta', 'Join our infrastructure team to build and scale the next generation of our platform using PHP 8+ and modern frameworks. You will work on high-traffic systems and help improve performance and reliability.', '2+ years PHP experience, Laravel framework, MySQL, REST APIs', 'full-time', 'junior', 2, 4, 8000000.00, 12000000.00, 'INR', 'yearly', 1, 'Menlo Park, CA', 37.4530, -122.1817, 0, 'active', 890, 32, 0, 0, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 3, 'Data Analyst Intern', 'data-analyst-intern-spotify', 'An exciting opportunity for a student or recent graduate to work with our data science team on user behavior analysis. You will learn from experienced analysts and work on real-world projects.', 'Currently pursuing or recently completed degree in Data Science, Statistics, or related field', 'internship', 'fresher', 0, 1, 3000000.00, 4000000.00, 'INR', 'yearly', 1, 'New York, NY', 40.7128, -74.0060, 0, 'active', 450, 18, 0, 0, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(4, 4, 'Frontend Developer', 'frontend-developer-amazon', 'Build beautiful and responsive user interfaces using Bootstrap 5 and modern JavaScript frameworks for AWS services. You will collaborate with UX designers and backend developers to deliver exceptional user experiences.', 'Experience with Bootstrap 5, JavaScript, React, HTML/CSS', 'contract', 'junior', 1, 3, 7000000.00, 10000000.00, 'INR', 'yearly', 1, 'Seattle, WA', 47.6062, -122.3321, 0, 'active', 680, 28, 0, 0, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 5, 'DevOps Engineer', 'devops-engineer-slack', 'Help maintain and improve our CI/CD pipelines, ensuring our services are reliable and scalable for millions of users. You will work with Docker, Kubernetes, and cloud infrastructure.', 'Experience with CI/CD, Docker, Kubernetes, AWS, monitoring tools', 'full-time', 'senior', 5, 8, 12000000.00, 18000000.00, 'INR', 'yearly', 1, 'San Francisco, CA', 37.7749, -122.4194, 1, 'active', 1120, 52, 1, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(6, 6, 'Marketing Manager', 'marketing-manager-shopify', 'Lead our growth marketing initiatives and develop campaigns to attract new merchants to the Shopify platform. You will work with cross-functional teams to drive user acquisition and engagement.', '3+ years marketing experience, SEO, content marketing, analytics', 'part-time', 'junior', 2, 4, 5000000.00, 7000000.00, 'INR', 'yearly', 1, 'Ottawa, ON', 45.4215, -75.6972, 0, 'active', 320, 15, 0, 0, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(7, 1, 'Full Stack Developer (Remote)', 'full-stack-developer-remote-google', 'Build scalable web applications using modern technologies. Work remotely with a global team of talented developers. You will work on both frontend and backend systems.', 'Node.js, React, TypeScript, MongoDB, 3+ years experience', 'remote', 'senior', 3, 7, 14000000.00, 20000000.00, 'INR', 'yearly', 1, 'Remote', NULL, NULL, 1, 'active', 950, 38, 1, 0, DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(8, 2, 'PHP Developer', 'php-developer-meta', 'We are looking for a PHP developer with experience in Laravel framework to join our growing team. You will work on building and maintaining web applications.', 'PHP, Laravel, MySQL, REST API development', 'full-time', 'junior', 1, 3, 6000000.00, 9000000.00, 'INR', 'yearly', 1, 'Mumbai, India', 19.0760, 72.8777, 0, 'active', 750, 25, 1, 0, DATE_SUB(NOW(), INTERVAL 6 HOUR));

-- ============================================
-- Sample Data - Job Skills
-- ============================================
INSERT INTO `job_skills` (`job_id`, `skill_name`, `is_required`) VALUES
(1, 'Design', 1),
(1, 'UI/UX', 1),
(1, 'Figma', 1),
(1, 'Prototyping', 0),
(2, 'PHP', 1),
(2, 'MySQL', 1),
(2, 'Laravel', 1),
(2, 'API', 1),
(3, 'Analytics', 1),
(3, 'SQL', 1),
(3, 'Python', 0),
(4, 'Bootstrap 5', 1),
(4, 'JavaScript', 1),
(4, 'Frontend', 1),
(5, 'CI/CD', 1),
(5, 'Docker', 1),
(5, 'Kubernetes', 1),
(5, 'AWS', 1),
(6, 'Marketing', 1),
(6, 'SEO', 1),
(6, 'Content', 1),
(7, 'Node.js', 1),
(7, 'React', 1),
(7, 'TypeScript', 1),
(7, 'MongoDB', 1),
(8, 'PHP', 1),
(8, 'Laravel', 1),
(8, 'MySQL', 1),
(8, 'REST API', 1);

-- ============================================
-- Sample Data - Users (Admin & Test Users)
-- ============================================
-- Default Admin User (password: admin123)
INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `user_type`, `status`, `email_verified`, `email_verified_at`, `created_at`) VALUES
(1, 'admin@toptopjobs.local', '$2y$12$0kml4MBOFbCKs/QHGFodv.N4bsOUSkAaeVKzeOz.6YATTO2ogEOEu', 'Admin', 'User', 'admin', 'active', 1, NOW(), NOW());

-- Test Job Seeker (password: user123)
INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `user_type`, `status`, `email_verified`, `email_verified_at`, `created_at`) VALUES
(2, 'user@toptopjobs.local', '$2y$12$n3tFOLSR7DaxKQsX3.LIw.MKOKFdsVfzQXkegLR6JvPZDvRlU0we2', 'John', 'Doe', 'job_seeker', 'active', 1, NOW(), NOW());

-- Test Employer (password: employer123)
INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `user_type`, `status`, `email_verified`, `email_verified_at`, `created_at`) VALUES
(3, 'employer@toptopjobs.local', '$2y$12$hR1AxIbEX0p/hT54eCouPuUpPE040P6iUQMXD/bdtBn3zM8Y7R2h2', 'Jane', 'Smith', 'employer', 'active', 1, NOW(), NOW());

-- Default Login Credentials:
-- Admin: admin@toptopjobs.local / admin123
-- User: user@toptopjobs.local / user123
-- Employer: employer@toptopjobs.local / employer123
-- 
-- IMPORTANT: Change these passwords after first login!

-- ============================================
-- Indexes for Performance
-- ============================================
-- Additional indexes are already created above with the tables
-- These composite indexes can help with common queries:

-- For job search queries
CREATE INDEX `idx_job_search` ON `jobs` (`status`, `job_type`, `experience_level`, `posted_at` DESC);

-- For location-based searches
CREATE INDEX `idx_location_coords` ON `jobs` (`latitude`, `longitude`);

-- ============================================
-- End of Database Schema
-- ============================================

