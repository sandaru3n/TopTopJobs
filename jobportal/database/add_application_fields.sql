-- ============================================
-- Optional Migration: Add Application Contact Fields to Jobs Table
-- ============================================
-- This migration adds dedicated fields for application contact information
-- If you don't run this, the application info will still be stored in the description field

USE `toptopjobs`;

-- Add application contact fields to jobs table
ALTER TABLE `jobs` 
ADD COLUMN `application_email` VARCHAR(255) DEFAULT NULL AFTER `location`,
ADD COLUMN `application_url` VARCHAR(500) DEFAULT NULL AFTER `application_email`,
ADD COLUMN `application_phone` VARCHAR(20) DEFAULT NULL AFTER `application_url`;

-- Add index for application_email for faster searches
CREATE INDEX `idx_application_email` ON `jobs` (`application_email`);

-- ============================================
-- Note: This migration is OPTIONAL
-- The current code will work without these fields
-- as application info is stored in the description field
-- ============================================

