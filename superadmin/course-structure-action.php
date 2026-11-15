<?php
require_once '../includes/db.php';
require_once '../user/auth.php';
require_role(['superadmin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: courses.php');
    exit;
}

$action = $_POST['action'] ?? '';

try {

    /* =========================
       ADD MODULE
    ========================== */
    if ($action === 'add_module') {

        $course_id  = (int)($_POST['course_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($course_id <= 0 || $title === '') {
            throw new Exception('Invalid module data.');
        }

        // Get next sort order
        $stmtOrder = $pdo->prepare("
            SELECT COALESCE(MAX(sort_order), 0) + 1
            FROM course_modules
            WHERE course_id = ?
        ");
        $stmtOrder->execute([$course_id]);
        $nextOrder = $stmtOrder->fetchColumn();

        $stmt = $pdo->prepare("
            INSERT INTO course_modules (course_id, title, description, sort_order)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([$course_id, $title, $description, $nextOrder]);
    }

    /* =========================
       DELETE MODULE
    ========================== */
    elseif ($action === 'delete_module') {

        $module_id = (int)($_POST['module_id'] ?? 0);

        if ($module_id <= 0) {
            throw new Exception('Invalid module.');
        }

        $stmt = $pdo->prepare("DELETE FROM course_modules WHERE id = ?");
        $stmt->execute([$module_id]);
    }

    /* =========================
       ADD LESSON
    ========================== */
    elseif ($action === 'add_lesson') {

        $module_id  = (int)($_POST['module_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_mandatory = isset($_POST['is_mandatory']) ? 1 : 0;

        if ($module_id <= 0 || $title === '') {
            throw new Exception('Invalid lesson data.');
        }

        // Get next sort order
        $stmtOrder = $pdo->prepare("
            SELECT COALESCE(MAX(sort_order), 0) + 1
            FROM lessons
            WHERE module_id = ?
        ");
        $stmtOrder->execute([$module_id]);
        $nextOrder = $stmtOrder->fetchColumn();

        $stmt = $pdo->prepare("
            INSERT INTO lessons (module_id, title, description, sort_order, is_mandatory)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([$module_id, $title, $description, $nextOrder, $is_mandatory]);
    }

    /* =========================
       EDIT LESSON
    ========================== */
    elseif ($action === 'edit_lesson') {

        $lesson_id  = (int)($_POST['lesson_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_mandatory = isset($_POST['is_mandatory']) ? 1 : 0;

        if ($lesson_id <= 0 || $title === '') {
            throw new Exception('Invalid lesson data.');
        }

        $stmt = $pdo->prepare("
            UPDATE lessons
            SET title = ?, description = ?, is_mandatory = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $description, $is_mandatory, $lesson_id]);
    }

    /* =========================
       DELETE LESSON
    ========================== */
    elseif ($action === 'delete_lesson') {

        $lesson_id = (int)($_POST['lesson_id'] ?? 0);

        if ($lesson_id <= 0) {
            throw new Exception('Invalid lesson.');
        }

        $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");
        $stmt->execute([$lesson_id]);
    }

    elseif ($action === 'add_step') {

        $lesson_id = (int)$_POST['lesson_id'];
        $title = trim($_POST['title']);
        $step_type = $_POST['step_type'];
        $content = $_POST['content'] ?? null;
        $media_url = $_POST['media_url'] ?? null;
        $is_required = isset($_POST['is_required']) ? 1 : 0;
    
        $stmt = $pdo->prepare("
            SELECT COALESCE(MAX(sort_order),0)+1 
            FROM lesson_steps WHERE lesson_id = ?
        ");
        $stmt->execute([$lesson_id]);
        $nextOrder = $stmt->fetchColumn();
    
        $insert = $pdo->prepare("
            INSERT INTO lesson_steps
            (lesson_id, step_type, title, content, media_url, sort_order, is_required)
            VALUES (?,?,?,?,?,?,?)
        ");
        $insert->execute([
            $lesson_id,
            $step_type,
            $title,
            $content,
            $media_url,
            $nextOrder,
            $is_required
        ]);
    
        header("Location: lesson-builder.php?lesson_id=" . $lesson_id);
        exit;
    }
    
    elseif ($action === 'delete_step') {
    
        $step_id = (int)$_POST['step_id'];
    
        $stmt = $pdo->prepare("SELECT lesson_id FROM lesson_steps WHERE id = ?");
        $stmt->execute([$step_id]);
        $lesson_id = $stmt->fetchColumn();
    
        $pdo->prepare("DELETE FROM lesson_steps WHERE id = ?")
            ->execute([$step_id]);
    
        header("Location: lesson-builder.php?lesson_id=" . $lesson_id);
        exit;
    }
    

} catch (Exception $e) {
    // Optional logging later
    // error_log($e->getMessage());
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
