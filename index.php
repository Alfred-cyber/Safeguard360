<?php
// =============================================
// SAFEGUARD360 UK - FORM HANDLER
// Place this at the VERY TOP of your HTML file
// =============================================

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'safeguard360');
define('DB_USER', 'root');     // Change this
define('DB_PASS', '');         // Change this

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => '', 'errors' => []];
    
    try {
        // Database connection
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        
        // Handle Waiting List Submission
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'waiting_list') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = trim($_POST['role'] ?? '');
            
            // Validation
            if (empty($name)) $response['errors']['name'] = 'Full name is required';
            if (empty($email)) $response['errors']['email'] = 'Email is required';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $response['errors']['email'] = 'Invalid email format';
            if (empty($role)) $response['errors']['role'] = 'Please select your role';
            
            if (empty($response['errors'])) {
                // Check if email exists
                $stmt = $pdo->prepare("SELECT id FROM public_waiting_list WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() > 0) {
                    $response['success'] = true;
                    $response['message'] = 'You\'re already on our waiting list! We\'ll be in touch soon.';
                } else {
                    // Insert new record
                    $stmt = $pdo->prepare("
                        INSERT INTO public_waiting_list (full_name, email, role, ip_address, user_agent) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $name,
                        $email,
                        $role,
                        $_SERVER['REMOTE_ADDR'],
                        $_SERVER['HTTP_USER_AGENT'] ?? null
                    ]);
                    
                    $response['success'] = true;
                    $response['message'] = 'Thanks for joining! We\'ll keep you updated on our UK launch.';
                    
                    // Optional: Send confirmation email
                    // mail($email, "Welcome to Safeguard360 UK", "Thank you for joining...");
                }
            }
        }
        
        // Handle Safeguarding Report Submission
        elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'safeguarding_report') {
            $club_name = trim($_POST['club_name'] ?? '');
            $concern_type = trim($_POST['concern_type'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
            
            // Validation
            if (empty($concern_type)) $response['errors']['concern_type'] = 'Please select the nature of concern';
            if (empty($description)) $response['errors']['description'] = 'Please provide a description';
            elseif (strlen($description) < 10) $response['errors']['description'] = 'Please provide more detail (minimum 10 characters)';
            
            // Determine priority
            $priority = 'medium';
            if (in_array($concern_type, ['Physical concern', 'Verbal misconduct'])) {
                $priority = 'critical';
            } elseif ($concern_type === 'Poor practice / behaviour') {
                $priority = 'high';
            }
            
            if (empty($response['errors'])) {
                // Insert report
                $stmt = $pdo->prepare("
                    INSERT INTO public_safeguarding_reports 
                    (club_name, concern_type, description, is_anonymous, priority, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $club_name ?: null,
                    $concern_type,
                    $description,
                    $is_anonymous,
                    $priority,
                    $_SERVER['REMOTE_ADDR'],
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]);
                
                $report_id = $pdo->lastInsertId();
                
                $response['success'] = true;
                $response['message'] = 'Your report has been submitted securely. Reference #: SAF-' . $report_id . '-' . date('Ymd');
                $response['reference'] = 'SAF-' . $report_id . '-' . date('Ymd');
            }
        }
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $response['message'] = 'A system error occurred. Please try again later.';
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        $response['message'] = 'An error occurred. Please try again.';
    }
    
    // Return JSON response for AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Redirect for regular form posts
    if ($response['success']) {
        $_SESSION['form_message'] = $response['message'];
        $_SESSION['form_success'] = true;
    } else {
        $_SESSION['form_errors'] = $response['errors'];
        $_SESSION['form_message'] = $response['message'] ?: 'Please correct the errors below.';
        $_SESSION['form_success'] = false;
        $_SESSION['form_data'] = $_POST;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '#form-feedback');
    exit;
}

