<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$ideas = [];
if (file_exists('Idea/ideas.json')) {
    $ideas = json_decode(file_get_contents('Idea/ideas.json'), true);
}

// Normalize status values and group
$grouped_projects = [
    'Not Started' => [],
    'In Progress' => [],
    'Completed' => []
];

foreach ($ideas as $index => $idea) {
    $idea['index'] = $index; // ✅ preserve index

    $raw_status = strtolower(trim($idea['status'] ?? 'not_started'));

    switch ($raw_status) {
        case 'in progress':
        case 'in_progress':
            $idea['status'] = 'In Progress';
            $grouped_projects['In Progress'][] = $idea;
            break;

        case 'completed':
            $idea['status'] = 'Completed';
            $grouped_projects['Completed'][] = $idea;
            break;

        default:
            $idea['status'] = 'Not Started';
            $grouped_projects['Not Started'][] = $idea;
            break;
    }
}

=======

<?php
$projects = [];

$data_path = '../chatbot_idea/ideas.json'; // adjust the path as needed

if (file_exists($data_path)) {
    $json = file_get_contents($data_path);
    $projects = json_decode($json, true);
}

function displayProjects($projects, $status) {
    foreach ($projects as $project) {
        if (isset($project["status"]) && $project["status"] === $status) {
            echo "<div class='kanban-card'>";
            echo "<h3>" . htmlspecialchars($project["title"]) . "</h3>";
            if (isset($project["deadline"])) {
                echo "<p><strong>Deadline:</strong> " . htmlspecialchars($project["deadline"]) . "</p>";
            }
            echo "<form method='post' action='#'><button>Share</button></form>";
            echo "</div>";
        }
    }
}
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-header h1 {
            color: #921224;
        }
        .back-button {
            background: #921224;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .kanban-board {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .kanban-column {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            flex: 1;
        }
        .kanban-column h3 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            color: #921224;
        }
        .project-card {
            background: #f5f5f5;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .project-card h4 {
            margin: 0 0 5px;
            font-size: 1rem;
            color: #333;
        }
        .project-card p {
            margin: 0 0 10px;
            font-size: 0.9rem;
            color: #555;
        }
        .project-card select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
=======
    <title>Project Board</title>
    <link rel="stylesheet" href="projectStyle.css">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .kanban-board {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .kanban-column {
            background: #EBF5EE;
            flex: 1;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .kanban-column h2 {
            text-align: center;
            color: #921224;
        }

        .kanban-card {
            background: #fff;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .kanban-card h3 {
            margin: 0 0 10px;
        }

        .kanban-card button {
            background-color: #921224;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .kanban-card button:hover {
            background-color: #BCE0DA;
            color: #921224;
        }

        .back-button {
            text-decoration: none;
            padding: 8px 14px;
            background-color: #921224;
            color: white;
            border-radius: 4px;
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
        }
    </style>
</head>
<body>
<<<<<<< HEAD
    <div class="main-header">
        <h1>Project Dashboard</h1>
        <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>
    </div>

    <div class="kanban-board">
        <?php foreach ($grouped_projects as $status => $list): ?>
            <div class="kanban-column">
                <h3><?= $status ?></h3>
                <?php if (empty($list)): ?>
                    <p style="color:#aaa; font-size:0.9rem;">No projects.</p>
                <?php else: ?>
                    <?php foreach ($list as $idea): ?>
                        <div class="project-card">
                            <h4><?= htmlspecialchars($idea['title']) ?></h4>
                            <p><?= htmlspecialchars($idea['short_desc'] ?? '') ?></p>
                            <form method="POST" action="update_status.php">
                                <input type="hidden" name="index" value="<?= $idea['index'] ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Not Started" <?= $idea['status'] === 'Not Started' ? 'selected' : '' ?>>Not Started</option>
                                    <option value="In Progress" <?= $idea['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Completed" <?= $idea['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
=======

<div class="header">
    <h1>Project Dashboard</h1>
    <a class="back-button" href="/dashboardw.php">← Back to Dashboard</a>
    
</div>

<div class="kanban-board">
    <div class="kanban-column">
        <h2>Not Started</h2>
        <?php displayProjects($projects, "Not Started"); ?>
    </div>
    <div class="kanban-column">
        <h2>In Progress</h2>
        <?php displayProjects($projects, "In Progress"); ?>
    </div>
    <div class="kanban-column">
        <h2>Completed</h2>
        <?php displayProjects($projects, "Completed"); ?>
    </div>
</div>

>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
</body>
</html>
