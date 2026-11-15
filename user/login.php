<?php
// login.php
session_start();

// --- DB CONNECTION ---
require_once '../includes/db.php'; // Make sure path is correct

$errors = [];

// --- HANDLE LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = "Email and password are required.";
    }

    if (empty($errors)) {
        // Fetch user
        $stmt = $pdo->prepare("
            SELECT u.id, u.email, u.password_hash, u.status, r.name AS role
            FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            JOIN roles r ON ur.role_id = r.id
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors[] = "Invalid email or password.";
        } elseif ($user['status'] !== 'active') {
            $errors[] = "Your account is not active. Please verify your email or contact admin.";
        } elseif (!password_verify($password, $user['password_hash'])) {
            $errors[] = "Invalid email or password.";
        } else {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // Redirect to role-based folder dashboard
            //$role_folder = $user['role']; // e.g., 'coach', 'superadmin'
            $project_root = '/safeguard360'; // adjust if your folder is different
            $role_folder = $user['role'];
            header("Location: {$project_root}/{$role_folder}/dashboard.php");
            exit;

            header("Location: /$role_folder/dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login – Safeguard360 Football</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-green: #13DEB9;
            --secondary-green: #20c997;
        }

        body {
            background: linear-gradient(135deg, rgba(19, 222, 185, 0.05), #fff);
            min-height: 100vh;
        }

        .login-card {
            max-width: 450px;
            margin: 80px auto;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
            background-color: #fff;
        }

        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: var(--secondary-green);
            border-color: var(--secondary-green);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">

        <div class="text-center mb-4">
            <h3>
                <i class="bi bi-shield-check me-2"></i>
                <span style="color:var(--primary-green)">Safeguard</span>360
            </h3>
            <p class="text-muted">Login to your account</p>
        </div>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php endif; ?>

        <form method="post" novalidate>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Log In
            </button>

            <div class="text-center mt-3">
                <small>
                    Don't have an account?
                    <a href="register.php" class="text-decoration-none">Register here</a>
                </small>
            </div>

        </form>
    </div>
</div>

</body>
</html>
