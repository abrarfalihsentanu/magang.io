<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================================
// PUBLIC ROUTES (No Auth Required)
// ========================================
$routes->get('/', 'LandingController::index');
$routes->get('/login', 'AuthController::login', ['filter' => 'guest']);
$routes->post('/login', 'AuthController::processLogin', ['filter' => 'guest']);

// ========================================
// AUTHENTICATED ROUTES
// ========================================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Logout
    $routes->get('/logout', 'AuthController::logout');

    // Dashboard (All Roles)
    $routes->get('/dashboard', 'Dashboard::index');
    // Dashboard data endpoints (used by charts)
    $routes->get('/dashboard/data/attendance-trend', 'Dashboard::attendanceTrend');
    $routes->get('/dashboard/data/interns-by-division', 'Dashboard::internsByDivision');
    $routes->get('/dashboard/data/finance-summary', 'Dashboard::financeSummary');
    $routes->get('/dashboard/data/mentor-mentees', 'Dashboard::mentorMentees');
    $routes->get('/dashboard/data/intern-activities', 'Dashboard::internActivities');
    // Additional dashboard data endpoints per requested features
    $routes->get('/dashboard/data/role-distribution', 'Dashboard::roleDistribution');
    $routes->get('/dashboard/data/login-activity', 'Dashboard::loginActivity');
    $routes->get('/dashboard/data/intern-growth', 'Dashboard::internGrowth');

    $routes->get('/dashboard/data/attendance-3months', 'Dashboard::attendance3Months');
    $routes->get('/dashboard/data/pending-corrections', 'Dashboard::pendingCorrections');
    $routes->get('/dashboard/data/daily-attendance-division', 'Dashboard::dailyAttendanceByDivision');

    $routes->get('/dashboard/data/mentor-mentees-detail', 'Dashboard::mentorMenteesDetail');
    $routes->get('/dashboard/data/mentor-activity-feed', 'Dashboard::mentorActivityFeed');

    $routes->get('/dashboard/data/finance-payments', 'Dashboard::financePayments');
    $routes->get('/dashboard/data/finance-by-division', 'Dashboard::financeByDivision');

    $routes->get('/dashboard/data/attendance-calendar', 'Dashboard::attendanceCalendar');
    $routes->get('/dashboard/data/attendance-vs-target', 'Dashboard::attendanceVsTarget');
    $routes->get('/dashboard/data/allowance-history', 'Dashboard::allowanceHistory');

    // Profile (All Roles)
    $routes->get('/profile', 'ProfileController::index');
    $routes->post('/profile/update', 'ProfileController::update');
    $routes->post('/profile/change-password', 'ProfileController::changePassword');
    $routes->get('/profile/photo/(:any)', 'ProfileController::photo/$1');

    // ========================================
    // ADMIN & HR ONLY ROUTES
    // ========================================
    $routes->group('', ['filter' => 'auth:admin,hr'], function ($routes) {

        // User Management
        $routes->get('/user', 'UserController::index');
        $routes->get('/user/create', 'UserController::create');
        $routes->post('/user/store', 'UserController::store');
        $routes->get('/user/edit/(:num)', 'UserController::edit/$1');
        $routes->post('/user/update/(:num)', 'UserController::update/$1');
        $routes->delete('/user/delete/(:num)', 'UserController::delete/$1');
        $routes->get('user/detail/(:num)', 'UserController::detail/$1');
        $routes->post('/user/get-next-nik', 'UserController::getNextNIK');

        // Intern Management
        $routes->get('/intern', 'InternController::index');
        $routes->get('/intern/create', 'InternController::create');
        $routes->post('/intern/store', 'InternController::store');
        $routes->get('/intern/detail/(:num)', 'InternController::detail/$1');
        $routes->get('/intern/edit/(:num)', 'InternController::edit/$1');
        $routes->post('/intern/update/(:num)', 'InternController::update/$1');
        $routes->delete('/intern/delete/(:num)', 'InternController::delete/$1');
        $routes->post('/intern/toggle-status/(:num)', 'InternController::toggleStatus/$1');
        $routes->get('/intern/download-document/(:num)', 'InternController::downloadDocument/$1');
    });

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    $routes->group('', ['filter' => 'auth:admin'], function ($routes) {

        // Role Management
        $routes->get('/role', 'RoleController::index');
        $routes->get('/role/create', 'RoleController::create');
        $routes->post('/role/store', 'RoleController::store');
        $routes->get('/role/detail/(:num)', 'RoleController::detail/$1');
        $routes->get('/role/edit/(:num)', 'RoleController::edit/$1');
        $routes->post('/role/update/(:num)', 'RoleController::update/$1');
        $routes->delete('/role/delete/(:num)', 'RoleController::delete/$1');
        $routes->post('/role/toggle-status/(:num)', 'RoleController::toggleStatus/$1');

        // Divisi Management
        $routes->get('/divisi', 'DivisiController::index');
        $routes->get('/divisi/create', 'DivisiController::create');
        $routes->post('/divisi/store', 'DivisiController::store');
        $routes->get('/divisi/detail/(:num)', 'DivisiController::detail/$1');
        $routes->get('/divisi/edit/(:num)', 'DivisiController::edit/$1');
        $routes->post('/divisi/update/(:num)', 'DivisiController::update/$1');
        $routes->delete('/divisi/delete/(:num)', 'DivisiController::delete/$1');
        $routes->post('/divisi/toggle-status/(:num)', 'DivisiController::toggleStatus/$1');

        // Settings
        $routes->group('settings', function ($routes) {
            $routes->get('/', 'SettingController::index');
            $routes->get('create', 'SettingController::create');
            $routes->post('store', 'SettingController::store');
            $routes->get('detail/(:num)', 'SettingController::detail/$1');
            $routes->get('edit/(:num)', 'SettingController::edit/$1');
            $routes->post('update/(:num)', 'SettingController::update/$1');
            $routes->delete('delete/(:num)', 'SettingController::delete/$1');
            $routes->post('bulk-update', 'SettingController::bulkUpdate');
        });

        // Audit Logs
        $routes->get('/audit', 'AuditController::index');
    });

    // ========================================
    // ATTENDANCE ROUTES
    // ========================================
    $routes->group('attendance', function ($routes) {

        // For Interns
        $routes->get('/', 'AttendanceController::index', ['filter' => 'auth:intern']);
        $routes->get('checkin', 'AttendanceController::checkin', ['filter' => 'auth:intern']);
        $routes->post('process-checkin', 'AttendanceController::processCheckin', ['filter' => 'auth:intern']);
        $routes->post('process-checkout', 'AttendanceController::processCheckout', ['filter' => 'auth:intern']);

        // Correction (Intern)
        $routes->get('correction', 'AttendanceController::correction', ['filter' => 'auth:intern']);
        $routes->post('correction/submit', 'AttendanceController::submitCorrection', ['filter' => 'auth:intern']);

        // For Admin/HR/Mentor
        $routes->get('all', 'AttendanceController::viewAll', ['filter' => 'auth:admin,hr,mentor,finance']);
        $routes->get('correction/approval', 'AttendanceController::correctionApproval', ['filter' => 'auth:admin,hr,mentor']);
        $routes->post('correction/approve/(:num)', 'AttendanceController::approveCorrection/$1', ['filter' => 'auth:admin,hr,mentor']);
        $routes->post('correction/reject/(:num)', 'AttendanceController::rejectCorrection/$1', ['filter' => 'auth:admin,hr,mentor']);
    });

    // ========================================
    // ACTIVITY & PROJECT ROUTES
    // ========================================
    $routes->group('activity', function ($routes) {

        // For Interns
        $routes->get('my', 'ActivityController::my', ['filter' => 'auth:intern']);
        $routes->get('create', 'ActivityController::create', ['filter' => 'auth:intern']);
        $routes->post('store', 'ActivityController::store', ['filter' => 'auth:intern']);
        $routes->get('edit/(:num)', 'ActivityController::edit/$1', ['filter' => 'auth:intern']);
        $routes->post('update/(:num)', 'ActivityController::update/$1', ['filter' => 'auth:intern']);
        $routes->delete('delete/(:num)', 'ActivityController::delete/$1', ['filter' => 'auth:intern']);

        // For Mentor
        $routes->get('approval', 'ActivityController::approval', ['filter' => 'auth:mentor,admin,hr']);
        $routes->post('approve/(:num)', 'ActivityController::approve/$1', ['filter' => 'auth:mentor,admin,hr']);
        $routes->post('reject/(:num)', 'ActivityController::reject/$1', ['filter' => 'auth:mentor,admin,hr']);
        $routes->post('batch-approve', 'ActivityController::batchApprove', ['filter' => 'auth:mentor,admin,hr']);

        // For Admin/HR
        $routes->get('/', 'ActivityController::index', ['filter' => 'auth:admin,hr']);
        $routes->get('dashboard', 'ActivityController::dashboard', ['filter' => 'auth:admin,hr']);
        $routes->get('export', 'ActivityController::export', ['filter' => 'auth:admin,hr']);

        // File handling
        $routes->get('detail/(:num)', 'ActivityController::detail/$1', ['filter' => 'auth']);
        $routes->get('attachment/view/(:num)', 'ActivityController::viewAttachment/$1', ['filter' => 'auth']);
        $routes->get('attachment/download/(:num)', 'ActivityController::downloadAttachment/$1', ['filter' => 'auth']);
    });

    // ========================================
    // WEEKLY PROJECT ROUTES
    // ========================================
    $routes->group('project', function ($routes) {

        // For Interns
        $routes->get('my', 'ProjectController::my', ['filter' => 'auth:intern']);
        $routes->get('create', 'ProjectController::create', ['filter' => 'auth:intern']);
        $routes->post('store', 'ProjectController::store', ['filter' => 'auth:intern']);
        $routes->get('edit/(:num)', 'ProjectController::edit/$1', ['filter' => 'auth:intern']);
        $routes->post('update/(:num)', 'ProjectController::update/$1', ['filter' => 'auth:intern']);
        $routes->delete('delete/(:num)', 'ProjectController::delete/$1', ['filter' => 'auth:intern']);

        // For Mentor
        $routes->get('assessment', 'ProjectController::assessment', ['filter' => 'auth:mentor,admin,hr']);
        $routes->post('assess/(:num)', 'ProjectController::assess/$1', ['filter' => 'auth:mentor,admin,hr']);

        // For Admin/HR
        $routes->get('/', 'ProjectController::index', ['filter' => 'auth:admin,hr']);
        $routes->get('dashboard', 'ProjectController::dashboard', ['filter' => 'auth:admin,hr']);
        $routes->get('export', 'ProjectController::export', ['filter' => 'auth:admin,hr']);

        // File handling
        $routes->get('detail/(:num)', 'ProjectController::detail/$1', ['filter' => 'auth']);
        $routes->get('attachment/view/(:num)', 'ProjectController::viewAttachment/$1', ['filter' => 'auth']);
        $routes->get('attachment/download/(:num)', 'ProjectController::downloadAttachment/$1', ['filter' => 'auth']);
    });

    // ========================================
    // KPI ROUTES - COMPLETE SYSTEM
    // ========================================
    $routes->group('kpi', function ($routes) {

        // ADMIN ONLY - Indicators Management
        $routes->group('indicators', ['filter' => 'auth:admin'], function ($routes) {
            $routes->get('/', 'KpiIndicatorController::index');
            $routes->get('create', 'KpiIndicatorController::create');
            $routes->post('store', 'KpiIndicatorController::store');
            $routes->get('edit/(:num)', 'KpiIndicatorController::edit/$1');
            $routes->post('update/(:num)', 'KpiIndicatorController::update/$1');
            $routes->post('toggle-status/(:num)', 'KpiIndicatorController::toggleStatus/$1');
            $routes->delete('delete/(:num)', 'KpiIndicatorController::delete/$1');
        });

        // ADMIN/HR - KPI Calculation
        $routes->group('calculation', ['filter' => 'auth:admin,hr'], function ($routes) {
            $routes->get('/', 'Admin\KpiCalculationController::index');
            $routes->post('calculate', 'Admin\KpiCalculationController::calculate');
            $routes->post('recalculate/(:num)', 'Admin\KpiCalculationController::recalculate/$1');
        });

        // MENTOR - Manual Assessment
        $routes->group('assessment', ['filter' => 'auth:mentor,admin,hr'], function ($routes) {
            $routes->get('/', 'Mentor\KpiAssessmentController::index');
            $routes->get('form/(:num)', 'Mentor\KpiAssessmentController::assessmentForm/$1');
            $routes->post('submit', 'Mentor\KpiAssessmentController::submitAssessment');
            $routes->get('history/(:num)', 'Mentor\KpiAssessmentController::history/$1');
        });

        // ADMIN/HR - Monthly Results
        $routes->group('monthly', ['filter' => 'auth:admin,hr,mentor'], function ($routes) {
            $routes->get('/', 'Admin\KpiMonthlyController::index');
            $routes->get('view/(:num)/(:num)', 'Admin\KpiMonthlyController::view/$1/$2');
            $routes->post('finalize/(:num)', 'Admin\KpiMonthlyController::finalize/$1');
            $routes->get('export', 'Admin\KpiMonthlyController::export');
        });

        // ADMIN/HR - Period & Best Intern
        $routes->group('period', ['filter' => 'auth:admin,hr'], function ($routes) {
            $routes->get('/', 'Admin\KpiPeriodController::index');
            $routes->post('calculate', 'Admin\KpiPeriodController::calculatePeriod');
            $routes->get('best-interns', 'Admin\KpiPeriodController::bestInterns');
            $routes->post('generate-certificate/(:num)', 'Admin\KpiPeriodController::generateCertificate/$1');
            $routes->get('certificate/download/(:num)', 'Admin\KpiPeriodController::downloadCertificate/$1');
        });

        // INTERN - My KPI
        $routes->group('my', ['filter' => 'auth:intern'], function ($routes) {
            $routes->get('/', 'Intern\MyKpiController::dashboard');
            $routes->get('monthly/(:num)/(:num)', 'Intern\MyKpiController::monthlyDetail/$1/$2');
            $routes->get('breakdown', 'Intern\MyKpiController::breakdown');
            $routes->get('history', 'Intern\MyKpiController::history');
        });

        // Public Ranking/Leaderboard
        $routes->get('ranking', 'KpiRankingController::index', ['filter' => 'auth']);
        $routes->get('ranking/division/(:num)', 'KpiRankingController::byDivision/$1', ['filter' => 'auth']);

        // ADMIN/HR - Analytics
        $routes->group('analytics', ['filter' => 'auth:admin,hr'], function ($routes) {
            $routes->get('/', 'Admin\KpiAnalyticsController::index');
            $routes->get('distribution', 'Admin\KpiAnalyticsController::distribution');
            $routes->get('trends', 'Admin\KpiAnalyticsController::trends');
            $routes->post('export-report', 'Admin\KpiAnalyticsController::exportReport');
        });
    });

    // ========================================
    // ALLOWANCE ROUTES
    // ========================================
    $routes->group('allowance', function ($routes) {

        // For Interns
        $routes->get('my', 'AllowanceController::my', ['filter' => 'auth:intern']);
        $routes->get('slip/(:num)', 'AllowanceController::downloadSlip/$1', ['filter' => 'auth:intern']);

        // For Admin/HR/Finance
        $routes->get('period', 'AllowanceController::period', ['filter' => 'auth:admin,hr,finance']);
        $routes->post('period/create', 'AllowanceController::createPeriod', ['filter' => 'auth:admin,hr']);
        $routes->get('/', 'AllowanceController::index', ['filter' => 'auth:admin,hr,finance']);
        $routes->post('calculate', 'AllowanceController::calculate', ['filter' => 'auth:admin,hr']);

        // For Finance Only
        $routes->get('payment', 'AllowanceController::payment', ['filter' => 'auth:finance']);
        $routes->post('process-payment/(:num)', 'AllowanceController::processPayment/$1', ['filter' => 'auth:finance']);
    });

    // ========================================
    // LEAVE/CUTI ROUTES
    // ========================================
    $routes->group('leave', function ($routes) {

        // For Interns
        $routes->get('my', 'LeaveController::my', ['filter' => 'auth:intern']);
        $routes->get('create', 'LeaveController::create', ['filter' => 'auth:intern']);
        $routes->post('submit', 'LeaveController::submit', ['filter' => 'auth:intern']);

        // For Admin/HR/Mentor
        $routes->get('approval', 'LeaveController::approval', ['filter' => 'auth:admin,hr,mentor']);
        $routes->post('approve/(:num)', 'LeaveController::approve/$1', ['filter' => 'auth:admin,hr,mentor']);
        $routes->post('reject/(:num)', 'LeaveController::reject/$1', ['filter' => 'auth:admin,hr,mentor']);
    });

    // ========================================
    // REPORTS
    // ========================================
    $routes->group('report', ['filter' => 'auth:admin,hr,finance,mentor'], function ($routes) {
        $routes->get('attendance', 'ReportController::attendance');
        $routes->get('activity', 'ReportController::activity');
        $routes->get('kpi', 'ReportController::kpi');
        $routes->get('allowance', 'ReportController::allowance');
        $routes->post('export', 'ReportController::export');
    });

    // ========================================
    // ARCHIVE (Admin & HR Only)
    // ========================================
    $routes->get('archive', 'ArchiveController::index', ['filter' => 'auth:admin,hr']);
    $routes->get('archive/view/(:num)', 'ArchiveController::view/$1', ['filter' => 'auth:admin,hr']);
    $routes->post('archive/process', 'ArchiveController::process', ['filter' => 'auth:admin,hr']);
});

// ========================================
// NOTIFICATION FULL PAGE
// ========================================
$routes->get('notifications', 'NotificationController::index', ['filter' => 'auth']);

// ========================================
// API ROUTES (AJAX)
// ========================================
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('check-session', 'AuthController::checkSession');
    $routes->get('notifications', 'NotificationController::getUnread');
    $routes->post('notification/mark-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('notifications/mark-all-read', 'NotificationController::markAllAsRead');
});
