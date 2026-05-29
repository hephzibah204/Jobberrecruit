/* ============================================================
   Industry/Category SEO Migration
   Adds description, meta_description, seo_h1 to industries
   Seeds all 23 job category pages with SEO copy
   URL pattern: /{slug}-jobs  (e.g. /energy-oil-gas-jobs)
   ============================================================ */

/* (Note: You already ran this part, so the columns exist. ALTER TABLE block removed). */

/* ============================================================
   Upsert 23 industry/category pages
   Keyed on slug — safe to re-run
   ============================================================ */

INSERT INTO `industries` (`name`, `slug`, `parent_id`, `is_active`, `seo_h1`, `meta_description`, `description`)
VALUES

/* 1. Accounting, Finance & Banking */
('Accounting, Finance & Banking', 'accounting-finance-banking', NULL, 1,
 'Accounting, Finance & Banking Jobs in Nigeria',
 'Browse verified accounting, finance and banking jobs in Nigeria. Filter by salary, experience and location to find your next role.',
 'Find verified accounting, finance, and banking jobs across Nigeria. Browse roles for chartered accountants, financial analysts, auditors, investment bankers, treasury officers, and credit analysts from top firms including commercial banks, microfinance institutions, Big Four accounting firms, and financial services companies. Filter by location, salary, and experience level to find your next career move in Lagos, Abuja, Port Harcourt, and beyond.'),

/* 2. Administration & Office Support */
('Administration & Office Support', 'administration-office-support', NULL, 1,
 'Administration & Office Support Jobs in Nigeria',
 'Browse verified administration and office support jobs in Nigeria. Filter by salary, experience and location.',
 'Browse verified administration and office support vacancies in Nigeria. Find roles for executive assistants, office managers, administrative officers, receptionists, data entry clerks, and personal assistants across sectors including banking, oil and gas, NGOs, and government agencies. Whether you are entry-level or experienced, discover admin jobs in Lagos, Abuja, and major cities across Nigeria.'),

/* 3. Agriculture & Agribusiness */
('Agriculture & Agribusiness', 'agriculture-agribusiness', NULL, 1,
 'Agriculture & Agribusiness Jobs in Nigeria',
 'Explore agriculture and agribusiness jobs across Nigeria. Filter by location, salary and experience level.',
 'Explore agriculture and agribusiness job opportunities across Nigeria. Find vacancies for agronomists, farm managers, agricultural extension officers, agro-processing technicians, supply chain specialists, and agribusiness development officers. From crop production and livestock management to food processing and export, discover roles with leading agribusiness companies, cooperatives, and development organisations working across Nigeria\'s agricultural sector.'),

/* 4. Construction & Real Estate */
('Construction & Real Estate', 'construction-real-estate', NULL, 1,
 'Construction & Real Estate Jobs in Nigeria',
 'Find construction and real estate jobs in Nigeria. Browse roles for engineers, surveyors, architects and project managers.',
 'Find construction and real estate jobs in Nigeria from verified employers. Browse vacancies for civil engineers, site managers, quantity surveyors, architects, project managers, real estate agents, property developers, and facility managers. Opportunities span residential, commercial, and infrastructure projects with leading construction firms, real estate companies, and property management organisations across Lagos, Abuja, Port Harcourt, and other major cities.'),

/* 5. Customer Service & Call Center */
('Customer Service & Call Center', 'customer-service-call-center', NULL, 1,
 'Customer Service & Call Center Jobs in Nigeria',
 'Discover customer service and call center jobs in Nigeria. Browse verified vacancies from telecoms, banks and BPO firms.',
 'Discover customer service and call center job opportunities across Nigeria. Find roles for customer care representatives, call center agents, customer success managers, help desk officers, and client relations executives. Top employers include telecoms companies, banks, e-commerce platforms, and BPO firms. Whether you are looking for shift-based call center roles or senior customer experience positions, browse verified vacancies in Lagos and across Nigeria.'),

