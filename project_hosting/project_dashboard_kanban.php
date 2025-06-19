<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$ideas = [];
if (file_exists('ideas.json')) {
    $ideas = json_decode(file_get_contents('ideas.json'), true);
}

// Normalize ideas into status categories
$grouped_projects = [
    'Not Started' => [],
    'In Progress' => [],
    'Completed' => []
];

foreach ($ideas as $idea) {
    $status = strtolower(trim($idea['status'] ?? 'not_started'));

    if ($status === 'in_progress') {
        $grouped_projects['In Progress'][] = $idea;
    } elseif ($status === 'completed') {
        $grouped_projects['Completed'][] = $idea;
    } else {
        $grouped_projects['Not Started'][] = $idea;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .kanban-board {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .kanban-column {
            background: #f0fff5;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            min-height: 300px;
        }
        .kanban-column h3 {
            color: #921224;
            margin-bottom: 10px;
        }
        .project-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .project-card h4 {
            margin-bottom: 5px;
            font-size: 1rem;
        }
        .project-card p {
            font-size: 0.9rem;
            color: #555;
        }
        .badge {
            font-size: 0.75rem;
            background: #eee;
            border-radius: 5px;
            padding: 2px 6px;
            margin-right: 5px;
        }
        .back-button {
            float: right;
            background: #921224;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <main class="main-content">
        <header class="main-header">
            <h1>Project Dashboard</h1>
            <a href="dashboard.php" class="back-button">← Back to Dashboard</a>
        </header>

        <div class="kanban-board">
            <?php foreach ($grouped_projects as $status => $list): ?>
                <div class="kanban-column">
                    <h3><?= $status ?></h3>
                    <?php if (empty($list)): ?>
                        <p>No projects.</p>
                    <?php else: ?>
                        <?php foreach ($list as $idea): ?>
                            <div class="project-card">
                                <h4><?= htmlspecialchars($idea['title']) ?></h4>
                                <p><?= htmlspecialchars($idea['short_desc'] ?? '') ?></p>
                                <?php if (!empty($idea['tags'])): ?>
                                    <div class="tags">
                                        <?php foreach (explode(',', $idea['tags']) as $tag): ?>
                                            <span class="badge"><?= htmlspecialchars(trim($tag)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>
