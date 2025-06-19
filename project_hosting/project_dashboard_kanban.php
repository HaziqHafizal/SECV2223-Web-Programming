
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        }
    </style>
</head>
<body>

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

</body>
</html>
