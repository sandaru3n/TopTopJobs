-- ============================================
-- Add Country Fields to Jobs Table
-- ============================================
-- This migration adds country and country_code fields to jobs table
-- Default country is LK (Sri Lanka)

USE `toptopjobs`;

-- Add country fields to jobs table
ALTER TABLE `jobs` 
ADD COLUMN `country` VARCHAR(100) DEFAULT 'Sri Lanka' AFTER `location`,
ADD COLUMN `country_code` VARCHAR(2) DEFAULT 'LK' AFTER `country`,
ADD INDEX `idx_country_code` (`country_code`);

-- Update existing jobs to have default country (Sri Lanka)
UPDATE `jobs` SET `country` = 'Sri Lanka', `country_code` = 'LK' WHERE `country` IS NULL OR `country_code` IS NULL;
