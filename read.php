<?php
require_once 'config.php';

try {
    $result = $conn->query("SELECT id, name, email, role, created_at FROM members ORDER BY id DESC");
    $members = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} catch (mysqli_sql_exception $e) {
    die("Could not retrieve members from the database.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Registered Members</h2>
            <a href="create.php" class="btn btn-success">+ Add New Member</a>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($member['id']) ?></td>
                                        <td><?= htmlspecialchars($member['name']) ?></td>
                                        <td><?= htmlspecialchars($member['email']) ?></td>
                                        <td>
                                            <span class="badge <?= $member['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($member['role'])) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($member['created_at']) ?></td>
                                        <td>
                                            <a href="update.php?id=<?= $member['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="delete.php?id=<?= $member['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No members found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>