/* 6. Education & Training */
('Education & Training', 'education-training', NULL, 1,
 'Education & Training Jobs in Nigeria',
 'Browse education and training jobs in Nigeria. Find vacancies for teachers, lecturers and education coordinators.',
 'Browse education and training job opportunities in Nigeria. Find vacancies for teachers, lecturers, school administrators, curriculum developers, corporate trainers, e-learning specialists, and education coordinators. Opportunities span primary and secondary schools, universities, vocational training institutions, EdTech startups, and international schools. Discover teaching jobs and education careers across Lagos, Abuja, and major cities in Nigeria.'),

/* 7. Energy, Oil & Gas */
('Energy, Oil & Gas', 'energy-oil-gas', NULL, 1,
 'Oil & Gas Jobs in Nigeria — Browse Verified Vacancies',
 'Browse verified oil, gas and energy jobs in Nigeria. Find roles in drilling, production, HSE and project management.',
 'Explore hundreds of verified oil, gas, and energy sector jobs from leading companies across Nigeria. Whether you are an experienced petroleum engineer, HSE professional, or a fresh graduate looking to break into the upstream sector, find roles matched to your level. Browse opportunities with IOCs, indigenous oil companies, and energy firms including roles in drilling, production, maintenance, subsea operations, and project management across Lagos, Port Harcourt, Warri, and offshore locations.'),

/* 8. Engineering & Technical */
('Engineering & Technical', 'engineering-technical', NULL, 1,
 'Engineering & Technical Jobs in Nigeria',
 'Find engineering and technical jobs in Nigeria. Browse roles for mechanical, electrical and chemical engineers.',
 'Find engineering and technical jobs in Nigeria from top employers. Browse verified vacancies for mechanical engineers, electrical engineers, chemical engineers, structural engineers, instrumentation technicians, maintenance engineers, and project engineers. Opportunities span oil and gas, manufacturing, telecoms, construction, and power sectors. Filter by discipline, location, and experience level to find your next engineering role in Nigeria.'),

/* 9. Graduate Trainee, NYSC & Internships */
('Graduate Trainee, NYSC & Internships', 'graduate-trainee-nysc-internships', NULL, 1,
 'Graduate Trainee, NYSC & Internship Jobs in Nigeria',
 'Find graduate trainee programmes, NYSC placements and internships in Nigeria. Apply to top companies today.',
 'Launch your career with graduate trainee programmes, NYSC placements, and internship opportunities across Nigeria. Find openings at banks, oil and gas companies, FMCG firms, telecoms operators, and professional services firms that offer structured programmes for fresh graduates and corps members. Browse entry-level vacancies, graduate schemes, and industrial training (IT) positions to kickstart your professional journey.'),

/* 10. Healthcare & Pharmaceuticals */
('Healthcare & Pharmaceuticals', 'healthcare-pharmaceuticals', NULL, 1,
 'Healthcare & Pharmaceutical Jobs in Nigeria',
 'Browse healthcare and pharmaceutical jobs in Nigeria. Find roles for doctors, nurses, pharmacists and more.',
 'Browse verified healthcare and pharmaceutical job opportunities in Nigeria. Find vacancies for doctors, nurses, pharmacists, medical laboratory scientists, radiographers, public health officers, hospital administrators, and pharmaceutical sales representatives. Opportunities span private hospitals, government health institutions, pharmaceutical companies, and international health organisations operating across Nigeria.'),

/* 11. Hospitality, Travel & Tourism */
('Hospitality, Travel & Tourism', 'hospitality-travel-tourism', NULL, 1,
 'Hospitality, Travel & Tourism Jobs in Nigeria',
 'Discover hospitality, travel and tourism jobs in Nigeria. Browse roles for hotel managers, chefs and travel consultants.',
 'Discover hospitality, travel, and tourism job opportunities across Nigeria. Browse vacancies for hotel managers, front desk officers, chefs, food and beverage supervisors, travel consultants, event planners, and airline staff. Find roles with leading hotels, resorts, travel agencies, airlines, and tourism companies in Lagos, Abuja, and across Nigeria\'s growing hospitality industry.'),

