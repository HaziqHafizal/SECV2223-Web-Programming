<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Load and optionally filter/sort ideas
$idea_file = "ideas.json";
$ideas = file_exists($idea_file) ? json_decode(file_get_contents($idea_file), true) : [];

$statusFilter = $_GET['status'] ?? '';
$sortOrder = $_GET['sort'] ?? '';

// Normalize status values
foreach ($ideas as &$idea) {
    $raw = strtolower(trim($idea['status'] ?? ''));
    if ($raw === 'in progress' || $raw === 'in_progress') {
        $idea['status'] = 'In Progress';
    } elseif ($raw === 'completed') {
        $idea['status'] = 'Completed';
    } else {
        $idea['status'] = 'Not Started';
    }
}
unset($idea);

// Apply filter
if (!empty($statusFilter)) {
    $ideas = array_filter($ideas, function ($p) use ($statusFilter) {
        return $p['status'] === $statusFilter;
    });
}


if ($sortOrder === 'asc') {
    usort($ideas, fn($a, $b) => strcmp($a['title'], $b['title']));
} elseif ($sortOrder === 'desc') {
    usort($ideas, fn($a, $b) => strcmp($b['title'], $a['title']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Projects | IdeaPlatform</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            border-radius: 10px;
            margin-bottom: 5px;
        }
        .in-progress { background-color: #2a9d8f; }
        .completed { background-color: #6c757d; }
        .not-started { background-color: #e76f51; }
    </style>
</head>
<body>
<div class="dashboard-container">

<button id="sidebarToggle" class="sidebar-toggle" aria-label="Open Menu">
    <i class="fas fa-bars"></i>
</button>

<div id="sidebarOverlay" class="sidebar-overlay hidden">
    <aside class="sidebar-drawer">
        <div class="sidebar-header">
            <h2>IdeaHub</h2>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="../dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="idea.php"><i class="fas fa-lightbulb"></i> Ideas</a></li>
                <li><a href="projectList.php"><i class="fas fa-diagram-project"></i> Projects</a></li>
                <li><a href="../collaborators.php"><i class="fas fa-handshake"></i> Collaboration</a></li>
                <li><a href="../Login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
</div>

<main class="main-content">
    <header class="main-header">
        <h1>My Projects</h1>
    </header>

    <section class="projects-section">
        <form method="GET" style="margin-bottom: 20px;">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status">
                <option value="">-- All --</option>
                <option value="Not Started" <?= $statusFilter === 'Not Started' ? 'selected' : '' ?>>Not Started</option>
                <option value="In Progress" <?= $statusFilter === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>

            <label for="sort">Sort by Title:</label>
            <select name="sort" id="sort">
                <option value="">-- None --</option>
                <option value="asc" <?= $sortOrder === 'asc' ? 'selected' : '' ?>>A to Z</option>
                <option value="desc" <?= $sortOrder === 'desc' ? 'selected' : '' ?>>Z to A</option>
            </select>

            <button type="submit">Apply</button>
        </form>

        <?php if (empty($ideas)): ?>
            <p>No projects found.</p>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($ideas as $index => $project): ?>
                    <div class="project-card">
                        <h3><?= htmlspecialchars($project['title'] ?? 'Untitled') ?></h3>
                        <?php
                            $status = $project['status'] ?? 'Not Started';
                            $statusClass = strtolower(str_replace(' ', '-', $status));
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= strtoupper($status) ?></span>
                        <p><?= htmlspecialchars($project['short_desc'] ?? 'No description.') ?></p>

                        <div class="project-meta">
                            <span class="badge <?= ($project['visibility'] ?? 'public') === 'public' ? 'badge-public' : 'badge-private' ?>">
                                <i class="fas fa-eye<?= ($project['visibility'] ?? 'public') === 'private' ? '-slash' : '' ?>"></i>
                                <?= ucfirst($project['visibility'] ?? 'public') ?>
                            </span>

                            <?php if (!empty($project['file']) && file_exists($project['file'])): ?>
                                <span class="badge file">
                                    <i class="fas fa-file"></i>
                                    <a href="<?= $project['file'] ?>" target="_blank">Download</a>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($project['tags'])):
                                $tags = explode(',', $project['tags']);
                                foreach ($tags as $tag): ?>
                                    <span class="badge"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <a href="edit.php?index=<?= $index ?>" class="project-link"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?index=<?= $index ?>" class="project-link delete" onclick="return confirm('Are you sure you want to delete this project?');">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    toggleBtn.addEventListener('click', () => {
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            overlay.classList.add('hidden');
        }
    });
</script>
</body>
</html>
