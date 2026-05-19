<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('uploads/(:segment)/(:num)/(:any)', 'FileController::serve/$1/$2/$3');
// // Language switcher
// $routes->get('lang/(:segment)', 'Language::switch/$1');
// $routes->get('ping', function () {
//     return response()->setStatusCode(200)->setBody('OK');
// });

$routes->get('/', 'Home::home');
$routes->get('jobs', 'Home::jobs');
$routes->get('jobs-in-(:segment)', 'Home::location_hub/$1');
$routes->get('(:segment)-jobs', 'Home::industry_hub/$1');
$routes->get('jobs/(:segment)', 'Home::view_job/$1');
$routes->get('job/view/(:any)', 'Home::view_job/$1');
$routes->get('job/start-application/(:num)', 'Home::startApplication/$1');
$routes->get('job/application/(:num)', 'Home::apply_job/$1');
$routes->post('job/application/(:num)', 'Home::apply_job/$1');
$routes->get('job/applied/(:num)', 'Home::applied/$1');
$routes->post('jobs/toggle-save/(:num)', 'Home::toggleSave/$1');
$routes->post('job/unsave/(:num)', 'Home::unsave_job/$1');
$routes->get('employer/(:num)', 'Home::viewCompany/$1');
$routes->get('candidates', 'Home::talents');

$routes->get('jobs/featured', 'Home::featuredJobs');

$routes->get('about-us', 'Home::aboutUs');
$routes->get('contact-us', 'Home::contactUs');
$routes->post('contact-us', 'Home::contactUs');
$routes->get('blog', 'Home::blogs');
$routes->get('blog/(:segment)', 'Home::blogPost/$1');
// RSS
$routes->get('rss/blog', 'Home::rss');
$routes->get('privacy-policy', 'Home::privacyPolicy');
$routes->get('terms-and-conditions', 'Home::termsOfService');
$routes->get('faq', 'Home::faq');
$routes->get('recruitment', 'Home::recruitment');
$routes->get('job-ads', 'Home::adPage');

// -----------------------------------------------------------
// AUTH ROUTES
// -----------------------------------------------------------

// Login
$routes->addRedirect('signin', 'login', 301);
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');

// Registration
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::register');

// Forgot & Reset Password
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password/send', 'AuthController::forgotPassword');

