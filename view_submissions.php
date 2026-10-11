
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$sql = "SELECT projects.id, projects.title,
               projects.description, projects.tech_stack,
               projects.file_path, projects.created_at,
               users.full_name, categories.category_name
        FROM projects
        LEFT JOIN users ON projects.user_id = users.id
        LEFT JOIN categories ON projects.category_id = categories.id
        ORDER BY projects.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Submissions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">

        <h2>View Submissions</h2>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered">

                <tr class="table-dark">
                    <th>ID</th>
                    <th>Student</th>
                    <th>Project</th>
                    <th>Category</th>
                    <th>Technology</th>
                    <th>Date</th>
                    <th>File</th>
                </tr>

                <?php if (mysqli_num_rows($result) == 0) { ?>
                    <tr>
                        <td colspan="7">No submissions found.</td>
                    </tr>
                <?php } ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row['full_name'] ?? 'Unknown'); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['category_name'] ?? ''); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['tech_stack']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['created_at']); ?>
                        </td>

                        <td>
                            <?php if (!empty($row['file_path'])) { ?>
                                <a href="download_file.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-success btn-sm">
                                    Download
                                </a>
                            <?php } else { ?>
                                No File
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>

        <a href="admin_dashboard.php" class="btn btn-secondary">
            Back to Dashboard
        </a>

    </div>
</div>

</body>
</html>
