<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialTables extends Migration
{
    public function up()
    {
        // 1. Countries
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
            'iso_code' => ['type' => 'VARCHAR', 'constraint' => '10', 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('countries', true);

        // 2. States
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'country_id' => ['type' => 'INT', 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('country_id', 'countries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('states', true);

        // 3. Industries
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'parent_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('industries', true);

        // 4. Job Categories
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'parent_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('job_categories', true);

        // 5. Subscription Plans
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'duration' => ['type' => 'INT', 'default' => 30],
            'job_limit' => ['type' => 'INT', 'default' => 0],
            'featured_limit' => ['type' => 'INT', 'default' => 0],
            'billing_cycle' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'monthly'],
            'credit_allowance' => ['type' => 'INT', 'default' => 0],
            'discount_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subscription_plans', true);

        // 6. Plans
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'base_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'pricing_tiers' => ['type' => 'TEXT', 'null' => true],
            'billing_type' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'subscription'],
            'plan_type' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'subscription'],
            'monthly_job_credits' => ['type' => 'INT', 'default' => 0],
            'features' => ['type' => 'TEXT', 'null' => true],
            'paystack_plan_code' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('plans', true);

        // 7. Bundle Packages
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'credits' => ['type' => 'INT'],
            'cost_per_credit' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bundle_packages', true);

        // 8. Plan Bundles
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'job_credits' => ['type' => 'INT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'price_per_credit' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'is_best_value' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('plan_bundles', true);

        // 9. Pricing Rules
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'plan_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'bundle_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'currency' => ['type' => 'VARCHAR', 'constraint' => '10', 'default' => 'NGN'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pricing_rules', true);

        // 10. User Subscriptions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'plan_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'starts_at' => ['type' => 'DATETIME'],
            'ends_at' => ['type' => 'DATETIME'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'auto_renew' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_subscriptions', true);

        // 11. Wallets
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'balance' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'currency' => ['type' => 'VARCHAR', 'constraint' => '10', 'default' => 'NGN'],
            'is_locked' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wallets', true);

        // 12. Wallet Transactions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'wallet_id' => ['type' => 'INT', 'unsigned' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'type' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'description' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'reference' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('wallet_id', 'wallets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wallet_transactions', true);

        // 13. Job Credit Wallets
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'credits' => ['type' => 'INT', 'default' => 0],
            'source' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'reference_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_credit_wallets', true);

        // 14. Job Credit Transactions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'credits' => ['type' => 'INT'],
            'reference' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'meta' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_credit_transactions', true);

        // 15. Payments
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reference' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'metadata' => ['type' => 'TEXT', 'null' => true],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments', true);

        // 16. Employers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'company_name' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'logo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'industry_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'company_size' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'website' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'company_address' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'state_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'contact_name' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'contact_email' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'verification_doc' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'unlimited_access' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'unlimited_until' => ['type' => 'DATETIME', 'null' => true],
            'tin_number' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'is_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'verification_status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'verification_documents' => ['type' => 'TEXT', 'null' => true],
            'verification_notes' => ['type' => 'TEXT', 'null' => true],
            'verified_at' => ['type' => 'DATETIME', 'null' => true],
            'verified_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'rejection_reason' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('state_id', 'states', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('industry_id', 'industries', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('employers', true);

        // 17. Employer Documents
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'document_type' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'file_name' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'file_size' => ['type' => 'INT'],
            'mime_type' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'uploaded_at' => ['type' => 'DATETIME', 'null' => true],
            'verified_at' => ['type' => 'DATETIME', 'null' => true],
            'verified_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employer_documents', true);

        // 18. Employer Verification Logs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'admin_id' => ['type' => 'INT', 'unsigned' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employer_verification_logs', true);

        // 19. Job Seekers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'profile_picture' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'dob' => ['type' => 'DATE', 'null' => true],
            'gender' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'location' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'bio' => ['type' => 'TEXT', 'null' => true],
            'state_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'job_title' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'employment_type' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'skills' => ['type' => 'TEXT', 'null' => true],
            'experience_years' => ['type' => 'INT', 'null' => true],
            'education_level' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'languages' => ['type' => 'TEXT', 'null' => true],
            'resume' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'cover_letter' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'portfolio' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'desired_salary' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'salary_type' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'availability' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'is_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('state_id', 'states', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('job_seekers', true);

        // 20. Jobs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true],
            'state_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'description' => ['type' => 'TEXT'],
            'job_type' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'location_type' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'salary_type' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'salary_period' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'salary' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'salary_details' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'industry_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'education_level' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'experience_level' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'experience' => ['type' => 'TEXT', 'null' => true],
            'skills' => ['type' => 'TEXT', 'null' => true],
            'requirements' => ['type' => 'TEXT', 'null' => true],
            'application_method' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'application_access' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'whatsapp_link' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'application_email' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'external_url' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'application_deadline' => ['type' => 'DATE', 'null' => true],
            'start_date' => ['type' => 'DATE', 'null' => true],
            'contact_email' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'application' => ['type' => 'TEXT', 'null' => true],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'featured_until' => ['type' => 'DATETIME', 'null' => true],
            'is_anonymous' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'network_blast' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'views' => ['type' => 'INT', 'default' => 0],
            'accommodation' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'notification_preferences' => ['type' => 'TEXT', 'null' => true],
            'admin_status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'admin_reviewed_at' => ['type' => 'DATETIME', 'null' => true],
            'admin_notes' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'open'],
            'is_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'job_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('state_id', 'states', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('industry_id', 'industries', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('jobs', true);

        // 21. Job Applications
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'cv_path' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'cover_letter' => ['type' => 'TEXT', 'null' => true],
            'availability' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'salary_expectation' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'work_eligibility' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'consent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'status_message' => ['type' => 'TEXT', 'null' => true],
            'is_guest' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'guest_email_sent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_applications', true);

        // 22. Job Questions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'question_text' => ['type' => 'TEXT'],
            'question_type' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'text'],
            'is_required' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_questions', true);

        // 23. Job Application Answers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true],
            'question_id' => ['type' => 'INT', 'unsigned' => true],
            'answer_text' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'job_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_application_answers', true);

        // 24. Application Notes
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'note' => ['type' => 'TEXT'],
            'type' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'internal'],
            'created_by' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_notes', true);

        // 25. Job Notifications
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'message' => ['type' => 'TEXT'],
            'is_read' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'read_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_notifications', true);

        // 26. Job Clicks
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => '45', 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_clicks', true);

        // 27. Saved Jobs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('saved_jobs', true);

        // 28. Job Alerts
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'keyword' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'location_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'frequency' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'daily'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'states', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('job_alerts', true);

        // 29. Admins
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
            'role' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'super_admin'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('admins', true);

        // 30. Blogs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'admin_id' => ['type' => 'INT', 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '155'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '155', 'unique' => true],
            'content' => ['type' => 'TEXT'],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => '155', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'draft'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('admin_id', 'admins', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('blogs', true);

        // 31. Employer Industries
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'industry_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('industry_id', 'industries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employer_industries', true);

        // 32. Job Seeker Industries
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'industry_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('industry_id', 'industries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_seeker_industries', true);

        // 33. Job Seeker Categories
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'job_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_seeker_categories', true);

        // 34. Testimonials
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'content' => ['type' => 'TEXT'],
            'rating' => ['type' => 'TINYINT', 'default' => 5],
            'is_published' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('testimonials', true);

        // 35. Password Resets
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'token' => ['type' => 'VARCHAR', 'constraint' => '64'],
            'expires_at' => ['type' => 'DATETIME'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('password_resets', true);

        // 36. Resumes
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'summary' => ['type' => 'TEXT', 'null' => true],
            'template_id' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'classic'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resumes', true);

        // 37. Resume Experiences
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'resume_id' => ['type' => 'INT', 'unsigned' => true],
            'company' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'position' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'start_date' => ['type' => 'DATE'],
            'end_date' => ['type' => 'DATE', 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_current' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('resume_id', 'resumes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resume_experiences', true);

        // 38. Resume Education
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'resume_id' => ['type' => 'INT', 'unsigned' => true],
            'institution' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'degree' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'field_of_study' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'graduation_date' => ['type' => 'DATE', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('resume_id', 'resumes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resume_education', true);

        // 39. Resume Skills
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'resume_id' => ['type' => 'INT', 'unsigned' => true],
            'skill_name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'proficiency_level' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'intermediate'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('resume_id', 'resumes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resume_skills', true);

        // 40. Referrals
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'referrer_id' => ['type' => 'INT', 'unsigned' => true],
            'referred_id' => ['type' => 'INT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'reward_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'reward_paid' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('referrer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('referred_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('referrals', true);

        // 41. Newsletters
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'subject' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'content' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'draft'],
            'sent_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('newsletters', true);

        // 42. Webinars
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'description' => ['type' => 'TEXT'],
            'speaker_name' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'speaker_bio' => ['type' => 'TEXT', 'null' => true],
            'scheduled_at' => ['type' => 'DATETIME'],
            'duration' => ['type' => 'INT', 'null' => true], // minutes
            'meeting_link' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'upcoming'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('webinars', true);

        // 43. Webinar Registrations
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'webinar_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('webinar_id', 'webinars', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('webinar_registrations', true);

        // 44. Newsletter Subscribers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('newsletter_subscribers', true);

        // 45. Job Reports
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reason' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'details' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_reports', true);

        // 46. Courses
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'description' => ['type' => 'TEXT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'instructor' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('courses', true);

        // 47. Course Enrollments
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'course_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'payment_reference' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'enrolled'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_enrollments', true);

        // 48. Queue Jobs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'queue' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'default'],
            'payload' => ['type' => 'LONGTEXT'],
            'attempts' => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 0],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'pending'],
            'error' => ['type' => 'TEXT', 'null' => true],
            'available_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('queue_jobs', true);

        // 49. Email Verifications
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'token' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'expires_at' => ['type' => 'DATETIME'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('email_verifications', true);

        // 50. Application References
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_references', true);

        // 51. Application Answers (Alternative Table)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true],
            'question_id' => ['type' => 'INT', 'unsigned' => true],
            'answer' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_answers', true);
    }

    public function down()
    {
        $this->forge->dropTable('application_answers', true);
        $this->forge->dropTable('application_references', true);
        $this->forge->dropTable('email_verifications', true);
        $this->forge->dropTable('queue_jobs', true);
        $this->forge->dropTable('course_enrollments', true);
        $this->forge->dropTable('courses', true);
        $this->forge->dropTable('job_reports', true);
        $this->forge->dropTable('newsletter_subscribers', true);
        $this->forge->dropTable('webinar_registrations', true);
        $this->forge->dropTable('webinars', true);
        $this->forge->dropTable('newsletters', true);
        $this->forge->dropTable('referrals', true);
        $this->forge->dropTable('resume_skills', true);
        $this->forge->dropTable('resume_education', true);
        $this->forge->dropTable('resume_experiences', true);
        $this->forge->dropTable('resumes', true);
        $this->forge->dropTable('password_resets', true);
        $this->forge->dropTable('testimonials', true);
        $this->forge->dropTable('job_seeker_categories', true);
        $this->forge->dropTable('job_seeker_industries', true);
        $this->forge->dropTable('employer_industries', true);
        $this->forge->dropTable('blogs', true);
        $this->forge->dropTable('admins', true);
        $this->forge->dropTable('job_alerts', true);
        $this->forge->dropTable('saved_jobs', true);
        $this->forge->dropTable('job_clicks', true);
        $this->forge->dropTable('job_notifications', true);
        $this->forge->dropTable('application_notes', true);
        $this->forge->dropTable('job_application_answers', true);
        $this->forge->dropTable('job_questions', true);
        $this->forge->dropTable('job_applications', true);
        $this->forge->dropTable('jobs', true);
        $this->forge->dropTable('job_seekers', true);
        $this->forge->dropTable('employer_verification_logs', true);
        $this->forge->dropTable('employer_documents', true);
        $this->forge->dropTable('employers', true);
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('job_credit_transactions', true);
        $this->forge->dropTable('job_credit_wallets', true);
        $this->forge->dropTable('wallet_transactions', true);
        $this->forge->dropTable('wallets', true);
        $this->forge->dropTable('user_subscriptions', true);
        $this->forge->dropTable('pricing_rules', true);
        $this->forge->dropTable('plan_bundles', true);
        $this->forge->dropTable('bundle_packages', true);
        $this->forge->dropTable('plans', true);
        $this->forge->dropTable('subscription_plans', true);
        $this->forge->dropTable('job_categories', true);
        $this->forge->dropTable('industries', true);
        $this->forge->dropTable('states', true);
        $this->forge->dropTable('countries', true);
    }
}