$routes->get('auth/reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('auth/reset-password/(:segment)', 'AuthController::resetPassword/$1');

// -----------------------------------------------------------
// SOCIAL LOGINS
// -----------------------------------------------------------

// LinkedIn OAuth
$routes->get('auth/linkedin', 'AuthController::linkedinLogin');
$routes->get('auth/linkedin/callback', 'AuthController::linkedinCallback');

// Google OAuth
$routes->get('auth/google', 'AuthController::googleLogin');
$routes->get('auth/google/callback', 'AuthController::googleCallback');

// -----------------------------------------------------------
// SOCIAL ONBOARDING ("Resume where user left off")
// -----------------------------------------------------------
$routes->get('auth/social/complete', 'AuthController::socialComplete');
$routes->post('auth/social/finalize', 'AuthController::socialFinalize');

// -----------------------------------------------------------
// EMAIL VERIFICATION (Hybrid + Token + Resend)
// -----------------------------------------------------------

// Hybrid verification page (logged-in or logged-out)
$routes->get('auth/verify-email', 'AuthController::verifyEmailPage');

// Token-based verification (Option B: auto-login after verifying)
$routes->get('auth/verify-email/(:segment)', 'AuthController::verifyEmailToken/$1');

// Resend verification email
$routes->get('auth/resend-verification', 'AuthController::resendVerification');



$routes->get('dashboard', 'EmployerController::dashboard', ['filter' => 'auth']);
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

// Employer Routes
$routes->group('employer', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'EmployerController::dashboard');
    $routes->get('dashboard', 'EmployerController::dashboard');

    // No access
    $routes->get('no-access', 'EmployerController::no_access');
    // Jobs
    $routes->get('jobs', 'EmployerController::myJobs');
    // $routes->get('jobs/view/(:num)', 'EmployerController::job_detail/$1');
    $routes->post('jobs/delete/(:num)', 'EmployerController::deleteJob/$1');
    $routes->get('jobs/edit/(:num)', 'EmployerController::editJob/$1');
    $routes->post('jobs/update', 'EmployerController::updateJob');
    $routes->get('post-job', 'EmployerController::post_job');
    $routes->post('post-job', 'EmployerController::post_job');

    $routes->post('jobs/promote/(:num)', 'EmployerController::promoteJob/$1');
    $routes->get('jobs/view/(:num)', 'EmployerController::viewJob/$1');
    $routes->post('jobs/feature/(:num)', 'EmployerController::featureJob/$1');

    $routes->post('jobs/stop-featured/(:num)', 'EmployerController::stopFeatured/$1');
    $routes->post('jobs/toggle-anonymous/(:num)', 'EmployerController::toggleAnonymous/$1');

    // Applications
    $routes->get('applications', 'EmployerController::applications');
    $routes->get('applications/view/(:num)', 'EmployerController::viewApplication/$1');
    $routes->post('applications/update-status-2', 'EmployerController::updateApplicationStatus2');
    $routes->post('applications/delete/(:num)', 'EmployerController::deleteApplication/$1');
    $routes->get('applications/export', 'EmployerController::exportApplications');
    $routes->post('applications/bulk-update-status', 'EmployerController::bulkUpdateApplicationStatus');
    $routes->post('applications/bulk-delete', 'EmployerController::bulkDeleteApplications');

    $routes->post('applications/add-note', 'EmployerController::addApplicationNote');
    $routes->post('applications/delete-note/(:num)', 'EmployerController::deleteApplicationNote/$1');

    $routes->post('applications/update-status', 'EmployerController::updateApplicationStatus');

    // Profile
    $routes->get('profile', 'EmployerController::profile');
    $routes->get('profile/edit', 'EmployerController::edit_profile');
    $routes->post('profile/edit', 'EmployerController::edit_profile');
    $routes->get('profile/upload-document', 'EmployerController::upload_document');
    $routes->post('profile/process-document-upload', 'EmployerController::process_document_upload');
    // $routes->post('profile/update/(:num)', 'EmployerController::update_profile/$1');

    // Security
    $routes->get('settings/security', 'EmployerController::security');
    // $routes->get('security/edit', 'EmployerController::edit_security');
    $routes->post('settings/security/change-password', 'EmployerController::changePassword');

    // Pricing
    $routes->get('bundles', 'EmployerController::bundles');
    $routes->post('bundles/buy/(:segment)', 'EmployerController::checkoutBundle/$1');
    $routes->post('bundles/payments/verify', 'EmployerController::verifyPaystackBundlePayment');

    $routes->get('pricing', 'EmployerController::pricing');
    $routes->post('initiate-payment', 'EmployerController::initiate_payment');
    $routes->get('verify-payment', 'EmployerController::verify_payment');
    
    $routes->post('checkout-subscription/(:num)', 'EmployerController::checkoutSubscription/$1');
    $routes->post('verify-subscription', 'EmployerController::verifyPaystackSubscription');

    $routes->get('pricing/cancel', 'EmployerController::cancelSubscription');
    $routes->get('pricing/reactivate', 'EmployerController::reactivateSubscription');

    $routes->post('pricing/process-ajax', 'EmployerController::processCheckoutAjax');
    $routes->post('pricing/verify-ajax', 'EmployerController::verifyAjax');
    // optional contact sales
    $routes->get('contact/sales', 'ContactController::sales', ['as' => 'contact.sales']);

    $routes->get('pricing/verify', 'EmployerController::verify'); // callback URL

    $routes->get('notifications', 'EmployerController::notifications');
    $routes->post('notifications/mark-read', 'EmployerController::markNotificationRead');
    $routes->post('notifications/mark-all-read', 'EmployerController::markAllNotificationsRead');
    $routes->post('notifications/delete', 'EmployerController::deleteNotification');

    // Candidate Search (Paid Feature)
    $routes->get('candidates', 'EmployerController::candidates');
    $routes->get('candidates/filter', 'EmployerController::filterCandidates');
    $routes->get('candidates/view/(:num)', 'EmployerController::viewCandidate/$1');
    $routes->post('candidates/unlock', 'EmployerController::unlockCandidate');

    // Messaging
    $routes->get('messages', 'MessageController::inbox');
    $routes->get('messages/conversation/(:num)', 'MessageController::conversation/$1');
    $routes->post('messages/send', 'MessageController::send');
    $routes->post('messages/start', 'MessageController::startConversation');

    // GDPR Data Export
    $routes->get('settings/export-data', 'EmployerController::exportData');

    // Referrals
    $routes->get('referrals', 'ReferralController::index');
});

