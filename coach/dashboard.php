<?php
require_once '../user/auth.php';

// Only allow 'coach'
require_role(['coach']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coach Dashboard – Safeguard360</title>
</head>
<body>
<h1>Welcome, Coach <?= htmlspecialchars($_SESSION['email']) ?></h1>
<p>This is your dashboard showing your courses, team, and incident reports.</p>
</body>
</html>