// Display session messages
$form_message = $_SESSION['form_message'] ?? null;
$form_success = $_SESSION['form_success'] ?? null;
$form_errors = $_SESSION['form_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];

// Clear session data
unset($_SESSION['form_message'], $_SESSION['form_success'], $_SESSION['form_errors'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Safeguard360 Football – UK Grassroots Compliance</title>
    <!-- Bootstrap 5 + icons, compact & UK-only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- custom minimal styling: smaller, cleaner, UK focus -->
    <style>
        *{box-sizing:border-box; margin:0; padding:0}
        body{font-family:'Inter',system-ui,'Segoe UI',Roboto,sans-serif; background:#fcfcfd; color:#1e2a41; line-height:1.5}
        .bg-soft{background-color:#eafaf8}
        .brand-green{color:#0e9f8b}
        .bg-brand{background-color:#0e9f8b}
        .btn-360{background-color:#0e9f8b; border:none; color:white; font-weight:600; padding:.6rem 1.4rem; border-radius:2rem; transition:.2s}
        .btn-360:hover{background-color:#0b7e6e; color:white}
        .btn-outline-360{border:2px solid #0e9f8b; color:#0e9f8b; font-weight:600; border-radius:2rem; padding:.55rem 1.3rem}
        .btn-outline-360:hover{background:#0e9f8b; color:white}
        .section-title{font-weight:700; letter-spacing:-0.02em; margin-bottom:1.5rem; font-size:1.9rem}
        .navbar{padding:.6rem 0; background:rgba(255,255,255,0.95); backdrop-filter:blur(8px); box-shadow:0 2px 10px rgba(0,0,0,0.02)}
        .navbar-brand{font-weight:800; font-size:1.7rem; letter-spacing:-.03em}
        .card-360{border:none; border-radius:1.8rem; background:white; padding:1.8rem 1.4rem; box-shadow:0 6px 20px rgba(0,0,0,0.02); transition:.15s; height:100%; border:1px solid #f0f5fa}
        .card-360:hover{box-shadow:0 14px 28px rgba(14,159,139,0.06); border-color:#c0f0e8}
        .icon-lg{font-size:2rem; color:#0e9f8b; margin-bottom:1rem}
        .stat-num{font-size:2.5rem; font-weight:800; color:#0e9f8b; line-height:1}
        .modal-content{border-radius:1.8rem; border:none; padding:0.8rem}
        .modal-header{border:none; padding-bottom:0}
        .footer-links a{color:#4a5a6e; text-decoration:none; font-size:.95rem}
        .footer-links a:hover{color:#0e9f8b}
        hr{opacity:.2; margin:2rem 0}
        .list-bullet li{list-style:none; padding-left:1.8rem; margin-bottom:.75rem; position:relative}
        .list-bullet li:before{content:"⚽"; color:#0e9f8b; position:absolute; left:0; font-size:1.1rem}
        .tiny-badge{background:#e3f2ef; color:#0e9f8b; padding:.3rem .9rem; border-radius:3rem; font-size:.8rem; font-weight:600}
        .hero{background:linear-gradient(125deg, #f1fcf9 0%, #ffffff 70%)}
        /* compact spacing */
        .container{max-width:1200px}
        .py-6{padding-top:4rem; padding-bottom:4rem}
        .mt-n1{margin-top:-.8rem}
        .form-feedback {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

<?php if ($form_message): ?>
<div id="form-feedback" class="form-feedback">
    <div class="alert alert-<?= $form_success ? 'success' : 'danger' ?> alert-dismissible fade show shadow-lg border-0">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?= htmlspecialchars($form_message) ?>
    </div>
</div>
<?php endif; ?>

    <!-- ███ COMPACT NAV - UK ONLY ███ -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-fill-check brand-green me-1"></i><span class="fw-bold text-dark">Safeguard</span><span class="brand-green">360</span> <span style="font-size:.9rem; font-weight:400; color:#3a4a5a;">UK</span></a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav360"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="nav360">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#problem">Risk</a></li>
                    <li class="nav-item"><a class="nav-link" href="#solution">Fix</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#public">Report</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-360 btn-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#waitingModal"><i class="bi bi-envelope-paper me-1"></i>Join</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ███ HERO - PURE UK MESSAGE ███ -->
    <section class="hero pt-6 pb-5" style="margin-top:4.2rem;">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="tiny-badge mb-3 d-inline-block"><i class="bi bi-flag me-1"></i>BUILT FOR BRITISH GRASSROOTS</span>
                    <h1 class="display-4 fw-bold mb-3 lh-1" style="letter-spacing:-.03em;">Safeguarding compliance,<br><span class="brand-green">embedded in every club.</span></h1>
                    <p class="lead text-secondary mb-4" style="max-width:90%;">Move from one‑off courses to live digital oversight. FIFA‑aligned, federation‑ready, UK‑hosted.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-360 px-5 py-2" data-bs-toggle="modal" data-bs-target="#waitingModal"><i class="bi bi-megaphone me-2"></i>Get early access</a>
                        <a class="btn btn-outline-360 px-4 py-2" href="#public"><i class="bi bi-flag me-2"></i>Report a concern</a>
                    </div>
                    <div class="mt-4 d-flex align-items-center text-muted small"><i class="bi bi-check-circle-fill brand-green me-2"></i>GDPR compliant · UK data centres · FA guidance aligned</div>
                </div>
                <div class="col-lg-5">
                    <div class="bg-soft p-3 rounded-5">
                        <img src="https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=800&auto=format&fit=crop" alt="grassroots UK football" class="img-fluid rounded-4 shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ███ PROBLEM / SOLUTION COMPACT TWIN ███ -->
    <section id="problem" class="py-6 container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-360 h-100">
                    <div class="icon-lg"><i class="bi bi-exclamation-triangle"></i></div>
                    <h3 class="fw-bold h4">The safeguarding gap</h3>
                    <ul class="list-bullet mt-3 mb-0">
                        <li>Inconsistent training across 100,000+ teams</li>
                        <li>Paper records, no expiry tracking</li>
                        <li>Weak reporting – parents don’t know where</li>
                        <li>No unified compliance view for leagues</li>
                    </ul>
                    <div class="mt-4 small text-secondary"><i class="bi bi-dot"></i> 78% of clubs lack digital safeguarding audit trails</div>
                </div>
            </div>
            <div class="col-md-6" id="solution">
                <div class="card-360 h-100 border-0 bg-soft">
                    <div class="icon-lg"><i class="bi bi-shield-plus"></i></div>
                    <h3 class="fw-bold h4">Safeguard360 solution</h3>
                    <ul class="list-bullet mt-3 mb-0">
                        <li>Modular courses – coach, ref, welfare officer</li>
                        <li>QR‑verified certificates + auto‑renewal</li>
                        <li>Real‑time club/league dashboards</li>
                        <li>Confidential incident reports, direct escalation</li>
                    </ul>
                    <div class="mt-4"><span class="badge bg-white text-dark border p-2">⚙️ FIFA guidance · platinum governance</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ███ FEATURES · 3 COMPACT CARDS ███ -->
    <section id="features" class="py-5 bg-soft">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-4">
                <h2 class="section-title mb-0">Platform essentials</h2>
                <span class="text-secondary small">digital · verified · UK owned</span>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-360">
                        <i class="bi bi-qr-code-scan fs-1 brand-green"></i>
                        <h4 class="fw-bold h5 mt-3">QR‑verify anyone</h4>
                        <p class="text-secondary small">Scan certificate to check expiry, role & training. No more outdated PDFs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-360">
                        <i class="bi bi-bar-chart-steps fs-1 brand-green"></i>
                        <h4 class="fw-bold h5 mt-3">Club compliance hub</h4>
                        <p class="text-secondary small">League‑level oversight, automated alerts, audit trails – zero spreadsheets.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-360">
                        <i class="bi bi-incognito fs-1 brand-green"></i>
                        <h4 class="fw-bold h5 mt-3">Secure incident reporting</h4>
                        <p class="text-secondary small">Public & anonymous reports, triaged to club welfare officer + FA where required.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ███ PUBLIC REPORT MODAL TRIGGER & STATS (UK ONLY) ███ -->
    <section id="public" class="py-6 container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="tiny-badge"><i class="bi bi-megaphone"></i> SAFEGUARDING IS EVERYONE'S ROLE</span>
                <h2 class="display-6 fw-bold mt-2 mb-3">Speak up. <span class="brand-green">Report a concern.</span></h2>
                <p class="text-secondary">If you see something, say something. Use our confidential form – no login needed. Reports go directly to the club's Safeguarding Officer and The FA if required.</p>
                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-360 px-5 py-2" data-bs-toggle="modal" data-bs-target="#reportModal"><i class="bi bi-flag me-2"></i>Make a report</button>
                    <button class="btn btn-outline-secondary border-2" data-bs-toggle="modal" data-bs-target="#waitingModal"><i class="bi bi-bell"></i> Get updates</button>
                </div>
                <div class="row mt-5 g-3">
                    <div class="col-4">
                        <div class="stat-num">12k+</div>
                        <div class="small text-uppercase text-secondary">UK coaches</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-num">340+</div>
                        <div class="small text-uppercase text-secondary">clubs onboard</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-num">100%</div>
                        <div class="small text-uppercase text-secondary">UK hosted</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="bg-soft p-4 rounded-5 text-center">
                    <i class="bi bi-shield-shaded" style="font-size: 4rem; color:#0e9f8b;"></i>
                    <h5 class="fw-bold mt-2">Impartial, confidential, secure</h5>
                    <p class="small text-secondary mb-0">Your information stays in the UK. We never share without consent.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ███ COMPACT TRUST/IMPACT – REMOVED NIGERIA ███ -->
    <section class="py-5 bg-soft">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="p-3"><i class="bi bi-award fs-3 brand-green"></i>
                        <div class="fw-bold mt-1">FIFA guidance</div>
                        <small class="text-secondary">Platinum holder</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3"><i class="bi bi-lock fs-3 brand-green"></i>
                        <div class="fw-bold mt-1">GDPR compliant</div>
                        <small class="text-secondary">UK data sovereignty</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3"><i class="bi bi-building fs-3 brand-green"></i>
                        <div class="fw-bold mt-1">Federation ready</div>
                        <small class="text-secondary">FA, Cymru, SFA</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3"><i class="bi bi-arrow-repeat fs-3 brand-green"></i>
                        <div class="fw-bold mt-1">Annual renewal</div>
                        <small class="text-secondary">always up to date</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ███ MODAL 1 – PUBLIC INCIDENT REPORT (CORE PUBLIC FEATURE) ███ -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title fw-bold"><i class="bi bi-flag brand-green me-2"></i> Report a safeguarding concern</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-secondary">Your report is confidential. All UK clubs using Safeguard360 receive reports instantly.</p>
                    <form method="POST" action="" id="publicReportForm">
                        <input type="hidden" name="form_type" value="safeguarding_report">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Club/League name (if known)</label>
                            <input type="text" class="form-control form-control-sm rounded-pill" 
                                   name="club_name" 
                                   placeholder="e.g. Manchester United Academy"
                                   value="<?= htmlspecialchars($form_data['club_name'] ?? '') ?>">
                            <?php if (isset($form_errors['club_name'])): ?>
                                <small class="text-danger"><?= $form_errors['club_name'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nature of concern</label>
                            <select class="form-select form-select-sm rounded-pill" name="concern_type">
                                <option value="">Select concern type</option>
                                <option value="Poor practice / behaviour" <?= ($form_data['concern_type'] ?? '') == 'Poor practice / behaviour' ? 'selected' : '' ?>>Poor practice / behaviour</option>
                                <option value="Out of date certification" <?= ($form_data['concern_type'] ?? '') == 'Out of date certification' ? 'selected' : '' ?>>Out of date certification</option>
                                <option value="Verbal misconduct" <?= ($form_data['concern_type'] ?? '') == 'Verbal misconduct' ? 'selected' : '' ?>>Verbal misconduct</option>
                                <option value="Physical concern" <?= ($form_data['concern_type'] ?? '') == 'Physical concern' ? 'selected' : '' ?>>Physical concern</option>
                                <option value="Other" <?= ($form_data['concern_type'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if (isset($form_errors['concern_type'])): ?>
                                <small class="text-danger"><?= $form_errors['concern_type'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Description (no names? just context)</label>
                            <textarea class="form-control" rows="3" 
                                      name="description" 
                                      placeholder="What happened, when, where..."><?= htmlspecialchars($form_data['description'] ?? '') ?></textarea>
                            <?php if (isset($form_errors['description'])): ?>
                                <small class="text-danger"><?= $form_errors['description'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_anonymous" id="anonCheck" <?= !isset($form_data['is_anonymous']) || $form_data['is_anonymous'] ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="anonCheck">Report anonymously (no follow‑up)</label>
                        </div>
                        
                        <button type="submit" class="btn btn-360 w-100 rounded-pill py-2">
                            <i class="bi bi-send me-2"></i>Submit secure report
                        </button>
                    </form>
                    <p class="text-muted small mt-3 mb-0"><i class="bi bi-shield-check"></i> End‑to‑end encrypted · UK GDPR</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ███ MODAL 2 – WAITING LIST / INTEREST (PUBLIC) ███ -->
    <div class="modal fade" id="waitingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title fw-bold"><i class="bi bi-envelope-paper brand-green me-2"></i> Join Safeguard360 UK</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small">Be first to access UK safeguarding compliance tools. Clubs, leagues, welfare officers.</p>
                    <form method="POST" action="">
                        <input type="hidden" name="form_type" value="waiting_list">
                        
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-sm rounded-pill" 
                                   name="name" 
                                   placeholder="Full name"
                                   value="<?= htmlspecialchars($form_data['name'] ?? '') ?>">
                            <?php if (isset($form_errors['name'])): ?>
                                <small class="text-danger"><?= $form_errors['name'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <input type="email" class="form-control form-control-sm rounded-pill" 
                                   name="email" 
                                   placeholder="Email address"
                                   value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
                            <?php if (isset($form_errors['email'])): ?>
                                <small class="text-danger"><?= $form_errors['email'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <select class="form-select form-select-sm rounded-pill" name="role">
                                <option value="">Select your role</option>
                                <option value="Coach / referee" <?= ($form_data['role'] ?? '') == 'Coach / referee' ? 'selected' : '' ?>>Coach / referee</option>
                                <option value="Club official / welfare officer" <?= ($form_data['role'] ?? '') == 'Club official / welfare officer' ? 'selected' : '' ?>>Club official / welfare officer</option>
                                <option value="League administrator" <?= ($form_data['role'] ?? '') == 'League administrator' ? 'selected' : '' ?>>League administrator</option>
                                <option value="Parent / carer" <?= ($form_data['role'] ?? '') == 'Parent / carer' ? 'selected' : '' ?>>Parent / carer</option>
                                <option value="Other" <?= ($form_data['role'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if (isset($form_errors['role'])): ?>
                                <small class="text-danger"><?= $form_errors['role'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-360 w-100 rounded-pill py-2">
                            <i class="bi bi-check2-circle me-2"></i>Secure my spot
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ███ FOOTER – CLEAN, NO NIGERIA ███ -->
    <footer class="bg-white pt-5 pb-4 border-top">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <a class="navbar-brand" href="#"><i class="bi bi-shield-fill-check brand-green"></i><span class="fw-bold text-dark">Safeguard</span><span class="brand-green">360</span></a>
                    <p class="small text-secondary mt-2">Digital safeguarding compliance for UK grassroots football. Trusted by clubs, backed by FIFA guidance.</p>
                    <span class="tiny-badge"><i class="bi bi-geo-alt-fill"></i> London · Manchester · Edinburgh</span>
                </div>
                <div class="col-md-2 col-6">
                    <div class="footer-links">
                        <h6 class="fw-bold">Public</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#reportModal">Report incident</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#waitingModal">Join waiting list</a></li>
                            <li><a href="#">UK safeguarding guide</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="footer-links">
                        <h6 class="fw-bold">Solutions</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#">For clubs</a></li>
                            <li><a href="#">For leagues</a></li>
                            <li><a href="#">For FA's</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold">UK compliance hotline</h6>
                    <p class="small text-secondary"><i class="bi bi-telephone"></i> 0800 360 3601 (confidential)<br>© 2024 – Safeguard360 Football UK</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center small text-secondary">
                <span>🇬🇧 Solely for the United Kingdom. All data remains in UK.</span>
                <span><a href="#" class="text-decoration-none text-secondary">Privacy</a> · <a href="#" class="text-decoration-none text-secondary">GDPR</a> · <a href="#" class="text-decoration-none text-secondary">Cookies</a></span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Smooth hash scroll
        document.querySelectorAll('a[href^="#"]:not([data-bs-toggle])').forEach(a=>{
            a.addEventListener('click',function(e){
                e.preventDefault(); 
                let t=document.querySelector(this.getAttribute('href')); 
                if(t) window.scrollTo({top:t.offsetTop-70,behavior:'smooth'})
            })
        });
        
        // Open modal if there are errors
        <?php if (!empty($form_errors)): ?>
            <?php if (($form_data['form_type'] ?? '') === 'safeguarding_report'): ?>
                var reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
                reportModal.show();
            <?php elseif (($form_data['form_type'] ?? '') === 'waiting_list'): ?>
                var waitModal = new bootstrap.Modal(document.getElementById('waitingModal'));
                waitModal.show();
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>