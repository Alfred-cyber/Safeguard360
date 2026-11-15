<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

// Helper function to redirect with message
function redirect($course_id, $msg = '', $error = '') {
    $url = "course-builder.php?course_id=" . intval($course_id);
    if ($msg) $url .= "&msg=" . urlencode($msg);
    if ($error) $url .= "&error=" . urlencode($error);
    header("Location: $url");
    exit;
}

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

// Get the action
$action = $_POST['action'] ?? '';
$course_id = $_POST['course_id'] ?? 0;

try {
    switch ($action) {

        // ------------------ MODULES ------------------
        case 'add_module':
            $title = trim($_POST['title']);
            $description = trim($_POST['description'] ?? '');
            $course_id = intval($_POST['course_id']);

            if (!$title || !$course_id) {
                redirect($course_id, '', 'Title and course required');
            }

            // Determine sort_order
            $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order),0)+1 AS next_order FROM course_modules WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $sort_order = $stmt->fetchColumn();

            $stmt = $pdo->prepare("INSERT INTO course_modules (course_id, title, description, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$course_id, $title, $description, $sort_order]);

            redirect($course_id, 'Module added successfully');

        case 'edit_module':
            $module_id = intval($_POST['module_id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description'] ?? '');

            if (!$module_id || !$title) {
                redirect($course_id, '', 'Module ID and title required');
            }

            $stmt = $pdo->prepare("UPDATE course_modules SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $module_id]);

            // Get course_id to redirect
            $stmt = $pdo->prepare("SELECT course_id FROM course_modules WHERE id = ?");
            $stmt->execute([$module_id]);
            $course_id = $stmt->fetchColumn();

            redirect($course_id, 'Module updated successfully');

        case 'delete_module':
            $module_id = intval($_POST['module_id']);
            if (!$module_id) {
                redirect($course_id, '', 'Module ID required');
            }

            // Get course_id to redirect
            $stmt = $pdo->prepare("SELECT course_id FROM course_modules WHERE id = ?");
            $stmt->execute([$module_id]);
            $course_id = $stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM course_modules WHERE id = ?");
            $stmt->execute([$module_id]);

            redirect($course_id, 'Module deleted successfully');

        // ------------------ LESSONS ------------------
        case 'add_lesson':
            $module_id = intval($_POST['module_id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description'] ?? '');
            $is_mandatory = isset($_POST['is_mandatory']) ? 1 : 0;

            if (!$module_id || !$title) {
                redirect($course_id, '', 'Lesson title and module required');
            }

            // Determine sort_order
            $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order),0)+1 AS next_order FROM lessons WHERE module_id = ?");
            $stmt->execute([$module_id]);
            $sort_order = $stmt->fetchColumn();

            $stmt = $pdo->prepare("INSERT INTO lessons (module_id, title, description, sort_order, is_mandatory) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$module_id, $title, $description, $sort_order, $is_mandatory]);

            // Get course_id for redirect
            $stmt = $pdo->prepare("SELECT course_id FROM course_modules WHERE id = ?");
            $stmt->execute([$module_id]);
            $course_id = $stmt->fetchColumn();

            redirect($course_id, 'Lesson added successfully');

        case 'edit_lesson':
            $lesson_id = intval($_POST['lesson_id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description'] ?? '');
            $is_mandatory = isset($_POST['is_mandatory']) ? 1 : 0;

            if (!$lesson_id || !$title) {
                redirect($course_id, '', 'Lesson ID and title required');
            }

            $stmt = $pdo->prepare("UPDATE lessons SET title = ?, description = ?, is_mandatory = ? WHERE id = ?");
            $stmt->execute([$title, $description, $is_mandatory, $lesson_id]);

            // Get course_id for redirect
            $stmt = $pdo->prepare("
                SELECT cm.course_id
                FROM lessons l
                JOIN course_modules cm ON l.module_id = cm.id
                WHERE l.id = ?
            ");
            $stmt->execute([$lesson_id]);
            $course_id = $stmt->fetchColumn();

            redirect($course_id, 'Lesson updated successfully');

        case 'delete_lesson':
            $lesson_id = intval($_POST['lesson_id']);
            if (!$lesson_id) {
                redirect($course_id, '', 'Lesson ID required');
            }

            // Get course_id for redirect
            $stmt = $pdo->prepare("
                SELECT cm.course_id
                FROM lessons l
                JOIN course_modules cm ON l.module_id = cm.id
                WHERE l.id = ?
            ");
            $stmt->execute([$lesson_id]);
            $course_id = $stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");
            $stmt->execute([$lesson_id]);

            redirect($course_id, 'Lesson deleted successfully');

        default:
            redirect($course_id, '', 'Invalid action');

    }
} catch (PDOException $e) {
    redirect($course_id, '', 'Database error: ' . $e->getMessage());
}
