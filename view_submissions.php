<?php
require_once 'db.php';

$sql = "SELECT * FROM submissions ORDER BY submission_id DESC";
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
    <h2>View Submissions</h2>
    <p>View assignments submitted by students.</p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Submission ID</th>
                <th>Student ID</th>
                <th>Assignment ID</th>
                <th>File Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $row['submission_id'] ?></td>
                    <td><?= htmlspecialchars($row['student_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['assignment_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['file_name'] ?? '') ?></td>
                    <td>
                        <a href="download_file.php?id=<?= (int) $row['submission_id'] ?>"
                           class="btn btn-primary btn-sm">
                            Download
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No submissions found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</body>
</html>