-- ============================================
-- Seed Categories and Subcategories
-- ============================================

USE `toptopjobs`;

-- Helper function to create slug
SET @counter = 0;

-- Insert Categories and Subcategories
-- Category 1: IT & Software
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('IT & Software', 'it-software', 1);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Software Engineer', 'software-engineer', 1),
(@category_id, 'Full Stack Developer', 'full-stack-developer', 2),
(@category_id, 'Frontend Developer', 'frontend-developer', 3),
(@category_id, 'Backend Developer', 'backend-developer', 4),
(@category_id, 'Mobile App Developer (iOS/Android)', 'mobile-app-developer', 5),
(@category_id, 'Web Developer', 'web-developer', 6),
(@category_id, 'Data Scientist', 'data-scientist', 7),
(@category_id, 'Data Analyst', 'data-analyst', 8),
(@category_id, 'Machine Learning Engineer', 'machine-learning-engineer', 9),
(@category_id, 'AI Engineer', 'ai-engineer', 10),
(@category_id, 'Cloud Engineer (AWS/Azure/GCP)', 'cloud-engineer', 11),
(@category_id, 'DevOps Engineer', 'devops-engineer', 12),
(@category_id, 'Cybersecurity Specialist', 'cybersecurity-specialist', 13),
(@category_id, 'Network Engineer', 'network-engineer', 14),
(@category_id, 'Systems Administrator', 'systems-administrator', 15),
(@category_id, 'QA Tester / Quality Assurance Engineer', 'qa-tester', 16),
(@category_id, 'UI/UX Designer', 'ui-ux-designer', 17),
(@category_id, 'Product Manager', 'product-manager', 18),
(@category_id, 'IT Support / Help Desk Technician', 'it-support', 19),
(@category_id, 'Database Administrator (DBA)', 'database-administrator', 20);

-- Category 2: Marketing & Advertising
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Marketing & Advertising', 'marketing-advertising', 2);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Digital Marketer', 'digital-marketer', 1),
(@category_id, 'SEO Specialist', 'seo-specialist', 2),
(@category_id, 'Social Media Manager', 'social-media-manager', 3),
(@category_id, 'Content Writer', 'content-writer', 4),
(@category_id, 'Copywriter', 'copywriter', 5),
(@category_id, 'Graphic Designer', 'graphic-designer', 6),
(@category_id, 'Brand Manager', 'brand-manager', 7),
(@category_id, 'Email Marketing Specialist', 'email-marketing-specialist', 8),
(@category_id, 'PPC/Google Ads Specialist', 'ppc-google-ads-specialist', 9),
(@category_id, 'Marketing Manager', 'marketing-manager', 10),
(@category_id, 'Influencer Marketing Coordinator', 'influencer-marketing-coordinator', 11);

-- Category 3: Sales
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Sales', 'sales', 3);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Sales Executive', 'sales-executive', 1),
(@category_id, 'Business Development Executive', 'business-development-executive', 2),
(@category_id, 'Account Manager', 'account-manager', 3),
(@category_id, 'Retail Sales Associate', 'retail-sales-associate', 4),
(@category_id, 'Sales Manager', 'sales-manager', 5),
(@category_id, 'Telemarketing Agent', 'telemarketing-agent', 6),
(@category_id, 'Lead Generation Specialist', 'lead-generation-specialist', 7);

-- Category 4: Customer Service
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Customer Service', 'customer-service', 4);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Customer Support Representative', 'customer-support-representative', 1),
(@category_id, 'Call Center Agent', 'call-center-agent', 2),
(@category_id, 'Technical Support Agent', 'technical-support-agent', 3),
(@category_id, 'Client Relationship Manager', 'client-relationship-manager', 4),
(@category_id, 'Help Desk Representative', 'help-desk-representative', 5);

-- Category 5: Finance & Accounting
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Finance & Accounting', 'finance-accounting', 5);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Accountant', 'accountant', 1),
(@category_id, 'Financial Analyst', 'financial-analyst', 2),
(@category_id, 'Bookkeeper', 'bookkeeper', 3),
(@category_id, 'Audit Associate', 'audit-associate', 4),
(@category_id, 'Tax Consultant', 'tax-consultant', 5),
(@category_id, 'Finance Manager', 'finance-manager', 6),
(@category_id, 'Payroll Officer', 'payroll-officer', 7),
(@category_id, 'Investment Analyst', 'investment-analyst', 8);

