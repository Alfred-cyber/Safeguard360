<?php
// includes/auth.php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in.
 *
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login to access a page.
 * Redirects to login.php if not logged in.
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: /safeguard360/user/login.php");
        exit;
    }
}

/**
 * Require specific role(s) to access a page.
 * Can accept a string for a single role or an array for multiple roles.
 *
 * @param string|array $allowed_roles
 */
function require_role($allowed_roles) {
    require_login(); // Ensure user is logged in first

    $user_role = $_SESSION['role'] ?? null;

    // Normalize $allowed_roles to always be an array
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }

    if (!in_array($user_role, $allowed_roles)) {
        // Optional: redirect to a "no access" page or dashboard
        header("Location: /safeguard360/user/login.php");
        exit;
    }
}

/**
 * Logout function
 */
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: /safeguard360/user/login.php");
    exit;
}
