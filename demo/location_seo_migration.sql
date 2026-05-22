-- ============================================================
-- Location SEO Migration: Add slug + description to states
-- Run this in your MySQL/MariaDB database
-- ============================================================

-- Step 1: Add columns
-- (Note: You already ran this part, so the columns exist. ALTER TABLE block removed).

-- Step 2: Add unique index on slug
-- (Note: You already ran this part, so the index exists. ALTER TABLE block removed).

-- ============================================================
-- Step 3: Upsert all 37 States + FCT
-- Uses INSERT ... ON DUPLICATE KEY UPDATE on the name column
-- ============================================================

INSERT INTO `states` (`name`, `slug`, `capital`, `region`, `is_active`, `seo_h1`, `meta_description`, `description`)
VALUES
-- 1. Abia
('Abia', 'abia-state', 'Umuahia', 'South East', 1,
 'Jobs in Abia State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Abia State. Find roles in manufacturing, commerce, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Abia State — God\'s Own State. Browse vacancies in manufacturing, commerce, education, and public service with top employers in Umuahia, Aba, and Ohafia. Aba is one of Nigeria\'s foremost commercial and industrial hubs, making Abia one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Abia State jobs today.'),

-- 2. Adamawa
('Adamawa', 'adamawa-state', 'Yola', 'North East', 1,
 'Jobs in Adamawa State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Adamawa State. Find roles in agriculture, education, health, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Adamawa State — Land of Beauty. Browse vacancies in agriculture, education, health, NGOs, and government with top employers in Yola, Mubi, and Numan. A major hub for humanitarian and development organisations in northeastern Nigeria, making Adamawa one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Adamawa State jobs today.'),

-- 3. Akwa Ibom
('Akwa Ibom', 'akwa-ibom-state', 'Uyo', 'South South', 1,
 'Jobs in Akwa Ibom State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Akwa Ibom State. Find roles in oil and gas, aviation, construction, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Akwa Ibom State — Land of Promise. Browse vacancies in oil and gas, aviation, construction, hospitality, and government with top employers in Uyo, Eket, and Ikot Ekpene. Home to Ibom Air and major oil and gas operations including ExxonMobil affiliates, making Akwa Ibom one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Akwa Ibom State jobs today.'),

-- 4. Anambra
('Anambra', 'anambra-state', 'Awka', 'South East', 1,
 'Jobs in Anambra State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Anambra State. Find roles in trade, manufacturing, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Anambra State — Light of the Nation. Browse vacancies in trade, manufacturing, education, banking, and SMEs with top employers in Awka, Onitsha, and Nnewi. Onitsha hosts one of West Africa\'s largest markets and Nnewi is Nigeria\'s auto parts manufacturing capital, making Anambra one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Anambra State jobs today.'),

-- 5. Bauchi
('Bauchi', 'bauchi-state', 'Bauchi', 'North East', 1,
 'Jobs in Bauchi State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Bauchi State. Find roles in agriculture, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Bauchi State — Pearl of Tourism. Browse vacancies in agriculture, government, education, health, and NGOs with top employers in Bauchi, Azare, and Misau. Home to Yankari Game Reserve and a growing base for development organisations, making Bauchi one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Bauchi State jobs today.'),

-- 6. Bayelsa
('Bayelsa', 'bayelsa-state', 'Yenagoa', 'South South', 1,
 'Jobs in Bayelsa State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Bayelsa State. Find roles in oil and gas, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Bayelsa State — Glory of All Lands. Browse vacancies in oil and gas, government, education, and maritime with top employers in Yenagoa, Ogbia, and Brass. Headquarters of several upstream oil operations and home to major Niger Delta communities, making Bayelsa one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Bayelsa State jobs today.'),

-- 7. Benue
('Benue', 'benue-state', 'Makurdi', 'North Central', 1,
 'Jobs in Benue State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Benue State. Find roles in agriculture, education, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Benue State — Food Basket of the Nation. Browse vacancies in agriculture, education, government, health, and NGOs with top employers in Makurdi, Gboko, and Katsina-Ala. One of Nigeria\'s most productive agricultural states and a centre for development sector work, making Benue one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Benue State jobs today.'),

