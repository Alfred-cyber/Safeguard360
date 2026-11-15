<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

// Pagination & search
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$search_sql = $search ? "WHERE title LIKE :search" : "";

// Fetch total courses count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM courses $search_sql");
if ($search) {
    $countStmt->bindValue(':search', "%$search%");
}
$countStmt->execute();
$totalCourses = $countStmt->fetchColumn();
$totalPages = ceil($totalCourses / $limit);

// Fetch courses
$stmt = $pdo->prepare("
    SELECT *
    FROM courses
    $search_sql
    ORDER BY id DESC
    LIMIT :offset, :limit
");
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">Courses Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="ti ti-plus"></i> Add New Course
        </button>
    </div>

    <!-- Search -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search courses"
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <!-- Courses Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Validity</th>
                    <th>Passing</th>
                    <th>Certificate</th>
                    <th>Status</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($courses): ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['id'] ?></td>
                        <td><?= htmlspecialchars($course['title']) ?></td>
                        <td><?= $course['duration_minutes'] ? $course['duration_minutes'] . ' min' : '-' ?></td>
                        <td><?= $course['validity_months'] ?> months</td>
                        <td><?= $course['passing_score'] ?>%</td>
                        <td>
                            <?php if ($course['certificate_enabled']): ?>
                                <span class="badge bg-success">Enabled</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Disabled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($course['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="d-flex gap-1">

                            <!-- Build Button -->
                            <a href="course-builder.php?course_id=<?= $course['id'] ?>"
                               class="btn btn-sm btn-success"
                               title="Build Course">
                                <i class="ti ti-list"></i>
                            </a>

                            <!-- Edit Button -->
                            <button class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCourseModal<?= $course['id'] ?>">
                                <i class="ti ti-edit"></i>
                            </button>

                            <!-- Delete Button -->
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCourseModal<?= $course['id'] ?>">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- EDIT MODAL -->
                    <div class="modal fade" id="editCourseModal<?= $course['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" action="course-action.php">
                                    <input type="hidden" name="return" value="courses.php?page=<?= $page ?>&search=<?= urlencode($search) ?>">
                                    <input type="hidden" name="id" value="<?= $course['id'] ?>">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Course</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control"
                                                   value="<?= htmlspecialchars($course['title']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control"><?= htmlspecialchars($course['description']) ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label>Duration (minutes)</label>
                                                <input type="number" name="duration_minutes" class="form-control"
                                                       value="<?= $course['duration_minutes'] ?>">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Validity (months)</label>
                                                <input type="number" name="validity_months" class="form-control"
                                                       value="<?= $course['validity_months'] ?>" required>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Passing Score (%)</label>
                                                <input type="number" name="passing_score" class="form-control"
                                                       value="<?= $course['passing_score'] ?>">
                                            </div>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="certificate_enabled"
                                                   <?= $course['certificate_enabled'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">Certificate Enabled</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active"
                                                   <?= $course['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">Active</label>
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

                    <!-- DELETE MODAL -->
                    <div class="modal fade" id="deleteCourseModal<?= $course['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="course-action.php">
                                    <input type="hidden" name="return" value="courses.php?page=<?= $page ?>&search=<?= urlencode($search) ?>">
                                    <input type="hidden" name="id" value="<?= $course['id'] ?>">

                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">Delete Course</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to delete
                                        <strong><?= htmlspecialchars($course['title']) ?></strong>?
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
                <tr><td colspan="8" class="text-center">No courses found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

<!-- ADD COURSE MODAL -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="course-action.php">
                <input type="hidden" name="return" value="courses.php?page=<?= $page ?>&search=<?= urlencode($search) ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Validity (months)</label>
                            <input type="number" name="validity_months" class="form-control" value="12" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" value="70">
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="certificate_enabled" checked>
                        <label class="form-check-label">Certificate Enabled</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" checked>
                        <label class="form-check-label">Active</label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" name="action" value="add" class="btn btn-primary">Add Course</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