// Candidate
$routes->group('candidate', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'JobSeekerController::dashboard');
    $routes->get('dashboard', 'JobSeekerController::dashboard');
    $routes->get('applications', 'JobSeekerController::applications');
    $routes->get('applications/view/(:num)', 'JobSeekerController::viewApplication/$1');

    $routes->get('notifications', 'JobSeekerController::notifications');
    $routes->get('saved-jobs', 'JobSeekerController::savedJobs');
    $routes->post('alerts/save', 'JobSeekerController::saveAlert');
    $routes->post('alerts/delete/(:num)', 'JobSeekerController::deleteAlert/$1');

    // Profile
    $routes->get('profile', 'JobSeekerController::profile');
    $routes->get('profile/edit', 'JobSeekerController::edit_profile');
    $routes->post('profile/edit', 'JobSeekerController::edit_profile');
    $routes->get('settings/security', 'JobSeekerController::security');
    // $routes->get('security/edit', 'JobSeekerController::edit_security');
    $routes->post('settings/security/change-password', 'JobSeekerController::changePassword');
    // $routes->post('profile/update/(:num)', 'JobSeekerController::update_profile/$1');

    // Resume Builder
    $routes->get('resumes', 'ResumeController::index');
    $routes->get('resumes/build', 'ResumeController::build');
    $routes->get('resumes/build/(:num)', 'ResumeController::build/$1');
    $routes->post('resumes/save', 'ResumeController::save');
    $routes->post('resumes/ai/generate-summary', 'ResumeController::generateSummary');
    $routes->post('resumes/ai/improve-description', 'ResumeController::improveDescription');
    $routes->post('resumes/ai/generate-cover-letter', 'ResumeController::generateCoverLetter');
    $routes->get('resumes/download/(:num)', 'ResumeController::download/$1');

    // Referrals
    $routes->get('referrals', 'ReferralController::index');

    // Messaging
    $routes->get('messages', 'MessageController::inbox');
    $routes->get('messages/conversation/(:num)', 'MessageController::conversation/$1');
    $routes->post('messages/send', 'MessageController::send');

    // GDPR Data Export
    $routes->get('settings/export-data', 'JobSeekerController::exportData');

    // Candidate Subscription
    $routes->get('subscription/pricing', 'CandidateSubscriptionController::pricing');
    $routes->post('subscription/checkout', 'CandidateSubscriptionController::checkout');
    $routes->get('subscription/verify', 'CandidateSubscriptionController::verify');

    // AI Career Tools
    $routes->group('career-tools', function ($routes) {
        $routes->get('', 'CareerToolsController::index');
        $routes->get('mock-interview', 'CareerToolsController::mockInterview');
        $routes->get('salary-negotiation', 'CareerToolsController::salaryNegotiation');
        $routes->get('career-advice', 'CareerToolsController::careerAdvice');
        $routes->post('send-message', 'CareerToolsController::sendMessage');
        $routes->post('evaluate-interview', 'CareerToolsController::evaluateInterview');
    });
});


$routes->get('track/open/(:num)', 'Home::trackOpen/$1');
$routes->get('track/click/(:num)', 'Home::trackClick/$1');

$routes->post('pricing/webhook', 'EmployerController::webhook'); // webhook endpoint (no CSRF)


