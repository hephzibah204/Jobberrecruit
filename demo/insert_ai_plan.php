<?php
$db = new SQLite3('writable/database.sqlite');
$db->exec("INSERT INTO plans (code, name, base_price, pricing_tiers, billing_type, plan_type, monthly_job_credits, features, is_active) VALUES ('ai_monthly_access', 'AI Monthly Access', 5000.00, '{\"1\": {\"duration\": 30, \"price\": 5000}}', 'recurring', 'candidate', 0, '{\"ai_resume\": true, \"ai_career_tools\": true, \"ai_matching\": true}', 1)");
echo "AI plan inserted into SQLite.";