-- 8. Borno
('Borno', 'borno-state', 'Maiduguri', 'North East', 1,
 'Jobs in Borno State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Borno State. Find roles in NGOs, government, health, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Borno State — Home of Peace. Browse vacancies in NGOs, government, health, education, and agriculture with top employers in Maiduguri, Biu, and Gwoza. One of the most active bases for international humanitarian organisations in Nigeria, making Borno one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Borno State jobs today.'),

-- 9. Cross River
('Cross River', 'cross-river-state', 'Calabar', 'South South', 1,
 'Jobs in Cross River State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Cross River State. Find roles in tourism, hospitality, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Cross River State — The People\'s Paradise. Browse vacancies in tourism, hospitality, education, government, and agriculture with top employers in Calabar, Ikom, and Ogoja. Calabar is renowned as Nigeria\'s tourism capital and hosts major international carnivals, making Cross River one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Cross River State jobs today.'),

-- 10. Delta
('Delta', 'delta-state', 'Asaba', 'South South', 1,
 'Jobs in Delta State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Delta State. Find roles in oil and gas, manufacturing, banking, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Delta State — The Big Heart. Browse vacancies in oil and gas, manufacturing, banking, construction, and government with top employers in Asaba, Warri, Sapele, and Ughelli. Warri is a major hub for oil and gas operations in the Niger Delta region, making Delta one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Delta State jobs today.'),

-- 11. Ebonyi
('Ebonyi', 'ebonyi-state', 'Abakaliki', 'South East', 1,
 'Jobs in Ebonyi State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Ebonyi State. Find roles in agriculture, mining, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Ebonyi State — Salt of the Nation. Browse vacancies in agriculture, mining, education, government, and health with top employers in Abakaliki, Afikpo, and Onueke. Nigeria\'s leading rice-producing state and a growing centre for solid minerals, making Ebonyi one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Ebonyi State jobs today.'),

-- 12. Edo
('Edo', 'edo-state', 'Benin City', 'South South', 1,
 'Jobs in Edo State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Edo State. Find roles in oil and gas, rubber production, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Edo State — Heartbeat of the Nation. Browse vacancies in oil and gas, rubber production, education, health, and manufacturing with top employers in Benin City, Auchi, and Ekpoma. Home to Nigeria\'s oldest universities and a rich cultural and commercial heritage, making Edo one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Edo State jobs today.'),

-- 13. Ekiti
('Ekiti', 'ekiti-state', 'Ado-Ekiti', 'South West', 1,
 'Jobs in Ekiti State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Ekiti State. Find roles in education, government, health, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Ekiti State — Fountain of Knowledge. Browse vacancies in education, government, health, agriculture, and NGOs with top employers in Ado-Ekiti, Ikere-Ekiti, and Ijero-Ekiti. Widely regarded as Nigeria\'s most educated state per capita with a strong public sector, making Ekiti one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Ekiti State jobs today.'),

-- 14. Enugu
('Enugu', 'enugu-state', 'Enugu', 'South East', 1,
 'Jobs in Enugu State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Enugu State. Find roles in healthcare, education, banking, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Enugu State — Coal City State. Browse vacancies in healthcare, education, banking, manufacturing, and government with top employers in Enugu, Nsukka, and Oji River. A major commercial and educational hub in southeastern Nigeria with a thriving private sector, making Enugu one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Enugu State jobs today.'),

-- 15. FCT / Abuja
('FCT', 'fct-abuja', 'Abuja', 'North Central', 1,
 'Jobs in FCT (Abuja), Nigeria — Find Verified Vacancies',
 'Browse verified jobs in FCT Abuja. Find roles in government, banking, NGOs, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across FCT (Abuja) — Centre of Unity. Browse vacancies in government, banking, NGOs, telecoms, IT, real estate, and diplomacy with top employers in Garki, Wuse, Maitama, and Gwarinpa. Nigeria\'s capital and the seat of federal government, hosting embassies, multinationals, and development organisations, making FCT one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest FCT Abuja jobs today.'),

