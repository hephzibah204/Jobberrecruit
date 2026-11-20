<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LatestJobsSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        $employersData = [
            [
                'company_name' => 'Moniepoint Inc.',
                'tagline' => 'Powering digital payments & banking for African businesses',
                'website' => 'https://moniepoint.com',
                'state_id' => 25,
                'industry_id' => 14,
                'company_size' => '1000+',
                'description' => 'Moniepoint is Africa’s leading financial technology and digital banking platform providing all-in-one payment and banking solutions for businesses and individuals.',
            ],
            [
                'company_name' => 'Flutterwave',
                'tagline' => 'Endless possibilities for payments in Africa and beyond',
                'website' => 'https://flutterwave.com',
                'state_id' => 25,
                'industry_id' => 14,
                'company_size' => '500-1000',
                'description' => 'Flutterwave is a global payments technology company that helps businesses build customizable payment applications through its APIs.',
            ],
            [
                'company_name' => 'Interswitch Group',
                'tagline' => 'Africa’s leading integrated payments and digital commerce company',
                'website' => 'https://www.interswitchgroup.com',
                'state_id' => 25,
                'industry_id' => 14,
                'company_size' => '1000+',
                'description' => 'Interswitch is an Africa-focused integrated digital payments and commerce company that facilitates the electronic circulation of money.',
            ],
            [
                'company_name' => 'OPay Digital Services',
                'tagline' => 'Beyond banking: fast, secure and reliable transactions',
                'website' => 'https://www.opaycheckout.com',
                'state_id' => 39,
                'industry_id' => 14,
                'company_size' => '1000+',
                'description' => 'OPay is a leading fintech company offering consumer payments, POS merchant solutions, and smart financial services across Nigeria.',
            ],
            [
                'company_name' => 'Dangote Group',
                'tagline' => 'Providing your basic needs across Africa',
                'website' => 'https://www.dangote.com',
                'state_id' => 25,
                'industry_id' => 82,
                'company_size' => '10000+',
                'description' => 'Dangote Group is one of Africa’s largest and most diversified manufacturing conglomerates, spanning cement, sugar, fertilizer, and petroleum refining.',
            ],
            [
                'company_name' => 'MTN Nigeria',
                'tagline' => 'Everywhere you go — leading digital solutions for Africa’s progress',
                'website' => 'https://www.mtn.ng',
                'state_id' => 15,
                'industry_id' => 88,
                'company_size' => '5000+',
                'description' => 'MTN Nigeria is Africa’s largest telecommunications provider, driving digital transformation, 5G mobile networks, enterprise ICT, and fintech services.',
            ],
            [
                'company_name' => 'Access Bank Plc',
                'tagline' => 'Africa’s gateway to the world',
                'website' => 'https://www.accessbankplc.com',
                'state_id' => 39,
                'industry_id' => 76,
                'company_size' => '10000+',
                'description' => 'Access Bank Plc is a leading multinational commercial bank operating across Africa and international financial hubs.',
            ],
            [
                'company_name' => 'TotalEnergies EP Nigeria',
                'tagline' => 'Committed to better energy and sustainable exploration',
                'website' => 'https://totalenergies.com',
                'state_id' => 33,
                'industry_id' => 82,
                'company_size' => '1000+',
                'description' => 'TotalEnergies EP Nigeria is a broad energy company operating onshore and offshore exploration, production, and clean energy infrastructure.',
            ],
            [
                'company_name' => 'Nigeria LNG Limited (NLNG)',
                'tagline' => 'A globally competitive LNG company helping to build a better Nigeria',
                'website' => 'https://www.nlng.com',
                'state_id' => 33,
                'industry_id' => 82,
                'company_size' => '1000+',
                'description' => 'NLNG is a world-class liquefied natural gas (LNG) producer operating a six-train plant on Bonny Island in Rivers State.',
            ],
            [
                'company_name' => 'Deloitte Nigeria',
                'tagline' => 'Making an impact that matters',
                'website' => 'https://www2.deloitte.com/ng',
                'state_id' => 25,
                'industry_id' => 76,
                'company_size' => '1000+',
                'description' => 'Deloitte Nigeria provides industry-leading audit and assurance, consulting, financial advisory, risk advisory, and tax and legal services.',
            ],
            [
                'company_name' => 'KPMG Nigeria',
                'tagline' => 'Inspiring confidence and empowering change',
                'website' => 'https://kpmg.com/ng',
                'state_id' => 25,
                'industry_id' => 76,
                'company_size' => '1000+',
                'description' => 'KPMG Nigeria is a premier professional services firm delivering multidisciplinary audit, tax, technology, and financial advisory solutions.',
            ],
            [
                'company_name' => 'First City Monument Bank (FCMB)',
                'tagline' => 'My Bank and I — creating opportunities for collective growth',
                'website' => 'https://www.fcmb.com',
                'state_id' => 25,
                'industry_id' => 76,
                'company_size' => '5000+',
                'description' => 'FCMB is a full-service commercial banking and financial institution offering retail banking, digital lending, SME loans, and investment services.',
            ],
            [
                'company_name' => 'M-KOPA Nigeria',
                'tagline' => 'Financing progress for connected consumers',
                'website' => 'https://m-kopa.com',
                'state_id' => 28,
                'industry_id' => 96,
                'company_size' => '500-1000',
                'description' => 'M-KOPA combines digital micropayments with IoT connectivity to provide underbanked customers with flexible financing for everyday essentials.',
            ],
            [
                'company_name' => 'Smartflow Technologies',
                'tagline' => 'Leading automation, engineering & flow solutions in Africa',
                'website' => 'https://smartflowtech.com',
                'state_id' => 15,
                'industry_id' => 82,
                'company_size' => '200-500',
                'description' => 'Smartflow Technologies provides automation, flow measurement, calibration, and fueling systems across downstream and industrial sectors.',
            ],
            [
                'company_name' => 'Eden Solutions & Resources',
                'tagline' => 'Human capital and enterprise ERP solutions',
                'website' => 'https://edensr.com',
                'state_id' => 15,
                'industry_id' => 88,
                'company_size' => '50-100',
                'description' => 'Eden Solutions & Resources provides recruitment, management consulting, and open-source enterprise ERP solutions across Nigeria.',
            ],
            [
                'company_name' => 'Paystack',
                'tagline' => 'Modern online and offline payments for Africa',
                'website' => 'https://paystack.com',
                'state_id' => 40,
                'industry_id' => 14,
                'company_size' => '200-500',
                'description' => 'Paystack, a Stripe company, empowers thousands of organizations across Africa to accept payments from customers anywhere in the world.',
            ],
            [
                'company_name' => 'Piggyvest',
                'tagline' => 'The better way to save and invest online in Nigeria',
                'website' => 'https://www.piggyvest.com',
                'state_id' => 40,
                'industry_id' => 14,
                'company_size' => '100-200',
                'description' => 'Piggyvest is Nigeria’s first and largest automated digital savings and micro-investment platform, trusted by over 4 million Nigerians.',
            ],
            [
                'company_name' => 'Kuda Technologies',
                'tagline' => 'The money app for Africans everywhere',
                'website' => 'https://kuda.com',
                'state_id' => 40,
                'industry_id' => 14,
                'company_size' => '500-1000',
                'description' => 'Kuda is a digital neo-bank providing zero-fee retail banking, debit cards, business accounts, and cross-border money transfers.',
            ],
            [
                'company_name' => 'International Rescue Committee (IRC)',
                'tagline' => 'From harm to home — humanitarian aid and relief',
                'website' => 'https://www.rescue.org',
                'state_id' => 8,
                'industry_id' => 94,
                'company_size' => '1000+',
                'description' => 'The IRC responds to the world’s worst humanitarian crises and helps people to survive, recover, and gain control of their future.',
            ],
            [
                'company_name' => 'Nestlé Nigeria Plc',
                'tagline' => 'Good food, Good life',
                'website' => 'https://www.nestle-cwa.com',
                'state_id' => 28,
                'industry_id' => 91,
                'company_size' => '5000+',
                'description' => 'Nestlé Nigeria is one of Africa’s largest food and beverage manufacturers, producing trusted household brands like Maggi, Milo, Golden Morn, and Nescafé.',
            ],
        ];

        $logoDomainMap = [
            'Moniepoint Inc.' => 'https://www.google.com/s2/favicons?domain=moniepoint.com&sz=128',
            'Flutterwave' => 'https://www.google.com/s2/favicons?domain=flutterwave.com&sz=128',
            'Interswitch Group' => 'https://www.google.com/s2/favicons?domain=interswitchgroup.com&sz=128',
            'OPay Digital Services' => 'https://www.google.com/s2/favicons?domain=opaycheckout.com&sz=128',
            'Dangote Group' => 'https://www.google.com/s2/favicons?domain=dangote.com&sz=128',
            'MTN Nigeria' => 'https://www.google.com/s2/favicons?domain=mtn.ng&sz=128',
            'Access Bank Plc' => 'https://www.google.com/s2/favicons?domain=accessbankplc.com&sz=128',
            'TotalEnergies EP Nigeria' => 'https://www.google.com/s2/favicons?domain=totalenergies.com&sz=128',
            'Nigeria LNG Limited (NLNG)' => 'https://www.google.com/s2/favicons?domain=nlng.com&sz=128',
            'Deloitte Nigeria' => 'https://www.google.com/s2/favicons?domain=deloitte.com&sz=128',
            'KPMG Nigeria' => 'https://www.google.com/s2/favicons?domain=kpmg.com&sz=128',
            'First City Monument Bank (FCMB)' => 'https://www.google.com/s2/favicons?domain=fcmb.com&sz=128',
            'M-KOPA Nigeria' => 'https://www.google.com/s2/favicons?domain=m-kopa.com&sz=128',
            'Smartflow Technologies' => 'https://www.google.com/s2/favicons?domain=smartflowtech.com&sz=128',
            'Eden Solutions & Resources' => 'https://www.google.com/s2/favicons?domain=edensr.com&sz=128',
            'Paystack' => 'https://www.google.com/s2/favicons?domain=paystack.com&sz=128',
            'Piggyvest' => 'https://www.google.com/s2/favicons?domain=piggyvest.com&sz=128',
            'Kuda Technologies' => 'https://www.google.com/s2/favicons?domain=kuda.com&sz=128',
            'International Rescue Committee (IRC)' => 'https://www.google.com/s2/favicons?domain=rescue.org&sz=128',
            'Nestlé Nigeria Plc' => 'https://www.google.com/s2/favicons?domain=nestle.com&sz=128',
        ];

        $employerMap = [];
        foreach ($employersData as $emp) {
            $logoUrl = $logoDomainMap[$emp['company_name']] ?? null;
            if ($logoUrl) {
                $emp['logo'] = $logoUrl;
            }

            $existing = $db->table('employers')
                ->where('company_name', $emp['company_name'])
                ->get()
                ->getRowArray();

            if ($existing) {
                $employerMap[$emp['company_name']] = (int) $existing['id'];
                $updateData = [];
                if (!empty($logoUrl) && (empty($existing['logo']) || $existing['logo'] === 'images/favicon.png')) {
                    $updateData['logo'] = $logoUrl;
                }
                if (empty($existing['is_verified'])) {
                    $updateData['is_verified'] = 1;
                    $updateData['verification_status'] = 'approved';
                }
                if (!empty($updateData)) {
                    $db->table('employers')->where('id', $existing['id'])->update($updateData);
                }
            } else {
                $insertData = array_merge($emp, [
                    'user_id' => 2,
                    'is_verified' => 1,
                    'verification_status' => 'approved',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $db->table('employers')->insert($insertData);
                $employerMap[$emp['company_name']] = (int) $db->insertID();
            }
        }

        // Also ensure fallback employers have valid logos
        $db->table('employers')
            ->where('id', 1)
            ->where('logo', 'images/favicon.png')
            ->update(['logo' => 'https://www.google.com/s2/favicons?domain=nexusinnovations.com&sz=128', 'is_verified' => 1]);

        $db->table('employers')
            ->where('id', 17)
            ->where('logo', 'images/favicon.png')
            ->update(['logo' => 'images/logo.png', 'is_verified' => 1]);

        $jobsData = [
            [
                'company' => 'Moniepoint Inc.',
                'title' => 'Senior Site Reliability Engineer (SRE) — Payments Infrastructure',
                'category_id' => 1,
                'state_id' => 25,
                'industry_id' => 14,
                'job_type' => 'full-time',
                'location_type' => 'hybrid',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Senior Level (5+ yrs)',
                'skills' => 'Kubernetes, AWS, Terraform, Docker, Go, Python, Prometheus, Grafana, PostgreSQL',
                'description' => '<p>Moniepoint is looking for an experienced Senior Site Reliability Engineer (SRE) to ensure maximum uptime, resilience, and horizontal scalability across our payment gateways and point-of-sale (POS) terminal infrastructure processing millions of daily transactions.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Design, build, and operate resilient multi-region infrastructure on AWS and Kubernetes.</li>
<li>Establish and manage Service Level Indicators (SLIs), Service Level Objectives (SLOs), and Error Budgets.</li>
<li>Automate deployment pipelines and cloud infrastructure provisioning using Terraform and CI/CD tools.</li>
<li>Lead incident management, root cause analysis (RCA), and proactive observability using Prometheus and Grafana.</li>
<li>Collaborate with backend engineering teams to optimize high-throughput microservices and database queries.</li>
</ul>',
                'requirements' => '5+ years experience in Site Reliability Engineering or DevOps. Deep expertise in Kubernetes, Linux systems administration, and Terraform. Proficiency in Go, Python, or Shell scripting.',
                'application_method' => 'external',
                'external_url' => 'https://moniepoint.com/ng/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'Flutterwave',
                'title' => 'Senior Java Backend Engineer (Core Payments)',
                'category_id' => 1,
                'state_id' => 25,
                'industry_id' => 14,
                'job_type' => 'full-time',
                'location_type' => 'hybrid',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Senior Level (5+ yrs)',
                'skills' => 'Java, Spring Boot, Microservices, Kafka, Redis, PostgreSQL, REST APIs, Docker',
                'description' => '<p>Flutterwave is seeking a Senior Java Backend Engineer to join our Core Payments team in Lagos. You will design and implement resilient payment processing engines, fraud detection hooks, and banking gateway integrations that power pan-African and global transactions.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Architect, develop, and maintain high-performance, fault-tolerant microservices using Java and Spring Boot.</li>
<li>Design asynchronous event-driven pipelines using Apache Kafka for real-time transaction processing.</li>
<li>Ensure compliance with PCI-DSS standards, secure coding principles, and financial data isolation.</li>
<li>Collaborate with product designers and frontend teams to deliver developer-friendly public payment APIs.</li>
</ul>',
                'requirements' => 'Bachelor’s degree in Computer Science or equivalent practical experience. 5+ years building distributed Java applications with Spring Boot, SQL, and Kafka in high-scale environments.',
                'application_method' => 'external',
                'external_url' => 'https://flutterwave.com/ng/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'Interswitch Group',
                'title' => 'Africa Skills Acceleration Program (ASAP) Tech Trainee 2026',
                'category_id' => 1,
                'state_id' => 25,
                'industry_id' => 84,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '250000',
                'salary_details' => '₦250,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'skills' => 'Python, JavaScript, SQL, API Integration, Problem Solving, Git',
                'description' => '<p>Interswitch Group invites ambitious young graduates to join the Africa Skills Acceleration Program (ASAP) 2026. This intensive talent accelerator offers comprehensive hands-on training, executive mentorship, and direct placement into core engineering and fintech product teams.</p>
<h4>Program Highlights:</h4>
<ul>
<li>Structured 9-month immersive curriculum covering cloud computing, payment switching protocols, and modern software engineering.</li>
<li>Rotational projects across card switching, payment gateway development, and cybersecurity defense.</li>
<li>Direct mentorship from senior technical leads and industry innovators.</li>
<li>Guaranteed career progression into permanent engineering roles upon successful graduation.</li>
</ul>',
                'requirements' => 'Bachelor’s degree (minimum Second Class Upper) in Computer Science, Electrical/Electronic Engineering, Mathematics, or related STEM disciplines. Completion of NYSC not earlier than 2024.',
                'application_method' => 'external',
                'external_url' => 'https://www.interswitchgroup.com/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'OPay Digital Services',
                'title' => 'Regional Field Operations & POS Operations Lead',
                'category_id' => 3,
                'state_id' => 39,
                'industry_id' => 96,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '300000',
                'salary_details' => '₦300,000 / month',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Field Operations, Territory Management, Merchant Acquisition, POS Terminal Support, KPI Tracking',
                'description' => '<p>OPay is hiring a Regional Field Operations Lead to drive merchant expansion, agent banking acquisition, and POS terminal uptime across designated territories in Nigeria. You will manage local field teams, resolve merchant disputes, and expand transaction volume.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Lead regional deployment, activation, and continuous servicing of OPay Smart POS terminals.</li>
<li>Train, mentor, and supervise field sales officers and merchant support representatives.</li>
<li>Track daily transaction volume, terminal activity rates, and resolve merchant operational complaints.</li>
<li>Execute strategic market storming and agency banking partnership activations.</li>
</ul>',
                'requirements' => 'HND or Bachelor’s degree in Business Administration, Marketing, or related fields. 3+ years experience in agency banking operations, FMCG sales, or telecom distribution.',
                'application_method' => 'external',
                'external_url' => 'https://www.opaycheckout.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Dangote Group',
                'title' => 'Process Safety Engineer (Refinery & Petrochemicals Operations)',
                'category_id' => 4,
                'state_id' => 25,
                'industry_id' => 82,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Process Safety Management (PSM), HAZOP, Risk Assessment, Chemical Engineering, Plant Safety',
                'description' => '<p>Dangote Petroleum Refinery & Petrochemicals is seeking a qualified Process Safety Engineer at our 650,000 bpd refinery complex in Lekki, Lagos. You will oversee process safety management systems, hazard evaluations, and safe plant startup and continuous refining processes.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Facilitate Hazard and Operability (HAZOP) studies, What-If analyses, and Layer of Protection Analysis (LOPA).</li>
<li>Audit refinery operating units to verify compliance with OSHA PSM standards and local statutory requirements.</li>
<li>Investigate process safety incidents and near-misses, establishing corrective and preventative action plans.</li>
<li>Review piping and instrumentation diagrams (P&IDs) for process safety interlocks and relief system sizing.</li>
</ul>',
                'requirements' => 'B.Sc. or B.Eng. in Chemical or Petroleum Engineering. Minimum 4 years experience in process safety within crude oil refining, petrochemical processing, or upstream oil & gas facilities.',
                'application_method' => 'external',
                'external_url' => 'https://careers.dangote-group.com/',
                'is_featured' => 1,
            ],
            [
                'company' => 'MTN Nigeria',
                'title' => 'Core Network & Cloud Infrastructure Engineer',
                'category_id' => 1,
                'state_id' => 15,
                'industry_id' => 88,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => '5G Core, Packet Core, OpenStack, NFV, Cloud Native, IP Networking, Linux, Wireshark',
                'description' => '<p>MTN Nigeria is hiring a Core Network & Cloud Infrastructure Engineer in Abuja to manage next-generation packet core, cloud virtualization, and 5G network elements ensuring high-quality connectivity across Northern Nigeria.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Configure, integrate, and maintain Packet Core Network elements (SGW, PGW, SMF, UPF) and NFV infrastructure.</li>
<li>Monitor network KPIs, throughput latency, and perform capacity planning across 4G LTE and 5G networks.</li>
<li>Coordinate emergency maintenance, software upgrades, and patch deployments on telecom cloud platforms.</li>
<li>Troubleshoot packet core call flows and signaling traces using Wireshark and vendor diagnostic tools.</li>
</ul>',
                'requirements' => 'B.Sc. in Telecommunications Engineering, Computer Engineering, or related discipline. 3–5 years experience with mobile packet core, IP networking, and telco cloud infrastructure.',
                'application_method' => 'external',
                'external_url' => 'https://www.mtn.ng/careers/',
                'is_featured' => 0,
            ],
            [
                'company' => 'Access Bank Plc',
                'title' => 'Entry-Level Banking & Relationship Officer (ELTP 2026)',
                'category_id' => 2,
                'state_id' => 39,
                'industry_id' => 84,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '350000',
                'salary_details' => '₦350,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'skills' => 'Financial Analysis, Relationship Management, Customer Advisory, Retail Banking, Communication',
                'description' => '<p>Access Bank invites energetic, forward-thinking graduates across Nigeria to apply for our Entry Level Training Programme (ELTP) 2026. This prestigious program prepares future leaders for rewarding careers in retail banking, corporate finance, and commercial credit.</p>
<h4>Program Benefits:</h4>
<ul>
<li>World-class 4-month intensive training at the Access Banking School in Lagos.</li>
<li>Rotational postings across branches nationwide with direct exposure to credit appraisal and customer advisory.</li>
<li>Competitive compensation, comprehensive healthcare coverage, and structured career development.</li>
</ul>',
                'requirements' => 'Minimum of a Second Class Upper (2:1) degree from an accredited university in any discipline. Maximum age of 26 years (or 28 years for candidates with a Master’s degree). Completed NYSC.',
                'application_method' => 'external',
                'external_url' => 'https://www.accessbankplc.com/pages/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'TotalEnergies EP Nigeria',
                'title' => 'Offshore Production Maintenance Specialist',
                'category_id' => 4,
                'state_id' => 33,
                'industry_id' => 82,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Mechanical Maintenance, Pumps, Compressors, Rotating Equipment, Offshore Safety, SAP PM',
                'description' => '<p>TotalEnergies EP Nigeria is hiring an Offshore Production Maintenance Specialist based out of our Port Harcourt operational base. You will maintain production equipment integrity across our deepwater FPSO facilities.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Execute preventive and corrective maintenance on high-pressure gas compressors, export pumps, and turbines.</li>
<li>Participate in equipment troubleshooting, vibration analysis, and condition monitoring routines.</li>
<li>Log maintenance records, spare parts requisitions, and work orders using SAP Plant Maintenance (PM).</li>
<li>Ensure 100% adherence to TotalEnergies Golden Rules and international offshore safety standards.</li>
</ul>',
                'requirements' => 'HND or B.Eng in Mechanical or Marine Engineering. Minimum 4 years maintenance experience on offshore oil and gas production platforms or FPSOs. Valid BOSIET certification required.',
                'application_method' => 'external',
                'external_url' => 'https://totalenergies.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Nigeria LNG Limited (NLNG)',
                'title' => 'Plant Instrumentation & Control Systems Engineer',
                'category_id' => 4,
                'state_id' => 33,
                'industry_id' => 82,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Senior Level (5+ yrs)',
                'skills' => 'Distributed Control Systems (DCS), Safety Instrumented Systems (SIS), PLC, Fieldbus, Gas Processing',
                'description' => '<p>Nigeria LNG Limited (NLNG) is seeking an experienced Plant Instrumentation & Control Systems Engineer for our world-class gas liquefaction facility on Bonny Island, Rivers State.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Maintain and optimize Emerson and Honeywell Distributed Control Systems (DCS) across LNG trains.</li>
<li>Manage functional safety assessments, proof testing, and lifecycle maintenance for Safety Instrumented Systems (SIS).</li>
<li>Analyze instrumentation loop performance and oversee automated shutdown valve maintenance.</li>
<li>Lead control systems upgrades and modifications during scheduled turnaround maintenance.</li>
</ul>',
                'requirements' => 'Bachelor’s degree in Electrical/Electronic Engineering, Control Systems, or Automation. Minimum 6 years hands-on experience in instrumentation engineering in LNG, refinery, or petrochemical plants.',
                'application_method' => 'external',
                'external_url' => 'https://www.nlng.com/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'Deloitte Nigeria',
                'title' => 'Graduate Trainee Refresh 2026 — Tax, Legal & Consulting',
                'category_id' => 2,
                'state_id' => 25,
                'industry_id' => 84,
                'job_type' => 'full-time',
                'location_type' => 'hybrid',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '300000',
                'salary_details' => '₦300,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'skills' => 'Tax Compliance, Corporate Advisory, Financial Modeling, Analytical Skills, Research',
                'description' => '<p>Deloitte Nigeria invites applications from high-achieving Nigerian graduates for our 2026 Graduate Trainee Recruitment across our Tax, Regulatory Services, and Management Consulting practices in Lagos.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Support multi-disciplinary teams in delivering corporate tax compliance, advisory, and transfer pricing engagements.</li>
<li>Perform detailed financial modeling, industry benchmarking, and macroeconomic market research.</li>
<li>Assist clients in understanding statutory fiscal regulations, company tax filings, and transfer pricing rules.</li>
<li>Engage in continuous professional development towards ICAN, ACCA, or CITN certification.</li>
</ul>',
                'requirements' => 'First Degree (minimum 2:1) in Accounting, Economics, Finance, Law, or related commercial fields. Excellent analytical, numerical, and problem-solving skills. Completed NYSC with certificate of discharge.',
                'application_method' => 'external',
                'external_url' => 'https://www2.deloitte.com/ng/en/pages/careers/careers.html',
                'is_featured' => 0,
            ],
            [
                'company' => 'KPMG Nigeria',
                'title' => 'Senior Full-Stack Software Developer (KPMG Digital Lighthouse)',
                'category_id' => 1,
                'state_id' => 25,
                'industry_id' => 88,
                'job_type' => 'full-time',
                'location_type' => 'hybrid',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Senior Level (5+ yrs)',
                'skills' => 'React, Node.js, TypeScript, Azure, GraphQL, PostgreSQL, Microservices, CI/CD',
                'description' => '<p>KPMG Nigeria is hiring a Senior Full-Stack Software Developer to join our Digital Lighthouse technology consulting practice in Lagos. You will architect and build custom enterprise platforms and AI-powered solutions for top corporate and banking clients.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Develop responsive web portals and frontend user interfaces using modern React, Next.js, and TypeScript.</li>
<li>Build resilient backend REST/GraphQL microservices using Node.js and NestJS.</li>
<li>Deploy and monitor scalable cloud applications on Microsoft Azure using automated CI/CD pipelines.</li>
<li>Work alongside business analysts and client stakeholders to translate complex requirements into clean code.</li>
</ul>',
                'requirements' => 'Bachelor’s degree in Computer Science or related field. Minimum 5 years of professional full-stack development experience with modern JavaScript/TypeScript, relational databases, and cloud infrastructure.',
                'application_method' => 'external',
                'external_url' => 'https://kpmg.com/ng/en/home/careers.html',
                'is_featured' => 0,
            ],
            [
                'company' => 'First City Monument Bank (FCMB)',
                'title' => 'Early Career Technology Graduate Trainee 2026',
                'category_id' => 1,
                'state_id' => 25,
                'industry_id' => 84,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '220000',
                'salary_details' => '₦220,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'skills' => 'Data Analytics, Cybersecurity, SQL, Python, Digital Banking, Agile',
                'description' => '<p>FCMB is recruiting forward-thinking tech graduates for the 2026 Technology Graduate Programme. The programme provides targeted rotations across digital engineering, core banking applications, cybersecurity, and data analytics.</p>
<h4>What We Offer:</h4>
<ul>
<li>Comprehensive technical bootcamp covering core banking systems, cloud architecture, and cyber defense.</li>
<li>Direct placement with senior engineering mentors on high-impact digital banking products.</li>
<li>Competitive entry remuneration, health insurance, and structured promotion ladders.</li>
</ul>',
                'requirements' => 'Minimum 2:1 degree in Computer Science, Software Engineering, Information Technology, or Physics. Maximum age of 26 years at the time of application. Must have completed NYSC.',
                'application_method' => 'external',
                'external_url' => 'https://www.fcmb.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'M-KOPA Nigeria',
                'title' => 'Commercial Sales & Distribution Executive',
                'category_id' => 3,
                'state_id' => 28,
                'industry_id' => 96,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '180000',
                'salary_details' => '₦180,000 / month + Commissions',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Direct Sales, Channel Distribution, Team Leadership, Territory Mapping, Customer Acquisition',
                'description' => '<p>M-KOPA Nigeria is looking for a driven Commercial Sales & Distribution Executive in Ogun State to lead our field direct sales agents and expand asset-financing product distribution across retail channels.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Recruit, train, and manage high-performing direct sales teams across key municipal areas in Ogun State.</li>
<li>Achieve monthly sales targets for smartphones, solar products, and financing subscriptions.</li>
<li>Monitor customer onboarding, repayment literacy, and reduce early payment defaults.</li>
<li>Establish retail partnerships and manage local community marketing events.</li>
</ul>',
                'requirements' => 'B.Sc. or HND in Marketing, Business Administration, or related discipline. At least 2 years in retail sales management, telecom distribution, or microfinance field marketing.',
                'application_method' => 'external',
                'external_url' => 'https://m-kopa.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Smartflow Technologies',
                'title' => 'Industrial Automation & Flow Metering Sales Executive',
                'category_id' => 3,
                'state_id' => 15,
                'industry_id' => 96,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '250000',
                'salary_details' => '₦250,000 / month',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'B2B Sales, Industrial Automation, Downstream Oil & Gas, Flow Meters, Account Management',
                'description' => '<p>Smartflow Technologies is hiring a Technical Sales Executive in Abuja to drive business development for automated fueling systems, flow measurement solutions, and fuel management hardware across corporate clients and retail fuel stations.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Identify and secure commercial contracts with filling station chains, logistics fleets, and industrial manufacturing plants.</li>
<li>Deliver technical product presentations demonstrating ROI of automated wet-stock management systems.</li>
<li>Manage client relationships from initial quote through installation, calibration, and after-sales service.</li>
<li>Collaborate with field engineering teams to ensure prompt delivery and customer satisfaction.</li>
</ul>',
                'requirements' => 'Degree in Mechanical Engineering, Electrical Engineering, or Technical Sales. Minimum 3 years proven B2B sales experience in downstream petroleum equipment or industrial automation.',
                'application_method' => 'external',
                'external_url' => 'https://smartflowtech.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Eden Solutions & Resources',
                'title' => 'Enterprise Odoo ERP Developer',
                'category_id' => 1,
                'state_id' => 15,
                'industry_id' => 88,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '350000',
                'salary_details' => '₦350,000 / month',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Odoo ERP, Python, PostgreSQL, XML, JavaScript, API Integrations, Docker',
                'description' => '<p>Eden Solutions & Resources is recruiting an Enterprise Odoo ERP Developer in Abuja to customize, integrate, and deploy Odoo modules (Inventory, Accounting, HR, CRM) for institutional clients.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Develop custom Odoo modules and business workflows in Python and XML following Odoo best practices.</li>
<li>Design RESTful APIs and webhooks integrating Odoo with external payment gateways and third-party software.</li>
<li>Perform database optimizations and data migrations on PostgreSQL database instances.</li>
<li>Provide end-user technical training and resolve post-implementation bug tickets.</li>
</ul>',
                'requirements' => 'B.Sc. in Computer Science or Software Engineering. Minimum 3 years development experience with Odoo v15/v16/v17, Python, PostgreSQL, and Linux environments.',
                'application_method' => 'external',
                'external_url' => 'https://edensr.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Paystack',
                'title' => 'Technical Support Specialist (Remote — Nigeria)',
                'category_id' => 5,
                'state_id' => 40,
                'industry_id' => 14,
                'job_type' => 'full-time',
                'location_type' => 'remote',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Remote Compensation',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'APIs, Webhooks, Postman, SQL, JavaScript, Technical Troubleshooting, Customer Empathy',
                'description' => '<p>Paystack is seeking a Technical Support Specialist to work remotely anywhere in Nigeria. You will assist developers, merchants, and enterprise businesses to integrate and troubleshoot Paystack’s payment APIs, webhooks, and SDKs.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Debug failed API requests, webhook deliveries, and payment verification issues using logs and Postman.</li>
<li>Guide software developers across Africa in integrating Paystack libraries in PHP, Node.js, Python, and mobile SDKs.</li>
<li>Advocate for developer improvements by collaborating directly with engineering and product teams.</li>
<li>Maintain comprehensive developer documentation, FAQs, and integration guides.</li>
</ul>',
                'requirements' => 'Demonstrated ability to read and debug API code (JSON, REST, Webhooks). Prior experience in technical customer support or web development. Superb written English communication and troubleshooting skills.',
                'application_method' => 'external',
                'external_url' => 'https://paystack.com/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'Piggyvest',
                'title' => 'Content Strategist & Copywriter (Remote — Nigeria)',
                'category_id' => 14,
                'state_id' => 40,
                'industry_id' => 93,
                'job_type' => 'full-time',
                'location_type' => 'remote',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '300000',
                'salary_details' => '₦300,000 / month',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Copywriting, Content Strategy, Storytelling, Email Marketing, Financial Literacy, SEO Writing',
                'description' => '<p>Piggyvest is looking for a creative Content Strategist & Copywriter to work remotely from anywhere in Nigeria. You will write engaging newsletters, in-app microcopy, and financial literacy articles that empower millions of Nigerians to save and invest smartly.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Write clear, relatable, and culturally attuned copy for push notifications, email campaigns, and marketing landing pages.</li>
<li>Produce long-form personal finance guides and case studies for the Piggyvest blog and social channels.</li>
<li>Collaborate with product designers to create intuitive, human-centered UI copy across web and mobile apps.</li>
<li>Monitor campaign open rates, click-through rates, and audience engagement to continually refine messaging.</li>
</ul>',
                'requirements' => '3+ years experience writing engaging copy for digital consumer brands, media publications, or fintech products. Strong portfolio demonstrating creative storytelling and high financial literacy.',
                'application_method' => 'external',
                'external_url' => 'https://www.piggyvest.com/careers',
                'is_featured' => 0,
            ],
            [
                'company' => 'Kuda Technologies',
                'title' => 'Frontend Engineer (React / TypeScript) (Remote — Nigeria)',
                'category_id' => 1,
                'state_id' => 40,
                'industry_id' => 14,
                'job_type' => 'full-time',
                'location_type' => 'remote',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Remote Compensation',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'React, TypeScript, Next.js, Redux, TailwindCSS, Web Accessibility (a11y), Jest',
                'description' => '<p>Kuda is hiring a remote Frontend Engineer based in Nigeria to build fast, beautiful, and accessible web experiences for millions of retail and business banking customers across Africa and the UK.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Implement responsive web applications using React, Next.js, and strict TypeScript.</li>
<li>Ensure web accessibility (WCAG AA compliance) and cross-browser performance optimization.</li>
<li>Write comprehensive unit and integration tests using Jest and React Testing Library.</li>
<li>Partner with UI/UX designers and product managers in an agile sprint environment.</li>
</ul>',
                'requirements' => '3+ years experience building production web applications in React and TypeScript. Deep understanding of state management, DOM performance, web security, and RESTful APIs.',
                'application_method' => 'external',
                'external_url' => 'https://kuda.com/careers',
                'is_featured' => 1,
            ],
            [
                'company' => 'International Rescue Committee (IRC)',
                'title' => 'Health & Nutrition Field Officer',
                'category_id' => 7,
                'state_id' => 8,
                'industry_id' => 94,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'fixed',
                'salary_period' => 'monthly',
                'salary' => '450000',
                'salary_details' => '₦450,000 / month',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'Primary Healthcare, Nutrition Screening, Community Health, NGO Operations, Public Health Reporting',
                'description' => '<p>The International Rescue Committee (IRC) is seeking a Health & Nutrition Field Officer in Maiduguri, Borno State to oversee primary healthcare interventions and acute malnutrition management for crisis-affected communities.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Coordinate outpatient therapeutic programs (OTP) and targeted supplementary feeding clinics for vulnerable children and mothers.</li>
<li>Supervise community health workers and ensure quality clinical protocols in health facilities.</li>
<li>Track and report epidemiologic health data, drug supply balances, and program indicators.</li>
<li>Liaise with the State Ministry of Health and community stakeholders to maintain humanitarian access and coordination.</li>
</ul>',
                'requirements' => 'Degree in Nursing, Medicine, Public Health, or Human Nutrition. Valid license from relevant professional council. Minimum 3 years experience in public health or nutrition programming in humanitarian contexts.',
                'application_method' => 'external',
                'external_url' => 'https://rescue.csod.com/ux/ats/careersite/1/home?c=rescue',
                'is_featured' => 0,
            ],
            [
                'company' => 'Nestlé Nigeria Plc',
                'title' => 'Quality Assurance Specialist (Packaging & Food Safety)',
                'category_id' => 12,
                'state_id' => 28,
                'industry_id' => 91,
                'job_type' => 'full-time',
                'location_type' => 'on-site',
                'salary_type' => 'negotiable',
                'salary_period' => 'monthly',
                'salary' => 'Negotiable',
                'salary_details' => 'Competitive / Negotiable',
                'experience_level' => 'Mid Level (3–5 yrs)',
                'skills' => 'HACCP, ISO 22000, Food Chemistry, Quality Assurance, Packaging Inspection, Audit',
                'description' => '<p>Nestlé Nigeria Plc is seeking a Quality Assurance Specialist at our modern Agbara Manufacturing Facility in Ogun State to oversee food safety standards, raw material testing, and packaging compliance.</p>
<h4>Key Responsibilities:</h4>
<ul>
<li>Conduct chemical, physical, and sensory evaluations of raw materials, in-process goods, and finished packaging.</li>
<li>Enforce strict compliance with Good Manufacturing Practices (GMP), ISO 22000, and Nestlé Quality Management Systems (NQMS).</li>
<li>Lead internal quality audits, root cause analysis for packaging defects, and supplier quality verifications.</li>
<li>Coordinate factory pest management, hygiene inspections, and sanitation verification swabs.</li>
</ul>',
                'requirements' => 'B.Sc. in Food Science, Biochemistry, Industrial Chemistry, or Microbiology. Minimum 3 years QA/QC experience in a high-speed FMCG food manufacturing environment.',
                'application_method' => 'external',
                'external_url' => 'https://www.nestle-cwa.com/en/jobs',
                'is_featured' => 0,
            ],
        ];

        $jobModel = model('App\Models\JobModel');
        foreach ($jobsData as $jd) {
            $empId = $employerMap[$jd['company']] ?? 17;

            $exists = $db->table('jobs')
                ->where('title', $jd['title'])
                ->where('employer_id', $empId)
                ->get()
                ->getRowArray();

            if (!$exists) {
                $jobPayload = [
                    'employer_id' => $empId,
                    'category_id' => $jd['category_id'],
                    'state_id' => $jd['state_id'],
                    'industry_id' => $jd['industry_id'],
                    'title' => $jd['title'],
                    'description' => $jd['description'],
                    'job_type' => $jd['job_type'],
                    'location_type' => $jd['location_type'],
                    'salary_type' => $jd['salary_type'],
                    'salary_period' => $jd['salary_period'],
                    'salary' => $jd['salary'],
                    'salary_details' => $jd['salary_details'],
                    'experience_level' => $jd['experience_level'],
                    'skills' => $jd['skills'],
                    'requirements' => $jd['requirements'],
                    'application_method' => $jd['application_method'],
                    'external_url' => $jd['external_url'],
                    'application_deadline' => '2027-04-30',
                    'is_featured' => $jd['is_featured'],
                    'is_urgent' => 0,
                    'is_anonymous' => 0,
                    'admin_status' => 'approved',
                    'status' => 'open',
                    'is_verified' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $jobModel->insert($jobPayload);
            }
        }
    }
}
