-- =====================================================
-- Course Curriculum Data (Modules)
-- Purpose: Enable Testing of Course Completion and Certificates
-- =====================================================

-- 1. AI Career Launchpad (ID: 1) - Already has some modules, adding more to round it out
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(1, 'Final Assessment & Next Steps', 'Review everything you learned and prepare for your AI-powered career search.', 'text', NULL, NULL, 5, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 2. Interview Confidence (ID: 2) - Adding final module
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(2, 'Mock Interview Practice', 'Practice with real-world scenarios to build your confidence.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 3. CV Rewrite Mastery (ID: 3)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(3, 'Understanding ATS Systems', 'How applicant tracking systems read your CV and what to avoid.', 'youtube', 'https://www.youtube.com/watch?v=ATS_VIDEO_ID', NULL, 1, NOW()),
(3, 'Keyword Optimization', 'Using the right keywords to get noticed by recruiters.', 'text', NULL, NULL, 2, NOW()),
(3, 'Structural Design', 'Modern layout techniques for high-impact CVs.', 'upload', NULL, 'courses/modules/cv_structure.pdf', 3, NOW()),
(3, 'Final CV Checklist', 'Complete your final review and download your certificate.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 4. Employer Hiring Secrets (ID: 4)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(4, 'The Recruitment Funnel', 'How companies manage high volumes of applications.', 'youtube', 'https://www.youtube.com/watch?v=HIRING_SECRET_1', NULL, 1, NOW()),
(4, 'What Recruiters Look For', 'Decoding job descriptions and finding the real requirements.', 'text', NULL, NULL, 2, NOW()),
(4, 'Internal Referral Hacks', 'How to leverage network data to get an interview.', 'text', NULL, NULL, 3, NOW()),
(4, 'Employer Branding', 'Why cultural fit matters more than skills sometimes.', 'upload', NULL, 'courses/modules/culture_fit.pdf', 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 6. Python for Data Science (ID: 6)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(6, 'Python Environment Setup', 'Installing Anaconda and Jupyter Notebooks.', 'youtube', 'https://www.youtube.com/watch?v=PY_DS_1', NULL, 1, NOW()),
(6, 'Pandas Dataframes', 'Cleaning and manipulating large datasets with Pandas.', 'text', NULL, NULL, 2, NOW()),
(6, 'Data Visualization with Matplotlib', 'Creating stunning charts and graphs.', 'youtube', 'https://www.youtube.com/watch?v=PY_DS_2', NULL, 3, NOW()),
(6, 'Final Python Project', 'Building your first data analysis tool.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 7. Social Media Management (ID: 7)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(7, 'Strategy & Content Pillars', 'Developing a roadmap for social success.', 'youtube', 'https://www.youtube.com/watch?v=SM_MGT_1', NULL, 1, NOW()),
(7, 'Algorithm Mastery', 'Understanding Instagram, LinkedIn, and TikTok algorithms.', 'text', NULL, NULL, 2, NOW()),
(7, 'Analytics & Reporting', 'How to measure ROI and present data to clients.', 'upload', NULL, 'courses/modules/reporting_template.xlsx', 3, NOW()),
(7, 'Certificate Project', 'Launching your first live campaign.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 8. Introduction to Agile (ID: 8)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(8, 'The Agile Manifesto', 'Core principles and values of modern project management.', 'youtube', 'https://www.youtube.com/watch?v=AGILE_1', NULL, 1, NOW()),
(8, 'Scrum Roles & Rituals', 'Defining Sprints, Stand-ups, and Retrospectives.', 'text', NULL, NULL, 2, NOW()),
(8, 'Kanban Methodology', 'Visualizing workflow for efficiency.', 'upload', NULL, 'courses/modules/agile_guide.pdf', 3, NOW()),
(8, 'Final Agile Exam', 'Pass this to get your certification.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 9. Personal Finance Basics (ID: 9)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(9, 'Budgeting 101', 'Setting up your first financial tracking sheet.', 'youtube', 'https://www.youtube.com/watch?v=FINANCE_1', NULL, 1, NOW()),
(9, 'The Power of Compounding', 'Why starting early matters for long-term wealth.', 'text', NULL, NULL, 2, NOW()),
(9, 'Investing for Beginners', 'Stocks, Bonds, and Real Estate explained.', 'text', NULL, NULL, 3, NOW()),
(9, 'Course Summary', 'Review and get certified.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 10. Executive Leadership (ID: 10)
INSERT INTO `course_modules` (`course_id`, `title`, `description`, `content_source`, `youtube_url`, `content_file`, `order_index`, `created_at`) 
VALUES 
(10, 'Visionary Thinking', 'How to lead from the front in a changing world.', 'youtube', 'https://www.youtube.com/watch?v=EXEC_1', NULL, 1, NOW()),
(10, 'Emotional Intelligence (EQ)', 'The most critical skill for high-level management.', 'text', NULL, NULL, 2, NOW()),
(10, 'Strategic Decision Making', 'Data-driven choices in high-pressure environments.', 'upload', NULL, 'courses/modules/exec_strategy.pdf', 3, NOW()),
(10, 'Final Leadership Boardroom', 'A capstone case study for certification.', 'text', NULL, NULL, 4, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();
