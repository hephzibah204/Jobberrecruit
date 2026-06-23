-- MariaDB Migration for JobberRecruit AI Access Plan
-- Generated on 2026-06-02

-- 1. Insert the AI Monthly Access plan for candidates
INSERT INTO `plans` (`code`, `name`, `base_price`, `pricing_tiers`, `billing_type`, `plan_type`, `monthly_job_credits`, `features`, `is_active`, `created_at`, `updated_at`) 
VALUES (
    'ai_monthly_access', 
    'AI Monthly Access', 
    5000.00, 
    '{"1": {"duration": 30, "price": 5000}}', 
    'recurring', 
    'candidate', 
    0, 
    '{"ai_resume": true, "ai_career_tools": true, "ai_matching": true}', 
    1, 
    CURRENT_TIMESTAMP, 
    CURRENT_TIMESTAMP
);
