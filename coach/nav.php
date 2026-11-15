<?php
require_once '../user/auth.php';
require_role([$_SESSION['role']]);

$navigation_by_role = [
    'superadmin' => [ /* ... */ ],
    'club_admin' => [ /* ... */ ],
    'coach' => [ /* ... */ ],
    'volunteer' => [ /* ... */ ],
];

// Get current user's navigation
$nav = $navigation_by_role[$_SESSION['role']] ?? [];
?>

<nav>
    <ul>
    <?php foreach ($nav as $section => $links): ?>
        <li><strong><?= htmlspecialchars($section) ?></strong>
            <ul>
            <?php foreach ($links as $link): ?>
                <li>
                    <a href="<?= htmlspecialchars($link['url']) ?>">
                        <?= htmlspecialchars($link['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>
