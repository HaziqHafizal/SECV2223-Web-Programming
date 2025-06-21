<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $new_status = $_POST['status'] ?? '';

    // Define allowed statuses
    $allowed_statuses = ['Not Started', 'In Progress', 'Completed'];

    if ($index >= 0 && in_array($new_status, $allowed_statuses)) {
        $file = 'Idea/ideas.json';
        if (file_exists($file)) {
            $ideas = json_decode(file_get_contents($file), true);

            if (isset($ideas[$index])) {
                $ideas[$index]['status'] = $new_status;
                file_put_contents($file, json_encode($ideas, JSON_PRETTY_PRINT));
                header("Location: project_dashboard_kanban.php");
                exit();
            }
        }
    }
    // If anything goes wrong
    header("Location: project_dashboard_kanban.php?error=update");
    exit();
}
?>
