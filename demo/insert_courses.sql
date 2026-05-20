-- SQL Seeder to insert 10 courses of various categories, free and paid
-- DBDriver: SQLite / Standard SQL
-- Compatible with SQLite/MySQL

-- Clear existing courses with matching slugs to allow re-running this seeder safely
DELETE FROM courses WHERE slug IN (
    'web-development-fundamentals',
    'python-for-data-analysis',
    'social-media-marketing-masterclass',
    'intro-to-agile-and-scrum',
    'personal-finance-investment-basics',
    'executive-leadership-strategy',
    'medical-terminology-ethics',
    'high-ticket-sales-negotiation',
    'figma-design-essentials',
    'human-resource-management-guide'
);

-- Insert 10 Courses (Free and Paid in different semantic categories)
INSERT INTO courses (
    title, 
    slug, 
    description, 
    price, 
    instructor, 
    thumbnail, 
    is_active, 
    created_at, 
    updated_at, 
    duration, 
    level, 
    content_source, 
    youtube_url, 
    content_file, 
    is_featured
) VALUES 
-- 1. Web Development (Tech / Free)
(
    'Web Development Fundamentals',
    'web-development-fundamentals',
    '<p>Master the basics of web development. Learn HTML5, CSS3, and core JavaScript concepts to build your very first responsive website from scratch.</p>',
    0.00,
    'Tech Academy',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '3h 15m',
    'beginner',
    'youtube',
    'https://www.youtube.com/watch?v=ysz5S6PUM-U',
    NULL,
    1
),
-- 2. Data Science (Tech / Paid)
(
    'Python for Data Analysis',
    'python-for-data-analysis',
    '<p>Dive into data science. Learn Python programming, NumPy, Pandas, and Matplotlib to analyze and visualize real-world datasets effectively.</p>',
    12000.00,
    'Data School',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '4h 30m',
    'intermediate',
    'youtube',
    'https://www.youtube.com/watch?v=jNQXAC9IVRw',
    NULL,
    0
),
-- 3. Digital Marketing (Marketing / Paid)
(
    'Social Media Marketing Masterclass',
    'social-media-marketing-masterclass',
    '<p>Learn how to grow your brand, run high-converting advertising campaigns, and execute content marketing strategies across major social media channels.</p>',
    8500.00,
    'Digital Growth Lab',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '2h 45m',
    'intermediate',
    'youtube',
    'https://www.youtube.com/watch?v=ysz5S6PUM-U',
    NULL,
    0
),
-- 4. Project Management (Business / Free)
(
    'Introduction to Agile and Scrum',
    'intro-to-agile-and-scrum',
    '<p>Understand the fundamentals of Agile methodologies. Learn how to work in sprints, write effective user stories, and manage project boards using Scrum framework.</p>',
    0.00,
    'Agile Solutions',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '1h 10m',
    'beginner',
    'upload',
    NULL,
    'courses/agile-scrum.html',
    1
),
-- 5. Finance (Finance / Free)
(
    'Personal Finance & Investment Basics',
    'personal-finance-investment-basics',
    '<p>Take control of your money. Learn budgeting methods, debt management, and the core principles of stock market and mutual fund investments.</p>',
    0.00,
    'Wealth Coaches',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '1h 30m',
    'beginner',
    'youtube',
    'https://www.youtube.com/watch?v=jNQXAC9IVRw',
    NULL,
    0
),
-- 6. Leadership (Management / Paid)
(
    'Executive Leadership & Strategy',
    'executive-leadership-strategy',
    '<p>A premium course for senior managers and executives. Master organizational change, corporate strategy, team alignment, and strategic decision-making.</p>',
    35000.00,
    'Leadership Institute',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '5h 15m',
    'advanced',
    'upload',
    NULL,
    'courses/executive-leadership.html',
    1
),
-- 7. Healthcare (Healthcare / Paid)
(
    'Medical Terminology and Ethics',
    'medical-terminology-ethics',
    '<p>An essential course for healthcare aspirants. Learn medical prefix/suffix structures, patient confidentiality, healthcare ethics, and regulatory compliance standards.</p>',
    18000.00,
    'Health Care Training',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '3h 0m',
    'intermediate',
    'youtube',
    'https://www.youtube.com/watch?v=ysz5S6PUM-U',
    NULL,
    0
),
-- 8. Sales (Sales / Paid)
(
    'High-Ticket Sales & Negotiation',
    'high-ticket-sales-negotiation',
    '<p>Master the art of closing high-value deals. Learn active listening techniques, objection handling, pricing presentations, and advanced negotiation methods.</p>',
    22000.00,
    'Sales Pro Network',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '2h 20m',
    'advanced',
    'youtube',
    'https://www.youtube.com/watch?v=jNQXAC9IVRw',
    NULL,
    1
),
-- 9. UI/UX Design (Design / Free)
(
    'Figma Design Essentials',
    'figma-design-essentials',
    '<p>Learn how to design stunning user interfaces using Figma. Master layout grids, components, auto-layout, prototyping, and developer handoff workflows.</p>',
    0.00,
    'Design Hub',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '2h 5m',
    'beginner',
    'upload',
    NULL,
    'courses/figma-design.html',
    0
),
-- 10. Human Resources (HR / Paid)
(
    'Human Resource Management Guide',
    'human-resource-management-guide',
    '<p>Get a comprehensive guide to modern HR workflows. Learn about talent acquisition, onboarding procedures, performance appraisals, and employee relations.</p>',
    15000.00,
    'HR Partners',
    NULL,
    1,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP,
    '3h 10m',
    'intermediate',
    'youtube',
    'https://www.youtube.com/watch?v=ysz5S6PUM-U',
    NULL,
    0
);
