<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

// Helper function for redirect with message
function redirect_with_msg($msg, $type = 'success') {
    $_SESSION['flash_message'] = ['type' => $type, 'msg' => $msg];
    header("Location: users.php");
    exit;
}

// Check action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $email      = trim($_POST['email'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $status     = $_POST['status'] ?? 'pending';
        $role_id    = $_POST['role_id'] ?? null;
        $temp_pass  = trim($_POST['temp_password'] ?? '');

        // Validation
        if (!$email || !$first_name || !$last_name || !$role_id) {
            redirect_with_msg('All fields except temp password are required.', 'danger');
        }

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            redirect_with_msg('Email already exists.', 'danger');
        }

        // Generate password if not provided
        if (!$temp_pass) {
            $temp_pass = bin2hex(random_bytes(4)); // 8 chars
        }

        $password_hash = password_hash($temp_pass, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (email, first_name, last_name, password_hash, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $first_name, $last_name, $password_hash, $status]);
        $user_id = $pdo->lastInsertId();

        // Assign role
        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $role_id]);

        redirect_with_msg("User created successfully. Temporary password: $temp_pass");

    } elseif ($action === 'edit') {
        $user_id    = $_POST['id'] ?? null;
        $email      = trim($_POST['email'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $status     = $_POST['status'] ?? 'pending';
        $role_id    = $_POST['role_id'] ?? null;

        if (!$user_id || !$email || !$first_name || !$last_name || !$role_id) {
            redirect_with_msg('All fields are required.', 'danger');
        }

        // Check if email exists for another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            redirect_with_msg('Email already exists for another user.', 'danger');
        }

        // Update user
        $stmt = $pdo->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, status = ? WHERE id = ?");
        $stmt->execute([$email, $first_name, $last_name, $status, $user_id]);

        // Update role
        $stmt = $pdo->prepare("UPDATE user_roles SET role_id = ? WHERE user_id = ?");
        $stmt->execute([$role_id, $user_id]);

        redirect_with_msg("User updated successfully.");

    } elseif ($action === 'delete') {
        $user_id = $_POST['id'] ?? null;
        if (!$user_id) {
            redirect_with_msg('Invalid user ID.', 'danger');
        }

        // Prevent deleting self
        if ($user_id == $_SESSION['user_id']) {
            redirect_with_msg("You cannot delete your own account.", 'danger');
        }

        // Delete user (user_roles will cascade)
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        redirect_with_msg("User deleted successfully.");

    } else {
        redirect_with_msg('Invalid action.', 'danger');
    }
} else {
    redirect_with_msg('Invalid request.', 'danger');
}