-- 16. Gombe
('Gombe', 'gombe-state', 'Gombe', 'North East', 1,
 'Jobs in Gombe State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Gombe State. Find roles in agriculture, government, health, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Gombe State — Jewel in the Savannah. Browse vacancies in agriculture, government, health, education, and NGOs with top employers in Gombe, Bajoga, and Kumo. A growing commercial centre serving as a gateway to the northeastern region, making Gombe one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Gombe State jobs today.'),

-- 17. Imo
('Imo', 'imo-state', 'Owerri', 'South East', 1,
 'Jobs in Imo State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Imo State. Find roles in oil and gas, education, trade, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Imo State — Eastern Heartland. Browse vacancies in oil and gas, education, trade, health, and government with top employers in Owerri, Orlu, and Okigwe. Owerri is one of southeastern Nigeria\'s fastest-growing cities with a vibrant hospitality scene, making Imo one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Imo State jobs today.'),

-- 18. Jigawa
('Jigawa', 'jigawa-state', 'Dutse', 'North West', 1,
 'Jobs in Jigawa State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Jigawa State. Find roles in agriculture, education, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Jigawa State — New World. Browse vacancies in agriculture, education, government, health, and commerce with top employers in Dutse, Hadejia, and Gumel. One of Nigeria\'s leading agricultural states with a strong public sector and growing NGO presence, making Jigawa one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Jigawa State jobs today.'),

-- 19. Kaduna
('Kaduna', 'kaduna-state', 'Kaduna', 'North West', 1,
 'Jobs in Kaduna State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Kaduna State. Find roles in manufacturing, banking, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Kaduna State — Centre of Learning. Browse vacancies in manufacturing, banking, education, government, and agriculture with top employers in Kaduna, Zaria, and Kafanchan. The industrial capital of northern Nigeria and home to Ahmadu Bello University — one of Africa\'s largest — making Kaduna one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Kaduna State jobs today.'),

-- 20. Kano
('Kano', 'kano-state', 'Kano', 'North West', 1,
 'Jobs in Kano State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Kano State. Find roles in trade, manufacturing, banking, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Kano State — Centre of Commerce. Browse vacancies in trade, manufacturing, banking, telecoms, education, and agriculture with top employers in Kano, Wudil, and Gwarzo. Nigeria\'s commercial capital in the north, with one of the highest concentrations of manufacturing and trading activity outside Lagos, making Kano one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Kano State jobs today.'),

-- 21. Katsina
('Katsina', 'katsina-state', 'Katsina', 'North West', 1,
 'Jobs in Katsina State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Katsina State. Find roles in agriculture, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Katsina State — Home of Hospitality. Browse vacancies in agriculture, government, education, health, and commerce with top employers in Katsina, Daura, and Funtua. A major agricultural state with a strong tradition of craftsmanship and growing investment in renewable energy, making Katsina one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Katsina State jobs today.'),

-- 22. Kebbi
('Kebbi', 'kebbi-state', 'Birnin Kebbi', 'North West', 1,
 'Jobs in Kebbi State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Kebbi State. Find roles in agriculture, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Kebbi State — Land of Equity. Browse vacancies in agriculture, government, education, health, and agribusiness with top employers in Birnin Kebbi, Argungu, and Yauri. Nigeria\'s largest rice-producing state, powering the Anchor Borrowers Programme and national food security efforts, making Kebbi one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Kebbi State jobs today.'),

-- 23. Kogi
('Kogi', 'kogi-state', 'Lokoja', 'North Central', 1,
 'Jobs in Kogi State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Kogi State. Find roles in solid minerals, agriculture, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Kogi State — Confluence State. Browse vacancies in solid minerals, agriculture, government, education, and health with top employers in Lokoja, Okene, and Kabba. Nigeria\'s confluence state where the Niger and Benue rivers meet, with significant solid minerals potential, making Kogi one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Kogi State jobs today.'),

