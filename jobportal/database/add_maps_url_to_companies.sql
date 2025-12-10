-- Add maps_url field to companies table for Google Maps links
ALTER TABLE `companies` 
ADD COLUMN `maps_url` VARCHAR(500) NULL DEFAULT NULL AFTER `website`;

