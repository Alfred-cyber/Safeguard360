<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "header.php";
require_once '../includes/db.php';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {

    // =========================
    // USERS
    // =========================
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $activeUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status='active'")->fetchColumn();
    $inactiveUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status='inactive'")->fetchColumn();

    // =========================
    // COURSES
    // =========================
    $totalCourses = $pdo->query("SELECT COUNT(*) FROM courses WHERE is_active=1")->fetchColumn();

    // Total Enrolments
    $totalEnrolments = $pdo->query("SELECT COUNT(*) FROM course_enrolments")->fetchColumn();

    // Completed Courses
    $completedCourses = $pdo->query("
        SELECT COUNT(*) 
        FROM course_enrolments 
        WHERE completed_at IS NOT NULL
    ")->fetchColumn();

    // =========================
    // CERTIFICATES
    // =========================
    $validCertificates = $pdo->query("
        SELECT COUNT(*) 
        FROM certificates 
        WHERE revoked=0 AND expires_at >= NOW()
    ")->fetchColumn();

    $expiredCertificates = $pdo->query("
        SELECT COUNT(*) 
        FROM certificates 
        WHERE revoked=0 AND expires_at < NOW()
    ")->fetchColumn();

    // =========================
    // INCIDENTS (Internal)
    // =========================
    $totalIncidents = $pdo->query("SELECT COUNT(*) FROM incident_reports")->fetchColumn();

    $openIncidents = $pdo->query("
        SELECT COUNT(*) 
        FROM incident_reports 
        WHERE status IN ('reported','investigating','escalated')
    ")->fetchColumn();

    // =========================
    // PUBLIC SAFEGUARDING REPORTS
    // =========================
    $publicReports = $pdo->query("SELECT COUNT(*) FROM public_safeguarding_reports")->fetchColumn();

    $criticalPublicReports = $pdo->query("
        SELECT COUNT(*) 
        FROM public_safeguarding_reports 
        WHERE priority='critical'
    ")->fetchColumn();

    // =========================
    // QUIZ ATTEMPTS
    // =========================
    $totalQuizAttempts = $pdo->query("SELECT COUNT(*) FROM quiz_attempts")->fetchColumn();

    $passedQuizAttempts = $pdo->query("
        SELECT COUNT(*) 
        FROM quiz_attempts 
        WHERE passed=1
    ")->fetchColumn();

    // =========================
    // RECENT AUDIT LOGS
    // =========================
    $recentAudits = $pdo->query("
        SELECT a.*, u.email 
        FROM audit_logs a
        LEFT JOIN users u ON u.id = a.user_id
        ORDER BY a.created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div style='background:#000;color:#0f0;padding:20px'>";
    echo "<h3>Database Error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div>";
    exit;
}
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-3 mb-4">
<div class="card bg-light-primary"><div class="card-body">
<h6>Total Users</h6><h3><?= $totalUsers ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-success"><div class="card-body">
<h6>Active Users</h6><h3><?= $activeUsers ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-warning"><div class="card-body">
<h6>Total Courses</h6><h3><?= $totalCourses ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-info"><div class="card-body">
<h6>Total Enrolments</h6><h3><?= $totalEnrolments ?></h3>
</div></div></div>

</div>


<div class="row">

<div class="col-md-3 mb-4">
<div class="card bg-light-secondary"><div class="card-body">
<h6>Completed Courses</h6><h3><?= $completedCourses ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-success"><div class="card-body">
<h6>Valid Certificates</h6><h3><?= $validCertificates ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-danger"><div class="card-body">
<h6>Expired Certificates</h6><h3><?= $expiredCertificates ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-danger"><div class="card-body">
<h6>Open Incidents</h6><h3><?= $openIncidents ?></h3>
</div></div></div>

</div>


<div class="row">

<div class="col-md-3 mb-4">
<div class="card bg-light-warning"><div class="card-body">
<h6>Public Reports</h6><h3><?= $publicReports ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-danger"><div class="card-body">
<h6>Critical Public Reports</h6><h3><?= $criticalPublicReports ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-primary"><div class="card-body">
<h6>Total Quiz Attempts</h6><h3><?= $totalQuizAttempts ?></h3>
</div></div></div>

<div class="col-md-3 mb-4">
<div class="card bg-light-success"><div class="card-body">
<h6>Passed Quizzes</h6><h3><?= $passedQuizAttempts ?></h3>
</div></div></div>

</div>


<div class="card mt-4">
<div class="card-body">
<h5>Recent Audit Logs</h5>
<table class="table table-striped">
<thead>
<tr>
<th>User</th>
<th>Action</th>
<th>IP</th>
<th>Date</th>
</tr>
</thead>
<tbody>
<?php if($recentAudits): ?>
<?php foreach($recentAudits as $audit): ?>
<tr>
<td><?= htmlspecialchars($audit['email'] ?? 'System') ?></td>
<td><?= htmlspecialchars($audit['action']) ?></td>
<td><?= htmlspecialchars($audit['ip_address']) ?></td>
<td><?= date('d M Y H:i', strtotime($audit['created_at'])) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="4">No audit logs found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

</div>

<?php include "footer.php"; ?>