-- 24. Kwara
('Kwara', 'kwara-state', 'Ilorin', 'North Central', 1,
 'Jobs in Kwara State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Kwara State. Find roles in education, government, commerce, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Kwara State — State of Harmony. Browse vacancies in education, government, commerce, health, and manufacturing with top employers in Ilorin and Offa. Ilorin is one of Nigeria\'s fastest-growing cities and a key educational and commercial hub bridging north and south, making Kwara one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Kwara State jobs today.'),

-- 25. Lagos
('Lagos', 'lagos-state', 'Ikeja', 'South West', 1,
 'Jobs in Lagos State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Lagos State. Find roles in fintech, banking, FMCG, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Lagos State — Centre of Excellence. Browse vacancies in fintech, banking, FMCG, media, telecoms, oil and gas, real estate, and e-commerce with top employers on Lagos Island, Victoria Island, Lekki, Ikeja, Surulere, and Yaba. Nigeria\'s commercial capital and Africa\'s largest city by GDP, hosting the largest concentration of jobs and multinational companies on the continent, making Lagos one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Lagos State jobs today.'),

-- 26. Nasarawa
('Nasarawa', 'nasarawa-state', 'Lafia', 'North Central', 1,
 'Jobs in Nasarawa State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Nasarawa State. Find roles in mining, agriculture, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Nasarawa State — Home of Solid Minerals. Browse vacancies in mining, agriculture, government, education, and construction with top employers in Lafia, Keffi, and Akwanga. Strategically located near the FCT and home to abundant solid mineral deposits driving growing investment, making Nasarawa one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Nasarawa State jobs today.'),

-- 27. Niger
('Niger', 'niger-state', 'Minna', 'North Central', 1,
 'Jobs in Niger State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Niger State. Find roles in power and energy, agriculture, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Niger State — Power State. Browse vacancies in power and energy, agriculture, government, construction, and education with top employers in Minna, Bida, and Suleja. Home to the Kainji and Shiroro hydroelectric dams that power much of Nigeria\'s national grid, making Niger State one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Niger State jobs today.'),

-- 28. Ogun
('Ogun', 'ogun-state', 'Abeokuta', 'South West', 1,
 'Jobs in Ogun State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Ogun State. Find roles in manufacturing, FMCG, construction, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Ogun State — Gateway State. Browse vacancies in manufacturing, FMCG, construction, education, and logistics with top employers in Abeokuta, Sagamu, Ijebu-Ode, and Ota. Nigeria\'s industrial heartland hosting hundreds of manufacturing plants in the Ota and Sagamu industrial corridors, making Ogun one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Ogun State jobs today.'),

-- 29. Ondo
('Ondo', 'ondo-state', 'Akure', 'South West', 1,
 'Jobs in Ondo State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Ondo State. Find roles in oil and gas, agriculture, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Ondo State — Sunshine State. Browse vacancies in oil and gas, agriculture, education, health, and government with top employers in Akure, Ondo, and Owo. Sits atop one of the world\'s largest bitumen deposits and is a leading cocoa-producing state in Nigeria, making Ondo one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Ondo State jobs today.'),

-- 30. Osun
('Osun', 'osun-state', 'Osogbo', 'South West', 1,
 'Jobs in Osun State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Osun State. Find roles in education, government, agriculture, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Osun State — State of the Living Spring. Browse vacancies in education, government, agriculture, commerce, and health with top employers in Osogbo, Ile-Ife, and Ilesa. Home to Obafemi Awolowo University and the UNESCO-listed Osun-Osogbo Sacred Grove, making Osun one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Osun State jobs today.'),

