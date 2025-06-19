<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login/login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$query = "SELECT id, username, email FROM users WHERE id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaborator List</title>
    <link rel="stylesheet" href="Collab/inv_style.css">
</head>
<body>
    <div class="container">
        <h1 class="header">Collaborator List</h1>

        <div class="form-group">
            <input type="text" id="searchInput" placeholder="Search users..." class="message-input" style="min-height: auto; padding: 8px;">
            <button id="searchButton" class="send-button" style="margin-top: 10px;">Search</button>
        </div>

        <table id="userTable" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f0f8ff;">
                    <th style="padding: 10px; text-align: left;">User</th>
                    <th style="padding: 10px; text-align: left;">Email</th>
                    <th style="padding: 10px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td style="padding: 10px; text-align: center;">
                            <form action="invit_collaboration.php" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="send-button invite-btn">Invite</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <a href="dashboard.php" class="back-button">Back to Dashboard</a>
        </div>
    </div>

    <script src="Collab/collab.js" defer></script>
</body>
</html>
