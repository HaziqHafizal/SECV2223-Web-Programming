<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login/login.php");
    exit();
}

<<<<<<< HEAD
// Get user info
=======
// Fetch user data
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

<<<<<<< HEAD
// Load project data from JSON
$projects = [];
$data_path = 'Idea/ideas.json';
=======
// Fetch projects/ideas
$projects = [];
$data_path = 'ideas.json';
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
if (file_exists($data_path)) {
    $json = file_get_contents($data_path);
    $projects = json_decode($json, true);
}

<<<<<<< HEAD
// Status counters
=======
// Count projects by status
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
$status_counts = [
    'Not Started' => 0,
    'In Progress' => 0,
    'Completed' => 0
];

<<<<<<< HEAD
// Normalize and count
foreach ($projects as &$project) {
    $status = strtolower(trim($project['status'] ?? 'not_started'));

    if ($status === 'in_progress' || $status === 'in progress') {
        $project['status'] = 'In Progress';
        $status_counts['In Progress']++;
    } elseif ($status === 'completed') {
        $project['status'] = 'Completed';
        $status_counts['Completed']++;
    } else {
        $project['status'] = 'Not Started';
        $status_counts['Not Started']++;
    }
}
unset($project);

=======
foreach ($projects as $project) {
    if (isset($project['status'])) {
        $status_counts[$project['status']]++;
    }
}
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Invite Collaboration</title>

    <!-- ✅ Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Global layout styles -->
    <link rel="stylesheet" href="dashboard.css">

    <!-- Collaborator-specific styles -->
    <link rel="stylesheet" href="Collab/inv_style.css">
</head>


<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>IdeaHub</h2>
            <p>Welcome, <?= htmlspecialchars($user['username']); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="project_dashboard_kanban.php"><i class="fas fa-project-diagram"></i> Project Status</a></li>
                <li><a href="Idea/idea.php"><i class="fas fa-lightbulb"></i> Ideas</a></li>
                <li><a href="collaborators.php"><i class="fas fa-handshake"></i> Collaboration</a></li>
                <li><a href="Login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <h1>Dashboard</h1>
            <div class="user-actions">
                <span class="notification-badge"><i class="fas fa-bell"></i> <span>3</span></span>
                <div class="user-profile">
                    <img src="https://via.placeholder.com/40" alt="User Profile">
                </div>
            </div>
        </header>

        <!-- Stats -->
        <section class="stats-section">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #BCE0DA;">
                    <i class="fas fa-lightbulb" style="color: #921224;"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Ideas</h3>
                    <p><?= count($projects); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #FFE5D9;">
                    <i class="fas fa-tasks" style="color: #E76F51;"></i>
                </div>
                <div class="stat-info">
                    <h3>In Progress</h3>
                    <p><?= $status_counts['In Progress']; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #D8E2DC;">
                    <i class="fas fa-check-circle" style="color: #2A9D8F;"></i>
                </div>
                <div class="stat-info">
                    <h3>Completed</h3>
                    <p><?= $status_counts['Completed']; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #F4ACB7;">
                    <i class="fas fa-user-friends" style="color: #9D8189;"></i>
                </div>
                <div class="stat-info">
                    <h3>Team Members</h3>
                    <p>5</p>
                </div>
            </div>
        </section>

        <!-- Recent Projects -->
        <section class="projects-section">
            <div class="section-header">
                <h2>Recent Projects</h2>
                <a href="Idea/projectList.php" class="view-all">View All</a>
            </div>
            <div class="projects-grid">
                <?php 
                $recent_projects = array_slice($projects, 0, 4);
                foreach ($recent_projects as $project): 
                    $status_class = strtolower(str_replace(' ', '-', $project['status']));
                ?>
                    <div class="project-card">
                        <h3><?= htmlspecialchars($project['title']); ?></h3>
                        <div class="project-meta">
                            <span class="status-badge <?= $status_class ?>">
                                <?= $project['status'] ?>
                            </span>
                        </div>
                        <p><?= htmlspecialchars(substr($project['short_desc'] ?? $project['detailed_desc'] ?? '', 0, 100)); ?>...</p>
                        <a href="project_dashboard_kanban.php" class="project-link">View Project</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="Idea/add.php" class="action-btn"><i class="fas fa-plus"></i><span>Add New Idea</span></a>
                <a href="project_dashboard_kanban.php" class="action-btn"><i class="fas fa-project-diagram"></i><span>Project Status</span></a>
                <a href="collaborators.php" class="action-btn"><i class="fas fa-user-plus"></i><span>Invite Team</span></a>
                <a href="#" class="action-btn"><i class="fas fa-file-export"></i><span>Generate Report</span></a>
            </div>
        </section>
    </main>