-- 31. Oyo
('Oyo', 'oyo-state', 'Ibadan', 'South West', 1,
 'Jobs in Oyo State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Oyo State. Find roles in education, banking, agriculture, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Oyo State — Pace Setter State. Browse vacancies in education, banking, agriculture, manufacturing, health, and government with top employers in Ibadan, Ogbomoso, and Oyo. Ibadan is one of Nigeria\'s largest cities and home to the University of Ibadan — Nigeria\'s oldest university — making Oyo one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Oyo State jobs today.'),

-- 32. Plateau
('Plateau', 'plateau-state', 'Jos', 'North Central', 1,
 'Jobs in Plateau State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Plateau State. Find roles in mining, agriculture, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Plateau State — Home of Peace and Tourism. Browse vacancies in mining, agriculture, government, NGOs, education, and health with top employers in Jos, Bukuru, and Pankshin. Nigeria\'s highland capital known for its temperate climate and significant tin mining history, making Plateau one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Plateau State jobs today.'),

-- 33. Rivers
('Rivers', 'rivers-state', 'Port Harcourt', 'South South', 1,
 'Jobs in Rivers State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Rivers State. Find roles in oil and gas, maritime, engineering, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Rivers State — Treasure Base of the Nation. Browse vacancies in oil and gas, maritime, engineering, construction, FMCG, and banking with top employers in Port Harcourt, Obio-Akpor, and Eleme. Nigeria\'s oil capital and the operational headquarters for most upstream and midstream oil and gas companies, making Rivers State one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Rivers State jobs today.'),

-- 34. Sokoto
('Sokoto', 'sokoto-state', 'Sokoto', 'North West', 1,
 'Jobs in Sokoto State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Sokoto State. Find roles in agriculture, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Sokoto State — Seat of the Caliphate. Browse vacancies in agriculture, government, education, health, and commerce with top employers in Sokoto, Tambuwal, and Wurno. The spiritual capital of northern Nigeria with significant livestock trade and a growing agribusiness sector, making Sokoto one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Sokoto State jobs today.'),

-- 35. Taraba
('Taraba', 'taraba-state', 'Jalingo', 'North East', 1,
 'Jobs in Taraba State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Taraba State. Find roles in agriculture, government, education, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Taraba State — Nature\'s Gift to the Nation. Browse vacancies in agriculture, government, education, NGOs, and health with top employers in Jalingo, Wukari, and Bali. One of Nigeria\'s most biodiverse states with vast agricultural land and a growing humanitarian sector presence, making Taraba one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Taraba State jobs today.'),

-- 36. Yobe
('Yobe', 'yobe-state', 'Damaturu', 'North East', 1,
 'Jobs in Yobe State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Yobe State. Find roles in government, agriculture, health, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Yobe State — Pride of the North. Browse vacancies in government, agriculture, health, NGOs, and education with top employers in Damaturu, Potiskum, and Gashua. An active base for international humanitarian and development organisations serving the Lake Chad region, making Yobe one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Yobe State jobs today.'),

-- 37. Zamfara
('Zamfara', 'zamfara-state', 'Gusau', 'North West', 1,
 'Jobs in Zamfara State, Nigeria — Find Verified Vacancies',
 'Browse verified jobs in Zamfara State. Find roles in solid minerals, agriculture, government, and more. Filter by salary, experience, and location.',
 'Find verified job opportunities across Zamfara State — Farming is Our Pride. Browse vacancies in solid minerals, agriculture, government, education, and health with top employers in Gusau, Kaura Namoda, and Talata Mafara. Nigeria\'s most significant artisanal gold-mining state with growing formal mining investment, making Zamfara one of Nigeria\'s key employment destinations. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest Zamfara State jobs today.')

ON DUPLICATE KEY UPDATE
    `slug`             = VALUES(`slug`),
    `capital`          = VALUES(`capital`),
    `region`           = VALUES(`region`),
    `is_active`        = VALUES(`is_active`),
    `seo_h1`           = VALUES(`seo_h1`),
    `meta_description` = VALUES(`meta_description`),
    `description`      = VALUES(`description`);