// Admin
$routes->get('admin/login', 'AdminController::login');
$routes->post('admin/login', 'AdminController::login');
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('', 'AdminController::index');
    // Logout
    $routes->get('logout', 'AdminController::logout');
    $routes->get('dashboard', 'AdminController::index');
    $routes->post('theme/toggle', 'AdminController::toggleTheme');
    $routes->get('users', 'AdminController::index');
    $routes->get('settings', 'AdminController::index');

    $routes->get('profile', 'AdminController::profile');
    $routes->post('profile/update', 'AdminController::updateProfile');
    $routes->post('profile/change-password', 'AdminController::changePassword');

    // Jobs
    $routes->get('locations', 'AdminController::locations');
    $routes->post('locations/save', 'AdminController::locations');
    $routes->post('locations/delete/(:num)', 'AdminController::deleteLocation/$1');
    $routes->post('locations/toggleStatus', 'AdminController::toggleLocationStatus');
    $routes->post('locations/bulkAction', 'AdminController::locationBulkAction');
    $routes->get('categories', 'AdminController::categories');
    $routes->post('categories/save', 'AdminController::categories');
    $routes->post('categories/delete/(:num)', 'AdminController::deleteCategory/$1');
    $routes->post('categories/toggleStatus', 'AdminController::toggleCategoryStatus');
    $routes->post('categories/bulkAction', 'AdminController::categoryBulkAction');
    $routes->get('industries', 'AdminController::industries');
    $routes->post('industries/save', 'AdminController::industries');
    $routes->post('industries/delete/(:num)', 'AdminController::deleteIndustry/$1');
    $routes->post('industries/toggleStatus', 'AdminController::toggleIndustryStatus');
    $routes->post('industries/bulkAction', 'AdminController::industryBulkAction');
    
    $routes->get('jobs', 'AdminController::jobs');
    $routes->get('jobs/filter', 'AdminController::filterJobs');
    $routes->post('jobs/update-status/(:num)', 'AdminController::updateStatus/$1');
    $routes->post('jobs/delete', 'AdminController::deleteJob');
    $routes->get('jobs/view/(:num)', 'AdminController::viewJob/$1');
    $routes->get('jobs/performance/(:num)', 'AdminController::performanceChart/$1');
    $routes->post('jobs/approve', 'AdminController::approveJob');
    $routes->post('jobs/reject', 'AdminController::rejectJob');

    $routes->get('jobs/edit/(:num)', 'AdminController::editJob/$1');
    $routes->post('jobs/update/(:num)', 'AdminController::updateJob/$1');

    $routes->post('jobs/delete', 'AdminController::deleteJob');
    // Applications
    $routes->get('applications', 'AdminController::applications');
    $routes->get('applications/view/(:num)', 'AdminController::viewApplication/$1');
    // Candidates
    $routes->get('candidates', 'AdminController::candidates');
    $routes->get('candidates/filter', 'AdminController::filterCandidates');
    $routes->get('candidates/view/(:num)', 'AdminController::viewCandidate/$1');
    $routes->post('candidates/delete/(:num)', 'AdminController::deleteCandidate/$1');
    $routes->post('candidates/bulk-delete', 'AdminController::bulkDeleteCandidates');
    // Employers
    $routes->get('employers', 'AdminController::employers');
    $routes->get('employers/filter', 'AdminController::filterEmployers');
    $routes->get('employers/view/(:num)', 'AdminController::viewEmployer/$1');
    $routes->get('employers/documents/(:num)', 'AdminController::viewEmployerDocuments/$1');
    $routes->post('documents/verify/(:num)', 'AdminController::verifyDocument/$1');
    $routes->post('documents/reject/(:num)', 'AdminController::rejectDocument/$1');
    $routes->post('employers/verify/(:num)', 'AdminController::verifyEmployer/$1');
    $routes->post('employers/reject/(:num)', 'AdminController::rejectEmployer/$1');
    $routes->post('employers/delete/(:num)', 'AdminController::deleteEmployer/$1');
    $routes->post('employers/bulk-delete', 'AdminController::bulkDeleteEmployers');

    $routes->post('employers/toggle-unlimited-access', 'AdminController::toggleUnlimitedAccess');
    $routes->post('employers/update-unlimited-expiry', 'AdminController::updateUnlimitedExpiry');
    // Pricing
    $routes->get('bundles', 'AdminController::bundles');
    $routes->post('bundles', 'AdminController::bundles');
    $routes->get('plans', 'AdminController::plans');
    $routes->post('plans', 'AdminController::plans');
    $routes->get('plans/assign', 'AdminController::assignPlan');
    $routes->post('plans/assign', 'AdminController::assignPlan');
    $routes->post('plans/delete/(:num)', 'AdminController::deletePlan/$1');
    $routes->post('plans/grant-unlimited-access', 'AdminController::grantUnlimitedAccess');
    $routes->post('plans/revoke-unlimited-access', 'AdminController::revokeUnlimitedAccess');
    $routes->post('plans/toggle-free-mode', 'AdminController::toggleFreeMode');


    // Blog
    $routes->get('blogs', 'AdminController::blogs');
    $routes->post('blogs/save', 'AdminController::saveBlog');
    $routes->get('blogs/preview/(:segment)', 'AdminController::previewBlog/$1');
    $routes->post('blogs/check-slug', 'AdminController::checkSlug');
    $routes->post('blogs/check-title', 'AdminController::checkTitle');
    $routes->post('blogs/delete/(:num)', 'AdminController::deleteBlog/$1');

    // Testimonials
    $routes->get('testimonials', 'AdminController::testimonials');
    $routes->post('testimonials/save', 'AdminController::saveTestimonial');
    $routes->post('testimonials/delete/(:num)', 'AdminController::deleteTestimonial/$1');

    // Affiliate Program
    $routes->get('affiliate/settings', 'ReferralController::adminSettings');
    $routes->post('affiliate/settings', 'ReferralController::updateSettings');
});

