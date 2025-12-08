-- ============================================
-- Simplify User Types - Remove Roles System
-- ============================================
-- This migration removes the roles column and keeps only user_type
-- user_type can only be 'user' or 'admin'

USE `toptopjobs`;

-- Remove roles column if it exists
ALTER TABLE `users` 
DROP COLUMN IF EXISTS `roles`;

-- Ensure user_type enum only has 'user' and 'admin'
-- First update any existing data
UPDATE `users` 
SET `user_type` = 'user' 
WHERE `user_type` NOT IN ('user', 'admin');

-- Alter the enum to only allow 'user' and 'admin'
ALTER TABLE `users` 
MODIFY COLUMN `user_type` enum('user','admin') DEFAULT 'user';

-- Update sample users
UPDATE `users` 
SET `user_type` = 'user'
WHERE `email` IN ('user@toptopjobs.local', 'employer@toptopjobs.local');

UPDATE `users` 
SET `user_type` = 'admin'
WHERE `email` = 'admin@toptopjobs.local';

