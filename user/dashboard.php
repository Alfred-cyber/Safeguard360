<?php
// dashboard.php (or any protected page)
require_once 'includes/auth.php';
requireLogin();
requireRole(['superadmin', 'club_admin', 'coach', 'volunteer']); // Who can access this page
?>