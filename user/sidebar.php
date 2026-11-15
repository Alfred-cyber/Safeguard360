<!-- sidebar.php -->
<?php
require_once 'config/navigation.php';
require_once 'includes/auth.php';

requireLogin(); // Make sure user is logged in

$user_role = $_SESSION['user_role'] ?? 'volunteer';
$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$navigation = getNavigation($user_role);
?>

<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="dashboard.php" class="text-nowrap logo-img">
                <img src="assets/images/logo.svg" width="180" alt="Safeguard360">
            </a>
        </div>
        
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <?php foreach ($navigation as $section => $items): ?>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu"><?php echo $section; ?></span>
                    </li>
                    
                    <?php foreach ($items as $item): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?php echo $item['url']; ?>">
                                <span><i class="<?php echo $item['icon']; ?>"></i></span>
                                <span class="hide-menu"><?php echo $item['name']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
            
            <!-- Simple User Info -->
            <div class="user-info p-3 mt-5">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2">
                            <i class="ti ti-user text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold"><?php echo htmlspecialchars($user_name); ?></p>
                        <small class="text-muted"><?php echo ucfirst($user_role); ?></small>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger w-100 mt-3">
                    <i class="ti ti-logout me-2"></i> Logout
                </a>
            </div>
        </nav>
    </div>
</aside>