<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
if (!$lesson_id) die('Invalid lesson');

/* Fetch lesson */
$stmt = $pdo->prepare("
    SELECT l.*, m.title AS module_title, c.title AS course_title
    FROM lessons l
    JOIN course_modules m ON l.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE l.id = ?
");
$stmt->execute([$lesson_id]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$lesson) die('Lesson not found');

/* Fetch steps */
$stepsStmt = $pdo->prepare("
    SELECT * FROM lesson_steps
    WHERE lesson_id = ?
    ORDER BY sort_order ASC
");
$stepsStmt->execute([$lesson_id]);
$steps = $stepsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container-fluid">

    <h4 class="mb-3">
        <?= htmlspecialchars($lesson['course_title']) ?>
        → <?= htmlspecialchars($lesson['module_title']) ?>
        → <?= htmlspecialchars($lesson['title']) ?>
    </h4>

    <div class="d-flex justify-content-between mb-3">
        <h5>Lesson Steps</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStepModal">
            Add Step
        </button>
    </div>

    <?php if ($steps): ?>
    <?php foreach ($steps as $step): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($step['title']) ?></strong>

                        <span class="badge bg-secondary ms-2">
                            <?= strtoupper($step['step_type']) ?>
                        </span>

                        <?php if ($step['is_required']): ?>
                            <span class="badge bg-warning text-dark ms-1">
                                Required
                            </span>
                        <?php endif; ?>

                        <br>
                        <small class="text-muted">
                            Order: <?= $step['sort_order'] ?>
                        </small>
                    </div>

                    <div>
                        <button class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="collapse"
                            data-bs-target="#preview<?= $step['id'] ?>">
                            Preview
                        </button>

                        <form method="post"
                            action="course-structure-action.php"
                            class="d-inline">

                            <input type="hidden" name="step_id"
                                   value="<?= $step['id'] ?>">
                            <input type="hidden" name="action"
                                   value="delete_step">

                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this step?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- PREVIEW AREA -->
                <div class="collapse mt-3" id="preview<?= $step['id'] ?>">
                    <div class="border rounded p-3 bg-light">

                        <?php
                        switch ($step['step_type']) {

                            case 'text':
                                echo "<div>" . nl2br(htmlspecialchars($step['content'])) . "</div>";
                                break;

                                case 'video':

                                    if ($step['media_url']) {
                                
                                        $url = trim($step['media_url']);
                                
                                        // Convert normal YouTube URL to embed format
                                        if (strpos($url, 'youtube.com/watch') !== false) {
                                            parse_str(parse_url($url, PHP_URL_QUERY), $params);
                                            if (isset($params['v'])) {
                                                $url = "https://www.youtube.com/embed/" . $params['v'];
                                            }
                                        }
                                
                                        // Convert youtu.be short link
                                        if (strpos($url, 'youtu.be/') !== false) {
                                            $videoId = basename(parse_url($url, PHP_URL_PATH));
                                            $url = "https://www.youtube.com/embed/" . $videoId;
                                        }
                                
                                        echo '<div class="ratio ratio-16x9">
                                                <iframe src="' . htmlspecialchars($url) . '" 
                                                    allowfullscreen
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                                </iframe>
                                              </div>';
                                
                                    } else {
                                        echo "<small class='text-danger'>No video URL provided.</small>";
                                    }
                                
                                    break;
                                

                            case 'audio':
                                if ($step['media_url']) {
                                    echo '<audio controls style="width:100%">
                                            <source src="' . htmlspecialchars($step['media_url']) . '">
                                          </audio>';
                                } else {
                                    echo "<small class='text-danger'>No audio URL provided.</small>";
                                }
                                break;

                            case 'pdf':
                                if ($step['media_url']) {
                                    echo '<iframe src="' . htmlspecialchars($step['media_url']) . '" 
                                            width="100%" height="500px"></iframe>';
                                } else {
                                    echo "<small class='text-danger'>No PDF URL provided.</small>";
                                }
                                break;

                            case 'embed':
                                echo $step['content'];
                                break;

                            case 'quiz':
                                echo "<div class='alert alert-info'>
                                        Quiz step — manage quiz separately.
                                      </div>";
                                break;

                            default:
                                echo "Unknown step type.";
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">No steps yet.</div>
<?php endif; ?>


</div>

<!-- Add Step Modal -->
<div class="modal fade" id="addStepModal">
<div class="modal-dialog">
<div class="modal-content">
<form method="post" action="course-structure-action.php">

<div class="modal-header">
    <h5>Add Step</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
<input type="hidden" name="action" value="add_step">

<div class="mb-3">
<label>Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label>Type</label>
<select name="step_type" class="form-select" required>
    <option value="text">Text</option>
    <option value="video">Video</option>
    <option value="audio">Audio</option>
    <option value="pdf">PDF</option>
    <option value="quiz">Quiz</option>
    <option value="embed">Embed</option>
</select>
</div>

<div class="mb-3">
<label>Content (for text/embed)</label>
<textarea name="content" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Media URL (video/pdf/audio)</label>
<input type="text" name="media_url" class="form-control">
</div>

<div class="form-check">
<input type="checkbox" name="is_required" class="form-check-input" checked>
<label class="form-check-label">Required</label>
</div>

</div>

<div class="modal-footer">
<button class="btn btn-primary">Add Step</button>
</div>

</form>
</div>
</div>
</div>

<?php include "footer.php"; ?>