$routes->get('sitemap.xml', 'SitemapController::index', ['as' => 'sitemap']);
$routes->post('webhooks/paystack', 'WebhookController::paystack');
service('auth')->routes($routes);

// Chatbot Routes
$routes->post('chatbot/send', 'ChatbotController::sendMessage');
$routes->post('chatbot/clear', 'ChatbotController::clearHistory');

// Newsletter & Webinar Routes
$routes->get('webinars', 'NewsletterController::webinars');
$routes->post('newsletter/subscribe', 'NewsletterController::subscribe');
$routes->post('webinars/register/(:num)', 'NewsletterController::registerWebinar/$1');

// Job Reporting Routes
$routes->post('jobs/report', 'JobReportController::submit');

// E-Learning Routes
$routes->get('training', 'ElearningController::index');
$routes->get('training/course/(:num)', 'ElearningController::show/$1');
$routes->get('training/content/(:num)', 'ElearningController::content/$1');
$routes->get('training/enroll/(:num)', 'ElearningController::enroll');
$routes->get('training/verify/(:num)', 'ElearningController::verify/$1');
$routes->post('training/complete/(:num)', 'ElearningController::completeCourse/$1');
$routes->get('training/certificate/download/(:num)', 'ElearningController::downloadCertificate/$1');
$routes->get('training/certificates', 'ElearningController::myCertificates');

// Admin Newsletter & Webinar Management
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('newsletters', 'NewsletterController::adminIndex');
    $routes->post('newsletters/save', 'NewsletterController::saveNewsletter');
    $routes->post('newsletters/send/(:num)', 'NewsletterController::sendNewsletter/$1');
    $routes->post('webinars/save', 'NewsletterController::saveWebinar');

    // Job Reports
    $routes->get('reports', 'JobReportController::adminIndex');
    $routes->post('reports/update-status', 'JobReportController::updateStatus');

    // E-Learning
    $routes->get('elearning', 'ElearningController::adminIndex');
    $routes->post('elearning/save', 'ElearningController::saveCourse');
});