-- Category 6: Engineering
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Engineering', 'engineering', 6);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Civil Engineer', 'civil-engineer', 1),
(@category_id, 'Mechanical Engineer', 'mechanical-engineer', 2),
(@category_id, 'Electrical Engineer', 'electrical-engineer', 3),
(@category_id, 'Electronics Engineer', 'electronics-engineer', 4),
(@category_id, 'Chemical Engineer', 'chemical-engineer', 5),
(@category_id, 'Structural Engineer', 'structural-engineer', 6),
(@category_id, 'Industrial Engineer', 'industrial-engineer', 7),
(@category_id, 'Project Engineer', 'project-engineer', 8),
(@category_id, 'Quality Engineer', 'quality-engineer', 9);

-- Category 7: Design & Creative
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Design & Creative', 'design-creative', 7);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Graphic Designer', 'graphic-designer', 1),
(@category_id, 'UI/UX Designer', 'ui-ux-designer', 2),
(@category_id, 'Motion Graphics Designer', 'motion-graphics-designer', 3),
(@category_id, 'Video Editor', 'video-editor', 4),
(@category_id, 'Interior Designer', 'interior-designer', 5),
(@category_id, 'Photographer', 'photographer', 6),
(@category_id, 'Illustrator', 'illustrator', 7),
(@category_id, '3D Artist / Animator', '3d-artist-animator', 8);

-- Category 8: Healthcare & Medical
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Healthcare & Medical', 'healthcare-medical', 8);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Nurse', 'nurse', 1),
(@category_id, 'Doctor', 'doctor', 2),
(@category_id, 'Pharmacist', 'pharmacist', 3),
(@category_id, 'Lab Technician', 'lab-technician', 4),
(@category_id, 'Medical Assistant', 'medical-assistant', 5),
(@category_id, 'Physiotherapist', 'physiotherapist', 6),
(@category_id, 'Nutritionist', 'nutritionist', 7),
(@category_id, 'Dental Surgeon', 'dental-surgeon', 8),
(@category_id, 'Healthcare Administrator', 'healthcare-administrator', 9);

-- Category 9: Education & Training
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Education & Training', 'education-training', 9);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Teacher', 'teacher', 1),
(@category_id, 'Lecturer', 'lecturer', 2),
(@category_id, 'Tutor', 'tutor', 3),
(@category_id, 'Academic Coordinator', 'academic-coordinator', 4),
(@category_id, 'Special Education Teacher', 'special-education-teacher', 5),
(@category_id, 'Training & Development Officer', 'training-development-officer', 6);

-- Category 10: Hospitality & Tourism
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Hospitality & Tourism', 'hospitality-tourism', 10);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Hotel Receptionist', 'hotel-receptionist', 1),
(@category_id, 'Chef / Cook', 'chef-cook', 2),
(@category_id, 'Kitchen Assistant', 'kitchen-assistant', 3),
(@category_id, 'Waiter/Waitress', 'waiter-waitress', 4),
(@category_id, 'Barista', 'barista', 5),
(@category_id, 'Restaurant Manager', 'restaurant-manager', 6),
(@category_id, 'Travel Consultant', 'travel-consultant', 7),
(@category_id, 'Housekeeping Staff', 'housekeeping-staff', 8);

-- Category 11: Logistics & Supply Chain
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Logistics & Supply Chain', 'logistics-supply-chain', 11);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Logistics Coordinator', 'logistics-coordinator', 1),
(@category_id, 'Warehouse Worker', 'warehouse-worker', 2),
(@category_id, 'Delivery Driver', 'delivery-driver', 3),
(@category_id, 'Supply Chain Analyst', 'supply-chain-analyst', 4),
(@category_id, 'Inventory Controller', 'inventory-controller', 5),
(@category_id, 'Procurement Officer', 'procurement-officer', 6),
(@category_id, 'Forklift Operator', 'forklift-operator', 7);

-- Category 12: Construction & Skilled Trades
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Construction & Skilled Trades', 'construction-skilled-trades', 12);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Mason', 'mason', 1),
(@category_id, 'Carpenter', 'carpenter', 2),
(@category_id, 'Plumber', 'plumber', 3),
(@category_id, 'Electrician', 'electrician', 4),
(@category_id, 'Welder', 'welder', 5),
(@category_id, 'Site Supervisor', 'site-supervisor', 6),
(@category_id, 'Heavy Equipment Operator', 'heavy-equipment-operator', 7);