/* 12. Human Resources & Recruitment */
('Human Resources & Recruitment', 'human-resources-recruitment', NULL, 1,
 'Human Resources & Recruitment Jobs in Nigeria',
 'Find HR and recruitment jobs in Nigeria. Browse roles for HR managers, talent acquisition and L&D officers.',
 'Find human resources and recruitment jobs in Nigeria from verified employers. Browse vacancies for HR managers, talent acquisition specialists, HR business partners, learning and development officers, compensation and benefits analysts, and HR generalists. Opportunities span multinationals, financial institutions, telecoms, manufacturing companies, and professional services firms across Lagos, Abuja, and major cities in Nigeria.'),

/* 13. Information Technology (IT) & Software */
('Information Technology (IT) & Software', 'information-technology-software', NULL, 1,
 'IT & Software Jobs in Nigeria — Tech Vacancies',
 'Explore IT and software jobs in Nigeria. Find roles for developers, data scientists, cybersecurity analysts and DevOps engineers.',
 'Explore information technology and software jobs across Nigeria. Find verified vacancies for software developers, data scientists, cybersecurity analysts, network engineers, product managers, UI/UX designers, DevOps engineers, and IT support officers. Opportunities span fintech startups, banks, telecoms, e-commerce platforms, and multinational tech firms. Browse remote and on-site tech roles in Lagos, Abuja, and beyond.'),

/* 14. Legal & Compliance */
('Legal & Compliance', 'legal-compliance', NULL, 1,
 'Legal & Compliance Jobs in Nigeria',
 'Browse legal and compliance jobs in Nigeria. Find roles for lawyers, compliance managers and company secretaries.',
 'Browse legal and compliance job opportunities across Nigeria. Find vacancies for lawyers, legal officers, compliance managers, company secretaries, contract analysts, regulatory affairs specialists, and in-house counsel. Opportunities span law firms, financial institutions, oil and gas companies, telecoms, and multinational corporations operating in Nigeria. Filter by practice area, location, and seniority to find your next legal role.'),

/* 15. Logistics, Supply Chain & Procurement */
('Logistics, Supply Chain & Procurement', 'logistics-supply-chain-procurement', NULL, 1,
 'Logistics, Supply Chain & Procurement Jobs in Nigeria',
 'Find logistics, supply chain and procurement jobs in Nigeria. Browse roles for warehouse managers and freight forwarders.',
 'Find logistics, supply chain, and procurement jobs in Nigeria from top employers. Browse vacancies for supply chain managers, logistics coordinators, warehouse managers, procurement officers, fleet managers, freight forwarders, and inventory analysts. Opportunities span FMCG, oil and gas, manufacturing, e-commerce, and international trade sectors across Lagos, Port Harcourt, Kano, and major commercial hubs in Nigeria.'),

/* 16. Manufacturing & Production */
('Manufacturing & Production', 'manufacturing-production', NULL, 1,
 'Manufacturing & Production Jobs in Nigeria',
 'Discover manufacturing and production jobs in Nigeria. Browse roles for plant operators, quality officers and process engineers.',
 'Discover manufacturing and production job opportunities across Nigeria. Find verified vacancies for production managers, plant operators, quality control officers, factory supervisors, process engineers, safety officers, and maintenance technicians. Roles available with FMCG companies, food and beverage manufacturers, pharmaceutical plants, cement companies, and industrial manufacturers across Lagos, Ogun, Kano, and major industrial states.'),

/* 17. Marketing, Advertising & Communications */
('Marketing, Advertising & Communications', 'marketing-advertising-communications', NULL, 1,
 'Marketing, Advertising & Communications Jobs in Nigeria',
 'Browse marketing, advertising and communications jobs in Nigeria. Find roles for brand managers and digital marketers.',
 'Browse marketing, advertising, and communications jobs across Nigeria. Find vacancies for marketing managers, digital marketers, brand managers, media planners, public relations officers, content strategists, and communications specialists. Opportunities span advertising agencies, FMCG companies, banks, telecoms, and media organisations. Discover roles in Lagos and major cities for experienced marketers and rising talent alike.'),

