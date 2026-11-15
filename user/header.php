<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Safeguard360 Football - Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/safeguard360-logo.svg" width="180" alt="Safeguard360 Football">
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <?php 
                            // In your PHP implementation, you'll check the user's role
                            // For now, showing all navigation for Super Admin
                            $userRole = 'superadmin'; // This will come from session/database
                        ?>
                        
                        <!-- DASHBOARD -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">DASHBOARD</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./dashboard.php" aria-expanded="false">
                                <span><i class="ti ti-layout-dashboard"></i></span>
                                <span class="hide-menu">Main Dashboard</span>
                            </a>
                        </li>

                        <!-- USER MANAGEMENT -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">USER MANAGEMENT</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./users.php" aria-expanded="false">
                                <span><i class="ti ti-users"></i></span>
                                <span class="hide-menu">All Users</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./user-registration.php" aria-expanded="false">
                                <span><i class="ti ti-user-plus"></i></span>
                                <span class="hide-menu">Register User</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./roles-permissions.php" aria-expanded="false">
                                <span><i class="ti ti-shield-lock"></i></span>
                                <span class="hide-menu">Roles & Permissions</span>
                            </a>
                        </li>

                        <!-- TRAINING & COURSES -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">TRAINING & COURSES</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./courses.php" aria-expanded="false">
                                <span><i class="ti ti-school"></i></span>
                                <span class="hide-menu">All Courses</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./course-create.php" aria-expanded="false">
                                <span><i class="ti ti-book-upload"></i></span>
                                <span class="hide-menu">Create Course</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./modules.php" aria-expanded="false">
                                <span><i class="ti ti-list-details"></i></span>
                                <span class="hide-menu">Course Modules</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./training-progress.php" aria-expanded="false">
                                <span><i class="ti ti-progress"></i></span>
                                <span class="hide-menu">Training Progress</span>
                            </a>
                        </li>

                        <!-- ASSESSMENTS & CERTIFICATIONS -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">ASSESSMENTS & CERTIFICATIONS</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./assessments.php" aria-expanded="false">
                                <span><i class="ti ti-clipboard-check"></i></span>
                                <span class="hide-menu">Assessments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./quiz-management.php" aria-expanded="false">
                                <span><i class="ti ti-question-mark"></i></span>
                                <span class="hide-menu">Quiz Management</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./certificates.php" aria-expanded="false">
                                <span><i class="ti ti-certificate"></i></span>
                                <span class="hide-menu">Certificates</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./certificate-verification.php" aria-expanded="false">
                                <span><i class="ti ti-qrcode"></i></span>
                                <span class="hide-menu">Certificate Verification</span>
                            </a>
                        </li>

                        <!-- COMPLIANCE & MONITORING -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">COMPLIANCE & MONITORING</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./compliance-dashboard.php" aria-expanded="false">
                                <span><i class="ti ti-chart-bar"></i></span>
                                <span class="hide-menu">Compliance Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./club-compliance.php" aria-expanded="false">
                                <span><i class="ti ti-building"></i></span>
                                <span class="hide-menu">Club Compliance</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./league-compliance.php" aria-expanded="false">
                                <span><i class="ti ti-trophy"></i></span>
                                <span class="hide-menu">League Compliance</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./federation-compliance.php" aria-expanded="false">
                                <span><i class="ti ti-world"></i></span>
                                <span class="hide-menu">Federation Compliance</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./audit-reports.php" aria-expanded="false">
                                <span><i class="ti ti-file-report"></i></span>
                                <span class="hide-menu">Audit Reports</span>
                            </a>
                        </li>

                        <!-- INCIDENT MANAGEMENT -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">INCIDENT MANAGEMENT</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./incidents.php" aria-expanded="false">
                                <span><i class="ti ti-alert-triangle"></i></span>
                                <span class="hide-menu">All Incidents</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./incident-report.php" aria-expanded="false">
                                <span><i class="ti ti-report"></i></span>
                                <span class="hide-menu">Report Incident</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./anonymous-reports.php" aria-expanded="false">
                                <span><i class="ti ti-user-off"></i></span>
                                <span class="hide-menu">Anonymous Reports</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./escalation-workflow.php" aria-expanded="false">
                                <span><i class="ti ti-arrow-up"></i></span>
                                <span class="hide-menu">Escalation Workflow</span>
                            </a>
                        </li>

                        <!-- NOTIFICATIONS & COMMUNICATIONS -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">NOTIFICATIONS</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./notifications.php" aria-expanded="false">
                                <span><i class="ti ti-bell"></i></span>
                                <span class="hide-menu">Notification Center</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./email-templates.php" aria-expanded="false">
                                <span><i class="ti ti-mail"></i></span>
                                <span class="hide-menu">Email Templates</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./reminder-settings.php" aria-expanded="false">
                                <span><i class="ti ti-clock"></i></span>
                                <span class="hide-menu">Reminder Settings</span>
                            </a>
                        </li>

                        <!-- SYSTEM ADMINISTRATION -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">SYSTEM ADMIN</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./system-settings.php" aria-expanded="false">
                                <span><i class="ti ti-settings"></i></span>
                                <span class="hide-menu">System Settings</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./audit-trail.php" aria-expanded="false">
                                <span><i class="ti ti-history"></i></span>
                                <span class="hide-menu">Audit Trail</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./backup-restore.php" aria-expanded="false">
                                <span><i class="ti ti-cloud-upload"></i></span>
                                <span class="hide-menu">Backup & Restore</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./api-integrations.php" aria-expanded="false">
                                <span><i class="ti ti-api"></i></span>
                                <span class="hide-menu">API Integrations</span>
                            </a>
                        </li>

                        <!-- HELP & SUPPORT -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">HELP & SUPPORT</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./documentation.php" aria-expanded="false">
                                <span><i class="ti ti-help"></i></span>
                                <span class="hide-menu">Documentation</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./support-tickets.php" aria-expanded="false">
                                <span><i class="ti ti-headset"></i></span>
                                <span class="hide-menu">Support Tickets</span>
                            </a>
                        </li>
                    </ul>

                    <!-- User Role Display -->
                    <div class="user-role-display bg-light-success position-relative mb-7 mt-5 rounded p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ti ti-shield-check fs-4 text-success"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">Logged in as: <span class="text-success">Super Admin</span></p>
                                <small class="text-muted">Full system access</small>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- Sidebar End -->

        <!-- Main wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <a href="https://adminmart.com/product/modernize-free-bootstrap-admin-dashboard/" target="_blank" class="btn btn-primary">Download Free</a>
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </a>
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-list-check fs-6"></i>
                                            <p class="mb-0 fs-3">My Task</p>
                                        </a>
                                        <a href="./authentication-login.html" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Header End -->