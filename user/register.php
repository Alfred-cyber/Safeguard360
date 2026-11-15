<?php
// register.php

// --- DB CONNECTION ---
$host = "localhost";
$db   = "safeguard360";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed.");
}

// --- UUID v4 GENERATOR ---
function uuidv4()
{
    $data = random_bytes(16);

    // Version 4
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    // Variant
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$errors = [];
$success = false;
$verificationToken = null;

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $email     = strtolower(trim($_POST['email'] ?? ''));
    $password  = $_POST['password'] ?? '';
    $role      = $_POST['role'] ?? '';

    $allowedRoles = ['coach', 'referee', 'volunteer', 'parent'];

    if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!in_array($role, $allowedRoles, true)) {
        $errors[] = "Invalid role selected.";
    }

    // Check email uniqueness
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "An account with this email already exists.";
    }

    // --- CREATE USER ---
    if (empty($errors)) {

        $passwordHash     = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = bin2hex(random_bytes(16));
        $user_uuid        = uuidv4();

        $pdo->beginTransaction();

        try {
            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users 
                (uuid, email, password_hash, first_name, last_name, status, verification_token)
                VALUES (?, ?, ?, ?, ?, 'pending', ?)
            ");
            $stmt->execute([
                $user_uuid,
                $email,
                $passwordHash,
                $firstName,
                $lastName,
                $verificationToken
            ]);

            $userId = $pdo->lastInsertId();

            // Assign role
            $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = ?");
            $stmt->execute([$role]);
            $roleId = $stmt->fetchColumn();

            if (!$roleId) {
                throw new Exception("Invalid role configuration.");
            }

            $stmt = $pdo->prepare("
                INSERT INTO user_roles (user_id, role_id)
                VALUES (?, ?)
            ");
            $stmt->execute([$userId, $roleId]);

            $pdo->commit();
            $success = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register – Safeguard360 Football</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-green: #13DEB9;
            --secondary-green: #20c997;
            --light-green: #e6faf5;
        }

        body {
            background: linear-gradient(135deg, rgba(19, 222, 185, 0.05), #fff);
            min-height: 100vh;
        }

        .register-card {
            max-width: 1100px;
            margin: auto;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
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

<div class="container-xl py-5">
    <div class="card register-card overflow-hidden">
        <div class="row g-0">

            <!-- LEFT COLUMN -->
            <div class="col-md-5 d-none d-md-flex align-items-center"
                 style="background:
                     linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
                     url('https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=900&q=80');
                     background-size: cover;
                     background-position: center;">

                <div class="p-4 text-white">
                    <h4 class="fw-bold mb-3">Safeguarding Starts With You</h4>
                    <p class="mb-3">
                        Join grassroots football professionals creating safer environments.
                    </p>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Accredited safeguarding training
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Digital certification & renewal tracking
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Trusted compliance platform
                        </li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-7">
                <div class="p-4 p-md-5">

                    <div class="text-center mb-4">
                        <h3>
                            <i class="bi bi-shield-check me-2"></i>
                            <span style="color:var(--primary-green)">Safeguard</span>360
                        </h3>
                        <p class="text-muted">Create your safeguarding account</p>
                    </div>

                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <?= implode('<br>', $errors) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h5 class="mb-2">Registration Successful</h5>
                            <p class="mb-1">Temporary verification token:</p>
                            <code><?= htmlspecialchars($verificationToken) ?></code>
                        </div>
                    <?php else: ?>

                    <form method="post" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Registering as</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select role</option>
                                <option value="coach">Coach</option>
                                <option value="referee">Referee</option>
                                <option value="volunteer">Volunteer</option>
                                <option value="parent">Parent</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Create Account
                        </button>

                        <div class="text-center mt-3">
                            <small>
                                Already registered?
                                <a href="login.php" class="text-decoration-none">Log in</a>
                            </small>
                        </div>
                    </form>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