-- Category 13: Human Resources
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Human Resources', 'human-resources', 13);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'HR Assistant', 'hr-assistant', 1),
(@category_id, 'Recruiter', 'recruiter', 2),
(@category_id, 'HR Manager', 'hr-manager', 3),
(@category_id, 'Talent Acquisition Specialist', 'talent-acquisition-specialist', 4),
(@category_id, 'Training & Development Manager', 'training-development-manager', 5);

-- Category 14: Legal
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Legal', 'legal', 14);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Lawyer', 'lawyer', 1),
(@category_id, 'Legal Assistant', 'legal-assistant', 2),
(@category_id, 'Paralegal', 'paralegal', 3),
(@category_id, 'Compliance Officer', 'compliance-officer', 4),
(@category_id, 'Contract Specialist', 'contract-specialist', 5);

-- Category 15: Media, Writing & Communications
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Media, Writing & Communications', 'media-writing-communications', 15);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Journalist', 'journalist', 1),
(@category_id, 'Content Writer', 'content-writer', 2),
(@category_id, 'Editor', 'editor', 3),
(@category_id, 'Public Relations Officer', 'public-relations-officer', 4),
(@category_id, 'Communications Specialist', 'communications-specialist', 5);

-- Category 16: Manufacturing & Production
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Manufacturing & Production', 'manufacturing-production', 16);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Machine Operator', 'machine-operator', 1),
(@category_id, 'Factory Worker', 'factory-worker', 2),
(@category_id, 'Production Manager', 'production-manager', 3),
(@category_id, 'Quality Assurance Inspector', 'quality-assurance-inspector', 4),
(@category_id, 'Assembly Line Worker', 'assembly-line-worker', 5);

-- Category 17: Real Estate
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Real Estate', 'real-estate', 17);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Real Estate Agent', 'real-estate-agent', 1),
(@category_id, 'Property Manager', 'property-manager', 2),
(@category_id, 'Leasing Consultant', 'leasing-consultant', 3),
(@category_id, 'Real Estate Sales Executive', 'real-estate-sales-executive', 4);

-- Category 18: Retail
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Retail', 'retail', 18);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Cashier', 'cashier', 1),
(@category_id, 'Storekeeper', 'storekeeper', 2),
(@category_id, 'Retail Sales Associate', 'retail-sales-associate', 3),
(@category_id, 'Stock Assistant', 'stock-assistant', 4),
(@category_id, 'Store Manager', 'store-manager', 5);

-- Category 19: Agriculture & Environment
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Agriculture & Environment', 'agriculture-environment', 19);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Farm Worker', 'farm-worker', 1),
(@category_id, 'Agricultural Technician', 'agricultural-technician', 2),
(@category_id, 'Environmental Scientist', 'environmental-scientist', 3),
(@category_id, 'Horticulturist', 'horticulturist', 4),
(@category_id, 'Fisherman', 'fisherman', 5);

-- Category 20: Security & Armed Forces
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Security & Armed Forces', 'security-armed-forces', 20);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Security Guard', 'security-guard', 1),
(@category_id, 'Security Supervisor', 'security-supervisor', 2),
(@category_id, 'CCTV Operator', 'cctv-operator', 3),
(@category_id, 'Firefighter', 'firefighter', 4),
(@category_id, 'Military Personnel', 'military-personnel', 5);

-- Category 21: Administrative & Office
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Administrative & Office', 'administrative-office', 21);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Data Entry Operator', 'data-entry-operator', 1),
(@category_id, 'Office Assistant', 'office-assistant', 2),
(@category_id, 'Receptionist', 'receptionist', 3),
(@category_id, 'Admin Executive', 'admin-executive', 4),
(@category_id, 'Executive Assistant', 'executive-assistant', 5),
(@category_id, 'Virtual Assistant', 'virtual-assistant', 6);

-- Category 22: Creative, Entertainment & Arts
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES 
('Creative, Entertainment & Arts', 'creative-entertainment-arts', 22);

SET @category_id = LAST_INSERT_ID();

INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `sort_order`) VALUES
(@category_id, 'Actor', 'actor', 1),
(@category_id, 'Musician', 'musician', 2),
(@category_id, 'Dancer', 'dancer', 3),
(@category_id, 'Video Producer', 'video-producer', 4),
(@category_id, 'Audio Engineer', 'audio-engineer', 5),
(@category_id, 'Event Coordinator', 'event-coordinator', 6);

