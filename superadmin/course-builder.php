<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
if (!$course_id) {
    die('Invalid course');
}

/* Fetch course */
$courseStmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$courseStmt->execute([$course_id]);
$course = $courseStmt->fetch(PDO::FETCH_ASSOC);
if (!$course) {
    die('Course not found');
}

/* Fetch modules */
$modulesStmt = $pdo->prepare("SELECT * FROM course_modules WHERE course_id = ? ORDER BY sort_order ASC");
$modulesStmt->execute([$course_id]);
$modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

/* Fetch lessons grouped by module */
$lessonsStmt = $pdo->prepare("SELECT * FROM lessons WHERE module_id IN (SELECT id FROM course_modules WHERE course_id = ?) ORDER BY sort_order ASC");
$lessonsStmt->execute([$course_id]);
$lessonsRaw = $lessonsStmt->fetchAll(PDO::FETCH_ASSOC);

$lessonsByModule = [];
foreach ($lessonsRaw as $lesson) {
    $lessonsByModule[$lesson['module_id']][] = $lesson;
}
?>

<?php include "header.php"; ?>

<div class="container-fluid">

    <!-- Display Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Course Header -->
    <div class="mb-4">
        <h3 class="fw-semibold"><?= htmlspecialchars($course['title']) ?></h3>
        <p class="text-muted mb-1"><?= htmlspecialchars($course['description']) ?></p>
        <small>
            Duration: <?= $course['duration_minutes'] ?> mins ·
            Validity: <?= $course['validity_months'] ?> months ·
            Status:
            <?= $course['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?>
        </small>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold">Course Structure</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
            <i class="ti ti-plus"></i> Add Module
        </button>
    </div>

    <!-- MODULES -->
    <?php if ($modules): ?>
        <?php foreach ($modules as $module): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <!-- Module Header -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-semibold mb-0"><?= htmlspecialchars($module['title']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($module['description']) ?></small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModule<?= $module['id'] ?>">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModule<?= $module['id'] ?>">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <hr>

                    <!-- Lessons -->
                    <?php if (!empty($lessonsByModule[$module['id']])): ?>
                        <ul class="list-group mb-3">
                            <?php foreach ($lessonsByModule[$module['id']] as $lesson): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($lesson['title']) ?></strong>
                                        <?php if ($lesson['is_mandatory']): ?>
                                            <span class="badge bg-warning text-dark ms-2">Mandatory</span>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($lesson['description']) ?></small>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editLesson<?= $lesson['id'] ?>">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-secondary"
    onclick="location.href='lesson-builder.php?lesson_id=<?= $lesson['id'] ?>'">
    <i class="ti ti-layout"></i>
</button>

                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteLesson<?= $lesson['id'] ?>">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </li>

                                <!-- Edit Lesson Modal -->
                                <div class="modal fade" id="editLesson<?= $lesson['id'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="course-action.php">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Lesson</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                                                    <input type="hidden" name="action" value="edit_lesson">
                                                    <div class="mb-3">
                                                        <label>Title</label>
                                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($lesson['title']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Description</label>
                                                        <textarea name="description" class="form-control"><?= htmlspecialchars($lesson['description']) ?></textarea>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_mandatory" <?= $lesson['is_mandatory'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label">Mandatory</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-info">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Lesson Modal -->
                                <div class="modal fade" id="deleteLesson<?= $lesson['id'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="course-action.php">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Delete Lesson</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Delete <strong><?= htmlspecialchars($lesson['title']) ?></strong>?
                                                    <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                                                    <input type="hidden" name="action" value="delete_lesson">
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No lessons yet.</p>
                    <?php endif; ?>

                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addLesson<?= $module['id'] ?>">
                        <i class="ti ti-plus"></i> Add Lesson
                    </button>
                </div>
            </div>

            <!-- Add Lesson Modal -->
            <div class="modal fade" id="addLesson<?= $module['id'] ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="course-action.php">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Lesson</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                                <input type="hidden" name="action" value="add_lesson">
                                <div class="mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_mandatory" checked>
                                    <label class="form-check-label">Mandatory</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Add Lesson</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">No modules yet.</div>
    <?php endif; ?>

</div>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="course-action.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add Module</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                    <input type="hidden" name="action" value="add_module">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add Module</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
