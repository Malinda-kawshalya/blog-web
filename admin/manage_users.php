<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(3);

// Fetch all users
$stmt = $pdo->prepare("SELECT u.id, u.username, u.email, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="text-xl font-bold">Blog Platform</a>
        <div>
            <a href="dashboard.php" class="btn">Dashboard</a>
            <a href="manage_blogs.php" class="btn ml-2">Manage Blogs</a>
            <a href="../user/logout.php" class="btn ml-2">Logout</a>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">Manage Users</h1>
        <div class="card">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">Username</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Role</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                            <td>
                                <form action="handlers/admin_actions.php" method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <select name="role_id" class="px-2 py-1 border rounded">
                                        <option value="1" <?php echo $user['role_name'] == 'User' ? 'selected' : ''; ?>>User</option>
                                        <option value="2" <?php echo $user['role_name'] == 'Blogger' ? 'selected' : ''; ?>>Blogger</option>
                                        <option value="3" <?php echo $user['role_name'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="update_role" class="btn bg-blue-600 ml-2">Update Role</button>
                                </form>
                                <form action="handlers/admin_actions.php" method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn bg-red-600 ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>