/* 18. Media, Creative & Design */
('Media, Creative & Design', 'media-creative-design', NULL, 1,
 'Media, Creative & Design Jobs in Nigeria',
 'Find media, creative and design jobs in Nigeria. Browse roles for graphic designers, content creators and videographers.',
 'Find media, creative, and design job opportunities across Nigeria. Browse verified vacancies for graphic designers, video editors, content creators, journalists, photographers, art directors, copywriters, motion graphics artists, and broadcast producers. Opportunities span media houses, advertising agencies, digital studios, e-commerce brands, and entertainment companies in Lagos, Abuja, and across Nigeria\'s growing creative economy.'),

/* 19. NGO & Non-Profit */
('NGO & Non-Profit', 'ngo-non-profit', NULL, 1,
 'NGO & Non-Profit Jobs in Nigeria',
 'Explore NGO and non-profit jobs in Nigeria. Find roles for programme officers, M&E specialists and humanitarian workers.',
 'Explore NGO and non-profit job opportunities across Nigeria. Find vacancies for programme officers, monitoring and evaluation specialists, project coordinators, grants managers, community development officers, humanitarian workers, and communications staff. Opportunities available with international NGOs, local civil society organisations, UN agencies, and development finance institutions working in health, education, agriculture, governance, and humanitarian response across Nigeria.'),

/* 20. Procurement & Supply Chain */
('Procurement & Supply Chain', 'procurement-supply-chain', NULL, 1,
 'Procurement & Supply Chain Jobs in Nigeria',
 'Browse procurement and supply chain jobs in Nigeria. Find roles for sourcing analysts, vendor managers and category managers.',
 'Browse procurement and supply chain vacancies across Nigeria. Find roles for procurement managers, category managers, strategic sourcing analysts, vendor managers, contract specialists, and supply chain planners. Opportunities span oil and gas, FMCG, manufacturing, banking, and public sector organisations. Whether you are a seasoned procurement professional or building your supply chain career, discover verified roles with top Nigerian and multinational employers.'),

/* 21. Sales & Business Development */
('Sales & Business Development', 'sales-business-development', NULL, 1,
 'Sales & Business Development Jobs in Nigeria',
 'Find sales and business development jobs in Nigeria. Browse roles for account managers, BDEs and sales directors.',
 'Find sales and business development job opportunities across Nigeria. Browse verified vacancies for sales managers, business development executives, account managers, field sales representatives, key account officers, and sales directors. Roles available across fintech, FMCG, telecoms, insurance, real estate, and B2B services sectors. Discover commission-based, fixed, and hybrid sales roles with top employers across Lagos, Abuja, and all major cities in Nigeria.'),

/* 22. Science & Research */
('Science & Research', 'science-research', NULL, 1,
 'Science & Research Jobs in Nigeria',
 'Discover science and research jobs in Nigeria. Find roles for research scientists, lab analysts and environmental scientists.',
 'Discover science and research job opportunities across Nigeria. Find vacancies for research scientists, laboratory analysts, data analysts, clinical researchers, environmental scientists, geologists, chemists, and research officers. Opportunities span pharmaceutical companies, academic institutions, government research agencies, oil and gas firms, and international development organisations conducting research across Nigeria\'s key sectors.'),

/* 23. Security & Safety */
('Security & Safety', 'security-safety', NULL, 1,
 'Security & Safety Jobs in Nigeria',
 'Browse security and safety jobs in Nigeria. Find HSE officers, safety supervisors and loss prevention roles.',
 'Browse security and safety job opportunities across Nigeria. Find verified vacancies for HSE officers, security managers, safety supervisors, loss prevention officers, corporate security executives, and fire safety officers. Opportunities span oil and gas, construction, manufacturing, banking, and corporate organisations. Find entry-level and senior security and safety roles with reputable employers across Lagos, Port Harcourt, Abuja, and beyond.'),

