<?php
require_once 'config.php';
$message = '';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    die("<div class='alert alert-danger m-5'>Invalid or missing User ID. <a href='read.php'>Go back</a></div>");
}

try {
    $stmt = $conn->prepare("SELECT name, email, role, created_at FROM members WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        die("<div class='alert alert-danger m-5'>User record not found. <a href='read.php'>Go back</a></div>");
    }
} catch (mysqli_sql_exception $e) {
    die("Database error occurred.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'user'); 

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $conn->prepare("UPDATE members SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: read.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            $message = "Failed to update record.";
        }
    } else {
        $message = "Please provide valid details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Update Member Details</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $message ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning">Update User</button>
                                <a href="read.php" class="btn btn-outline-secondary">Cancel and return to list</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>