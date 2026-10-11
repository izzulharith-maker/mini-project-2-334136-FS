<?php
require_once 'db.php';

$sql = "SELECT projects.*, users.* 
        FROM projects 
        LEFT JOIN users ON projects.user_id = users.id
        ORDER BY projects.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submissions</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>View Project Submissions</h2>
    <p>View projects submitted by students.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Project ID</th>
                <th>Student</th>
                <th>Title</th>
                <th>Description</th>
                <th>Tech Stack</th>
                <th>File</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $row['id'] ?></td>
                    <td><?= (int) $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['tech_stack'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($row['file_path'])): ?>
                            <a href="download_file.php?id=<?= (int) $row['id'] ?>">Download</a>
                        <?php else: ?>
                            No file
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No projects found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>