/* 24. Retail & Ecommerce */
('Retail & Ecommerce', 'retail-ecommerce', NULL, 1,
 'Retail & Ecommerce Jobs in Nigeria',
 'Discover retail and ecommerce jobs in Nigeria. Browse roles for store managers, merchandisers and e-commerce specialists.',
 'Discover verified retail and ecommerce job opportunities across Nigeria. Find vacancies for store managers, merchandisers, sales associates, e-commerce specialists, digital marketers, inventory controllers, and customer service representatives. Opportunities span supermarkets, fashion retailers, electronics stores, online marketplaces, and FMCG companies operating across Lagos, Abuja, Port Harcourt, and other major cities. Filter by location, experience level, and salary to find your next retail or ecommerce role in Nigeria\'s growing consumer market.'),

/* 25. Arts, Entertainment & Sports */
('Arts, Entertainment & Sports', 'arts-entertainment-sports', NULL, 1,
 'Arts, Entertainment & Sports Jobs in Nigeria',
 'Find arts, entertainment and sports jobs in Nigeria. Browse roles for graphic designers, content creators, event managers, and sports coaches.',
 'Discover arts, entertainment, and sports job opportunities across Nigeria. Browse verified vacancies for graphic designers, video editors, musicians, event managers, content creators, choreographers, photographers, art directors, sports coaches, fitness instructors, and broadcast producers. Opportunities span media houses, entertainment companies, digital studios, sports organisations, and cultural institutions in Lagos, Abuja, and across Nigeria\'s vibrant creative and sports economy.'),

/* 26. Government & Non Profits */
('Government & Non Profits', 'government-non-profits', NULL, 1,
 'Government & Non Profit Jobs in Nigeria',
 'Explore government and non-profit jobs in Nigeria. Find roles for policy analysts, programme officers and humanitarian workers.',
 'Explore verified government and non-profit job opportunities across Nigeria. Find vacancies for policy analysts, programme officers, monitoring and evaluation specialists, grants administrators, civil servants, and humanitarian workers. Opportunities span federal and state government ministries, agencies, parastatals, local government councils, international NGOs, UN agencies, and development finance institutions. Whether you are looking for roles in public administration, social services, community development, or humanitarian response, browse verified vacancies in Abuja, Lagos, and across Nigeria\'s public and social sectors.'),

/* 27. Health, Medical & Pharmaceutical */
('Health, Medical & Pharmaceutical', 'health-medical-pharmaceutical', NULL, 1,
 'Healthcare, Medical & Pharmaceutical Jobs in Nigeria',
 'Browse healthcare, medical and pharmaceutical jobs in Nigeria. Find roles for doctors, nurses, pharmacists and clinical researchers.',
 'Explore verified healthcare, medical, and pharmaceutical job opportunities across Nigeria. Find vacancies for doctors, nurses, pharmacists, medical laboratory scientists, radiographers, physiotherapists, hospital administrators, and clinical researchers. Opportunities span private hospitals, government health institutions, pharmaceutical companies, diagnostic centers, and international health organisations operating in Lagos, Abuja, Port Harcourt, Kano, and other major cities. Whether you\'re a medical practitioner, allied health professional, or pharmaceutical specialist, discover roles matching your qualifications and experience level.'),

/* 28. Transport & Logistics */
('Transport & Logistics', 'transport-logistics', NULL, 1,
 'Transport & Logistics Jobs in Nigeria',
 'Find transport and logistics jobs in Nigeria. Browse roles for fleet managers, logistics coordinators and supply chain officers.',
 'Find transport and logistics job opportunities across Nigeria from verified employers. Browse vacancies for fleet managers, logistics coordinators, transport officers, warehouse managers, supply chain analysts, freight forwarders, dispatch riders, logistics drivers, and inventory officers. Opportunities span transport companies, e-commerce platforms, FMCG firms, oil and gas companies, and third-party logistics providers across Lagos, Abuja, Port Harcourt, Kano, and major commercial hubs across Nigeria.')

ON DUPLICATE KEY UPDATE
    `seo_h1`           = VALUES(`seo_h1`),
    `meta_description` = VALUES(`meta_description`),
    `description`      = VALUES(`description`),
    `is_active`        = VALUES(`is_active`);
