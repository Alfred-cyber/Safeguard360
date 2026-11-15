<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

// Pagination & search
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$search_sql = $search ? "WHERE u.email LIKE :search OR u.first_name LIKE :search OR u.last_name LIKE :search" : "";

// Fetch total users count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users u $search_sql");
if ($search) {
    $countStmt->bindValue(':search', "%$search%");
}
$countStmt->execute();
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// Fetch users
$stmt = $pdo->prepare("
    SELECT u.id, u.first_name, u.last_name, u.email, u.status, r.name AS role
    FROM users u
    LEFT JOIN user_roles ur ON u.id = ur.user_id
    LEFT JOIN roles r ON ur.role_id = r.id
    $search_sql
    ORDER BY u.id DESC
    LIMIT :offset, :limit
");
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all roles for Add/Edit
$rolesStmt = $pdo->query("SELECT id, name FROM roles ORDER BY name");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">Users Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="ti ti-user-plus"></i> Add New User
        </button>
    </div>

    <!-- Search -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? '-') ?></td>
                            <td>
                                <?php if($user['status'] == 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php elseif($user['status'] == 'inactive'): ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php elseif($user['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="user-action.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <div class="mb-3">
                                                <label>First Name</label>
                                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Last Name</label>
                                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" <?= $user['status']=='active' ? 'selected' : '' ?>>Active</option>
                                                    <option value="inactive" <?= $user['status']=='inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    <option value="pending" <?= $user['status']=='pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="suspended" <?= $user['status']=='suspended' ? 'selected' : '' ?>>Suspended</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Assign Role</label>
                                                <select name="role_id" class="form-control" required>
                                                    <option value="">-- Select Role --</option>
                                                    <?php foreach($roles as $role): ?>
                                                        <option value="<?= $role['id'] ?>" <?= ($role['name'] == $user['role']) ? 'selected' : '' ?>>
                                                            <?= ucfirst($role['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="action" value="edit" class="btn btn-info">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="user-action.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Delete User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>?
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="action" value="delete" class="btn btn-danger">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for($i=1;$i<=$totalPages;$i++): ?>
                <li class="page-item <?= $i==$page?'active':'' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="user-action.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="pending">Pending</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Assign Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <?php foreach($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= ucfirst($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Temporary Password (optional)</label>
                        <input type="text" name="temp_password" class="form-control" placeholder="Leave blank to auto-generate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="add" class="btn btn-primary">Add User</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