</div>
=======
    <title>Dashboard | IdeaHub</title>
    <img src="../logo_red.png" alt="Logo" style="height:40px;">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>IdeaHub</h2>
                <p>Welcome, <?php echo htmlspecialchars($user['username']); ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="project_dashboard_kanban.php"><i class="fas fa-project-diagram"></i> Projects</a></li>
                    <li><a href="Idea/index.php"><i class="fas fa-lightbulb"></i> Ideas</a></li>
                    <li><a href="collaborators.php"><i class="fas fa-handshake"></i> Collaboration</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Team</a></li>
                    <li><a href="#"><i class="fas fa-calendar"></i> Calendar</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Dashboard</h1>
                <div class="user-actions">
                    <span class="notification-badge"><i class="fas fa-bell"></i> <span>3</span></span>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="User Profile">
                    </div>
                </div>
            </header>

            <!-- Stats Cards -->
            <section class="stats-section">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #BCE0DA;">
                        <i class="fas fa-lightbulb" style="color: #921224;"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Ideas</h3>
                        <p><?php echo count($projects); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #FFE5D9;">
                        <i class="fas fa-tasks" style="color: #E76F51;"></i>
                    </div>
                    <div class="stat-info">
                        <h3>In Progress</h3>
                        <p><?php echo $status_counts['In Progress']; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #D8E2DC;">
                        <i class="fas fa-check-circle" style="color: #2A9D8F;"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Completed</h3>
                        <p><?php echo $status_counts['Completed']; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #F4ACB7;">
                        <i class="fas fa-user-friends" style="color: #9D8189;"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Team Members</h3>
                        <p>5</p>
                    </div>
                </div>
            </section>

            <!-- Recent Projects -->
            <section class="projects-section">
                <div class="section-header">
                    <h2>Recent Projects</h2>
                    <a href="project_dashboard_kanban.php" class="view-all">View All</a>
                </div>
                <div class="projects-grid">
                    <?php 
                    $recent_projects = array_slice($projects, 0, 4);
                    foreach ($recent_projects as $project): 
                    ?>
                    <div class="project-card">
                        <h3><?= htmlspecialchars($project['title']); ?></h3>
                        <span class="status-badge"><?= htmlspecialchars($project['status'] ?? 'Not Started'); ?></span>
                        <p><?= htmlspecialchars(substr($project['description'] ?? '', 0, 100)); ?>...</p>
                        <a href="project_dashboard_kanban.php" class="project-link">View Project</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="add.php" class="action-btn">
                        <i class="fas fa-plus"></i><span>Add New Idea</span>
                    </a>
                    <a href="project_dashboard_kanban.php" class="action-btn">
                        <i class="fas fa-project-diagram"></i><span>Create Project</span>
                    </a>
                    <a href="#" class="action-btn">
                        <i class="fas fa-user-plus"></i><span>Invite Team</span>
                    </a>
                    <a href="#" class="action-btn">
                        <i class="fas fa-file-export"></i><span>Generate Report</span>
                    </a>
                </div>
            </section>
        </main>
    </div>
>>>>>>> f934871340794bd77d000e2117a4f8787c79b82b
</body>
</html>
