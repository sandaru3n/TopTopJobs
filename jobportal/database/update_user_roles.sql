-- ============================================
-- Update Users Table for New Role System
-- ============================================
-- This migration updates the users table to:
-- 1. Change user_type to only 'user' and 'admin'
-- 2. Add roles field to store job_seeker and employer roles

USE `toptopjobs`;

-- Add roles column (JSON to store multiple roles)
ALTER TABLE `users` 
ADD COLUMN `roles` JSON DEFAULT NULL AFTER `user_type`;

-- Update existing data: convert user_type to roles
UPDATE `users` 
SET `roles` = JSON_ARRAY(`user_type`)
WHERE `roles` IS NULL;

-- Change user_type enum to only 'user' and 'admin'
-- First, update existing data
UPDATE `users` 
SET `user_type` = 'user' 
WHERE `user_type` IN ('job_seeker', 'employer');

-- Now alter the enum
ALTER TABLE `users` 
MODIFY COLUMN `user_type` enum('user','admin') DEFAULT 'user';

-- Update sample users with proper roles
UPDATE `users` 
SET `roles` = JSON_ARRAY('job_seeker', 'employer')
WHERE `email` = 'user@toptopjobs.local';

UPDATE `users` 
SET `roles` = JSON_ARRAY('employer')
WHERE `email` = 'employer@toptopjobs.local';

UPDATE `users` 
SET `roles` = JSON_ARRAY('job_seeker', 'employer')
WHERE `email` = 'admin@toptopjobs.local';

-- Add index for roles if needed
-- ALTER TABLE `users` ADD INDEX `idx_roles` ((CAST(`roles` AS CHAR(255) ARRAY)));

